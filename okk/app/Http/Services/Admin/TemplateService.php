<?php

namespace App\Http\Services\Admin;

use App\Models\Template;
use App\Models\FileManager;
use App\Models\Gallery;
use App\Models\Video;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TemplateService
{
    use ResponseTrait;

    public function getAllData($request)
    {
        $query = Template::query();

        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        return datatables($query)
            ->addColumn('media', function ($template) {
                $html = '<div class="d-flex flex-wrap gap-1 align-items-center">';

                if ($template->gallery_image_ids || $template->gallery_video_ids) {
                    if ($template->gallery_image_ids) {
                        $ids = array_filter(explode(',', $template->gallery_image_ids));
                        foreach ($ids as $id) {
                            $gallery = Gallery::find(trim($id));
                            if ($gallery) {
                                $html .= '<img src="' . asset('storage/' . $gallery->file_path) . '" alt="' . e($template->title) . '" class="img-fluid" style="height: 50px; width: 50px; object-fit: cover; border-radius: 5px; flex-shrink: 0;">';
                            }
                        }
                    }
                    if ($template->gallery_video_ids) {
                        $ids = array_filter(explode(',', $template->gallery_video_ids));
                        foreach ($ids as $id) {
                            $vid = Video::find(trim($id));
                            if ($vid) {
                                $html .= '<video src="' . asset('storage/' . $vid->file_path) . '" style="height: 50px; width: 50px; object-fit: cover; border-radius: 5px; flex-shrink: 0;"></video>';
                            }
                        }
                    }
                } else {
                    if ($template->image) {
                        $html .= '<img src="' . asset('storage/' . $template->image) . '" alt="' . $template->title . '" class="img-fluid" style="height: 50px; width: 50px; object-fit: cover; border-radius: 5px;">';
                    }
                    if ($template->video) {
                        $html .= '<video src="' . asset('storage/' . $template->video) . '" style="height: 50px; width: 50px; object-fit: cover; border-radius: 5px;"></video>';
                    }
                }

                if (!$template->image && !$template->video && !$template->gallery_image_ids && !$template->gallery_video_ids) {
                    $html .= '-';
                }
                $html .= '</div>';
                return $html;
            })
            ->addColumn('post_type', function ($template) {
                return $template->post_type ?? '-';
            })
            ->addColumn('platform', function ($template) {
                return SOCIAL_MEDIA_PLATFORMS[$template->platform] ?? $template->platform;
            })
            ->addColumn('status', function ($template) {
                if ($template->status == STATUS_ACTIVE) {
                    return '<span class="status active">' . __('Active') . '</span>';
                } else {
                    return '<span class="status inactive">' . __('Inactive') . '</span>';
                }
            })
            ->addColumn('action', function ($template) {
                return '<div class="dropdown options-area">
                    <a class="options-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-ellipsis"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="location.href=\'' . route('admin.template.edit', $template->id) . '\'">' . __('Edit') . '</a></li>
                        <li><a class="dropdown-item delete-item" href="#" data-route="' . route('admin.template.destroy', $template->id) . '">' . __('Delete') . '</a></li>
                    </ul>
                </div>';
            })
            ->rawColumns(['media', 'status', 'action'])
            ->make(true);
    }

    public function store($request)
    {
        try {
            // Check provider limit
            $providerCheck = getProviderLimitCheckResult($request->platform);
            if (!$providerCheck['allowed']) {
                return $this->error([], $providerCheck['message']);
            }

            DB::beginTransaction();

            $template = new Template();
            $template->title = $request->title;
            $template->slug = $request->slug ?: Str::slug($request->title);
            $template->category_id = $request->category_id;
            $template->short_description = $request->short_description;
            $template->content = $request->content;
            $template->post_type = $request->post_type ?? 'Feed';
            $template->platform = $request->platform;
            $template->status = $request->status == 'active' ? STATUS_ACTIVE : STATUS_DEACTIVATE;
            $template->created_by = auth()->id();
            $template->tenant_id = auth()->user()->tenant_id;

            if ($request->hasFile('direct_media')) {
                foreach ($request->file('direct_media') as $file) {
                    $mime = $file->getMimeType();
                    if (str_starts_with($mime, 'image/') && !$template->image) {
                        $newFile = new FileManager();
                        $uploaded = $newFile->upload('templates', $file);
                        if ($uploaded) {
                            $template->image = $uploaded->path;
                        }
                    } elseif (str_starts_with($mime, 'video/') && !$template->video) {
                        $newFile = new FileManager();
                        $uploaded = $newFile->upload('templates', $file);
                        if ($uploaded) {
                            $template->video = $uploaded->path;
                        }
                    }
                }
            }

            if (!$template->image && $request->hasFile('image')) {
                $newFile = new FileManager();
                $uploaded = $newFile->upload('templates', $request->file('image'));
                if ($uploaded) $template->image = $uploaded->path;
            }
            if (!$template->video && $request->hasFile('video')) {
                $newFile = new FileManager();
                $uploaded = $newFile->upload('templates', $request->file('video'));
                if ($uploaded) $template->video = $uploaded->path;
            }

            $template->gallery_image_ids = $request->gallery_image_ids;
            $template->gallery_video_ids = $request->gallery_video_ids;

            $template->save();

            DB::commit();

            return $this->success(['redirect_url' => route('admin.template.index')], getMessage(CREATED_SUCCESSFULLY));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }

    public function update($request, $id)
    {
        try {
            // Check provider limit
            $providerCheck = getProviderLimitCheckResult($request->platform);
            if (!$providerCheck['allowed']) {
                return $this->error([], $providerCheck['message']);
            }

            DB::beginTransaction();

            $template = Template::findOrFail($id);
            $template->title = $request->title;
            $template->slug = $request->slug ?: Str::slug($request->title);
            $template->category_id = $request->category_id;
            $template->short_description = $request->short_description;
            $template->content = $request->content;
            $template->post_type = $request->post_type ?? 'Feed';
            $template->platform = $request->platform;
            $template->status = $request->status == 'active' ? STATUS_ACTIVE : STATUS_DEACTIVATE;

            $newImageSet = false;
            $newVideoSet = false;

            if ($request->hasFile('direct_media')) {
                foreach ($request->file('direct_media') as $file) {
                    $mime = $file->getMimeType();
                    if (str_starts_with($mime, 'image/') && !$newImageSet) {
                        // remove old image file via FileManager if exists
                        if ($template->image) {
                            $oldImageFile = FileManager::where('path', $template->image)->first();
                            if ($oldImageFile) {
                                $oldImageFile->removeFile();
                                $oldImageFile->delete();
                            }
                        }

                        $newFile = new FileManager();
                        $uploaded = $newFile->upload('templates', $file);
                        if ($uploaded) {
                            $template->image = $uploaded->path;
                            $newImageSet = true;
                        }
                    } elseif (str_starts_with($mime, 'video/') && !$newVideoSet) {
                        // remove old video file via FileManager if exists
                        if ($template->video) {
                            $oldVideoFile = FileManager::where('path', $template->video)->first();
                            if ($oldVideoFile) {
                                $oldVideoFile->removeFile();
                                $oldVideoFile->delete();
                            }
                        }

                        $newFile = new FileManager();
                        $uploaded = $newFile->upload('templates', $file);
                        if ($uploaded) {
                            $template->video = $uploaded->path;
                            $newVideoSet = true;
                        }
                    }
                }
            }

            if (!$newImageSet && $request->hasFile('image')) {
                if ($template->image) {
                    $oldImageFile = FileManager::where('path', $template->image)->first();
                    if ($oldImageFile) {
                        $oldImageFile->removeFile();
                        $oldImageFile->delete();
                    }
                }

                $newFile = new FileManager();
                $uploaded = $newFile->upload('templates', $request->file('image'));
                if ($uploaded) {
                    $template->image = $uploaded->path;
                    $newImageSet = true;
                }
            }
            if (!$newVideoSet && $request->hasFile('video')) {
                if ($template->video) {
                    $oldVideoFile = FileManager::where('path', $template->video)->first();
                    if ($oldVideoFile) {
                        $oldVideoFile->removeFile();
                        $oldVideoFile->delete();
                    }
                }

                $newFile = new FileManager();
                $uploaded = $newFile->upload('templates', $request->file('video'));
                if ($uploaded) {
                    $template->video = $uploaded->path;
                    $newVideoSet = true;
                }
            }

            if (!$newImageSet && !$request->existing_image) {
                if ($template->image) {
                    $oldImageFile = FileManager::where('path', $template->image)->first();
                    if ($oldImageFile) {
                        $oldImageFile->removeFile();
                        $oldImageFile->delete();
                    }
                }
                $template->image = null;
            }
            if (!$newVideoSet && !$request->existing_video) {
                if ($template->video) {
                    $oldVideoFile = FileManager::where('path', $template->video)->first();
                    if ($oldVideoFile) {
                        $oldVideoFile->removeFile();
                        $oldVideoFile->delete();
                    }
                }
                $template->video = null;
            }

            $template->gallery_image_ids = $request->gallery_image_ids;
            $template->gallery_video_ids = $request->gallery_video_ids;

            $template->save();

            DB::commit();

            return $this->success(['redirect_url' => route('admin.template.index')], getMessage(UPDATED_SUCCESSFULLY));

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }

    public function deleteById($id)
    {
        try {
            DB::beginTransaction();
            $template = Template::findOrFail($id);

            // Remove directly uploaded image/video files via FileManager if they exist
            if ($template->image) {
                $imageFile = FileManager::where('path', $template->image)->first();
                if ($imageFile) {
                    $imageFile->removeFile();
                    $imageFile->delete();
                }
            }
            if ($template->video) {
                $videoFile = FileManager::where('path', $template->video)->first();
                if ($videoFile) {
                    $videoFile->removeFile();
                    $videoFile->delete();
                }
            }

            $template->delete();
            DB::commit();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }

    public function getEditData(int $id): array
    {
        $template   = Template::findOrFail($id);
        $categories = \App\Models\Category::where('status', STATUS_ACTIVE)->get();
        $galleries  = Gallery::latest()->get();
        $videos     = Video::latest()->get();

        $existingImages = [];

        // First, check for directly uploaded image
        if ($template->image) {
            $imageUrl = null;
            $imageFile = FileManager::where('path', $template->image)->first();
            if ($imageFile) {
                $imageUrl = getFile($imageFile->path, $imageFile->storage_type);
            } else {
                $imageUrl = asset('storage/' . $template->image);
            }

            $existingImages[] = [
                'id'  => 'uploaded:' . $template->image,
                'url' => $imageUrl,
            ];
        }

        // Then, check gallery images
        foreach (array_filter(explode(',', $template->gallery_image_ids ?? '')) as $imgId) {
            $g = Gallery::find($imgId);
            if ($g) {
                $galleryImageUrl = null;
                $galleryImageFile = FileManager::where('path', $g->file_path)->first();
                if ($galleryImageFile) {
                    $galleryImageUrl = getFile($galleryImageFile->path, $galleryImageFile->storage_type);
                } else {
                    $galleryImageUrl = asset('storage/' . $g->file_path);
                }

                $existingImages[] = [
                    'id'  => (int) $imgId,
                    'url' => $galleryImageUrl,
                ];
            }
        }

        $existingVideos = [];

        // First, check for directly uploaded video
        if ($template->video) {
            $videoUrl = null;
            $videoFile = FileManager::where('path', $template->video)->first();
            if ($videoFile) {
                $videoUrl = getFile($videoFile->path, $videoFile->storage_type);
            } else {
                $videoUrl = asset('storage/' . $template->video);
            }

            $existingVideos[] = [
                'id'  => 'uploaded:' . $template->video,
                'url' => $videoUrl,
            ];
        }

        // Then, check gallery videos
        foreach (array_filter(explode(',', $template->gallery_video_ids ?? '')) as $vidId) {
            $v = Video::find($vidId);
            if ($v) {
                $galleryVideoUrl = null;
                $galleryVideoFile = FileManager::where('path', $v->file_path)->first();
                if ($galleryVideoFile) {
                    $galleryVideoUrl = getFile($galleryVideoFile->path, $galleryVideoFile->storage_type);
                } else {
                    $galleryVideoUrl = asset('storage/' . $v->file_path);
                }

                $existingVideos[] = [
                    'id'  => (int) $vidId,
                    'url' => $galleryVideoUrl,
                ];
            }
        }

        return [
            'template'       => $template,
            'categories'     => $categories,
            'galleries'      => $galleries,
            'videos'         => $videos,
            'templateConfig' => [
                'isEdit'         => true,
                'postType'       => $template->post_type ?? 'Feed',
                'platform'       => $template->platform ?? '',
                'existingImages' => $existingImages,
                'existingVideos' => $existingVideos,
            ],
        ];
    }

    public function getTemplateList(): array
    {
        $templates = Template::where('status', STATUS_ACTIVE)
            ->orderBy('updated_at', 'desc')
            ->get(['id', 'title', 'content', 'post_type', 'platform', 'image', 'video', 'gallery_image_ids', 'gallery_video_ids']);

        return $templates->map(function ($t) {
            $media = [];

            // First, check for directly uploaded images
            if ($t->image) {
                $imageUrl = null;
                $imageFile = FileManager::where('path', $t->image)->first();
                if ($imageFile) {
                    $imageUrl = getFile($imageFile->path, $imageFile->storage_type);
                } else {
                    $imageUrl = asset('storage/' . $t->image);
                }

                $media[] = [
                    'type' => 'image',
                    'id'   => 'uploaded:' . $t->image,
                    'url'  => $imageUrl,
                ];
            }

            // Check for directly uploaded videos
            if ($t->video) {
                $videoUrl = null;
                $videoFile = FileManager::where('path', $t->video)->first();
                if ($videoFile) {
                    $videoUrl = getFile($videoFile->path, $videoFile->storage_type);
                } else {
                    $videoUrl = asset('storage/' . $t->video);
                }

                $media[] = [
                    'type' => 'video',
                    'id'   => 'uploaded:' . $t->video,
                    'url'  => $videoUrl,
                ];
            }

            // Then, check gallery images
            foreach (array_filter(explode(',', $t->gallery_image_ids ?? '')) as $id) {
                $g = Gallery::find($id);
                if ($g) {
                    $galleryImageUrl = null;
                    $galleryImageFile = FileManager::where('path', $g->file_path)->first();
                    if ($galleryImageFile) {
                        $galleryImageUrl = getFile($galleryImageFile->path, $galleryImageFile->storage_type);
                    } else {
                        $galleryImageUrl = asset('storage/' . $g->file_path);
                    }

                    $media[] = [
                        'type' => 'image',
                        'id'   => (int) $id,
                        'url'  => $galleryImageUrl,
                    ];
                }
            }

            // Then, check gallery videos
            foreach (array_filter(explode(',', $t->gallery_video_ids ?? '')) as $id) {
                $v = Video::find($id);
                if ($v) {
                    $galleryVideoUrl = null;
                    $galleryVideoFile = FileManager::where('path', $v->file_path)->first();
                    if ($galleryVideoFile) {
                        $galleryVideoUrl = getFile($galleryVideoFile->path, $galleryVideoFile->storage_type);
                    } else {
                        $galleryVideoUrl = asset('storage/' . $v->file_path);
                    }

                    $media[] = [
                        'type' => 'video',
                        'id'   => (int) $id,
                        'url'  => $galleryVideoUrl,
                    ];
                }
            }

            $platformLabel = '—';
            if ($t->platform !== null) {
                $platformLabel = SOCIAL_MEDIA_PLATFORMS[$t->platform] ?? $t->platform;
            }

            return [
                'id'                => $t->id,
                'title'             => $t->title,
                'content'           => $t->content ?? '',
                'post_type'         => $t->post_type ?? 'Feed',
                'platform'          => $t->platform,
                'platform_label'    => $platformLabel,
                'gallery_image_ids' => $t->gallery_image_ids,
                'gallery_video_ids' => $t->gallery_video_ids,
                'media'             => $media,
            ];
        })->values()->all();
    }
}