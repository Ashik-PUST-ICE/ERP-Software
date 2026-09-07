<?php

namespace App\Http\Services\Admin;

use App\Models\Gallery;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\FileManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryService
{
    use ResponseTrait;

    public function getAll($userId = null, $search = null)
    {
        $tenantId = auth()->user()->tenant_id ?? null;
        $query = Gallery::query()
            ->when($tenantId, fn($q) => $q->where('tenant_id', $tenantId));

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
        $tenantId = auth()->user()->tenant_id ?? null;
        return Gallery::when($tenantId, fn($q) => $q->where('tenant_id', $tenantId))->findOrFail($id);
    }

    public function store($request)
    {
        try {
            DB::beginTransaction();

            $gallery = new Gallery();
            $gallery->user_id = auth()->id();
            $gallery->tenant_id = auth()->user()->tenant_id;
            $gallery->title = $request->title ?? $request->file->getClientOriginalName();
            $gallery->platform = $request->platform;

            if ($request->hasFile('file')) {
                $newFile = new FileManager();
                $uploaded = $newFile->upload('galleries', $request->file);

                if (!is_null($uploaded)) {
                    $gallery->file_name = $uploaded->file_name;
                    $gallery->file_path = $uploaded->path;
                    $gallery->file_type = $uploaded->file_type;
                    $gallery->file_extension = $uploaded->extension;
                    $gallery->file_size = $uploaded->size;

                    $dimensions = null;
                    if (in_array(strtolower($uploaded->extension), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                        $imageInfo = @getimagesize($request->file->getRealPath());
                        if ($imageInfo) {
                            $dimensions = $imageInfo[0] . ' x ' . $imageInfo[1];
                        }
                    }
                    $gallery->dimensions = $dimensions;
                } else {
                    throw new \Exception("File upload failed");
                }
            }

            $gallery->save();

            DB::commit();

            return $this->success(['gallery' => $gallery], getMessage(CREATED_SUCCESSFULLY));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gallery store failed: ' . $e->getMessage());
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
    }

    public function update($request, $id)
    {
        try {
            DB::beginTransaction();

            $gallery = Gallery::when(auth()->user()->tenant_id, fn($q) => $q->where('tenant_id', auth()->user()->tenant_id))->findOrFail($id);
            $gallery->title = $request->title ?? $gallery->title;
            $gallery->platform = $request->platform ?? $gallery->platform;

            if ($request->hasFile('file')) {
                if ($gallery->file_path) {
                    $oldFile = FileManager::where('path', $gallery->file_path)->first();
                    if ($oldFile) {
                        $oldFile->removeFile();
                        $oldFile->delete();
                    }
                }

                $newFile = new FileManager();
                $uploaded = $newFile->upload('galleries', $request->file);

                if (!is_null($uploaded)) {
                    $gallery->file_name = $uploaded->file_name;
                    $gallery->file_path = $uploaded->path;
                    $gallery->file_type = $uploaded->file_type;
                    $gallery->file_extension = $uploaded->extension;
                    $gallery->file_size = $uploaded->size;

                    $dimensions = null;
                    if (in_array(strtolower($uploaded->extension), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                        $imageInfo = @getimagesize($request->file->getRealPath());
                        if ($imageInfo) {
                            $dimensions = $imageInfo[0] . ' x ' . $imageInfo[1];
                        }
                    }
                    $gallery->dimensions = $dimensions;
                }
            }

            $gallery->save();

            DB::commit();

            return $this->success([], getMessage(UPDATED_SUCCESSFULLY));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gallery update failed: ' . $e->getMessage());
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();

            $gallery = Gallery::when(auth()->user()->tenant_id, fn($q) => $q->where('tenant_id', auth()->user()->tenant_id))->findOrFail($id);

            if ($gallery->file_path) {
                $file = FileManager::where('path', $gallery->file_path)->first();
                if ($file) {
                    $file->removeFile();
                    $file->delete();
                }
            }

            $gallery->delete();

            DB::commit();

            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gallery delete failed: ' . $e->getMessage());
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
    }
}