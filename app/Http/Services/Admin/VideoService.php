<?php

namespace App\Http\Services\Admin;

use App\Models\Video;
use App\Traits\ResponseTrait;
use App\Models\FileManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideoService
{
    use ResponseTrait;

    public function getAll($userId = null, $search = null)
    {
        $query = Video::query();

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        // Show 8 cards per page
        return $query->orderBy('created_at', 'desc')->paginate(6);
    }

    public function find($id)
    {
        return Video::findOrFail($id);
    }

    public function store($request)
    {
        try {
            DB::beginTransaction();

            $video = new Video();
            $video->user_id = auth()->id();
            $video->tenant_id = auth()->user()->tenant_id;
            $video->title = $request->title ?? $request->file->getClientOriginalName();
            $video->platform = $request->platform;

            if ($request->hasFile('file')) {
                $newFile = new FileManager();
                $uploaded = $newFile->upload('videos', $request->file);

                if (!is_null($uploaded)) {
                    $video->file_name = $uploaded->file_name;
                    $video->file_path = $uploaded->path;
                    $video->file_type = $uploaded->file_type;
                    $video->file_extension = $uploaded->extension;
                    $video->file_size = $uploaded->size;

                    $duration = null;
                    if (function_exists('shell_exec')) {
                        $duration = shell_exec("ffprobe -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 " . escapeshellarg($request->file->getRealPath()));
                        if ($duration) {
                            $duration = round((float) $duration);
                        }
                    }
                    $video->duration = $duration;
                } else {
                    throw new \Exception("File upload failed");
                }
            }

            $video->save();

            DB::commit();

            return $this->success(['video' => $video], getMessage(CREATED_SUCCESSFULLY));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Video store failed: ' . $e->getMessage());
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
    }

    public function update($request, $id)
    {
        try {
            DB::beginTransaction();

            $video = Video::findOrFail($id);
            $video->title = $request->title ?? $video->title;
            $video->platform = $request->platform ?? $video->platform;

            if ($request->hasFile('file')) {
                if ($video->file_path) {
                    $oldFile = FileManager::where('path', $video->file_path)->first();
                    if ($oldFile) {
                        $oldFile->removeFile();
                        $oldFile->delete();
                    }
                }

                $newFile = new FileManager();
                $uploaded = $newFile->upload('videos', $request->file);

                if (!is_null($uploaded)) {
                    $video->file_name = $uploaded->file_name;
                    $video->file_path = $uploaded->path;
                    $video->file_type = $uploaded->file_type;
                    $video->file_extension = $uploaded->extension;
                    $video->file_size = $uploaded->size;

                    $duration = null;
                    if (function_exists('shell_exec')) {
                        $duration = shell_exec("ffprobe -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 " . escapeshellarg($request->file->getRealPath()));
                        if ($duration) {
                            $duration = round((float) $duration);
                        }
                    }
                    $video->duration = $duration;
                }
            }

            $video->save();

            DB::commit();

            return $this->success([], getMessage(UPDATED_SUCCESSFULLY));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Video update failed: ' . $e->getMessage());
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();

            $video = Video::findOrFail($id);

            if ($video->file_path) {
                $file = FileManager::where('path', $video->file_path)->first();
                if ($file) {
                    $file->removeFile();
                    $file->delete();
                }
            }

            $video->delete();

            DB::commit();

            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Video delete failed: ' . $e->getMessage());
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
    }
}