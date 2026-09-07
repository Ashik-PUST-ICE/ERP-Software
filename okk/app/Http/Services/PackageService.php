<?php

namespace App\Http\Services;

use App\Models\Gateway;
use App\Models\GatewayCurrency;
use App\Models\Package;
use App\Models\UserPackage;
use App\Models\FileManager;
use App\Http\Requests\SuperAdmin\PackageRequest;
use App\Http\Services\Payment\Payment;
use Stripe\StripeClient;
use App\Traits\ImageSaveTrait;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PackageService
{
    use ImageSaveTrait, ResponseTrait;


    private function createOrUpdateStripePlans($package, $request = null)
    {
        
        $stripeFields = ['name', 'monthly_price', 'yearly_price'];
        $stripeSynced = $package->stripe_monthly_plan_id && $package->stripe_product_id;
        $paypalSynced = $package->paypal_monthly_plan_id && $package->paypal_product_id;

        if (!$package->wasChanged($stripeFields) && $stripeSynced && $paypalSynced) {
            return;
        }

        try {
            foreach (RECURRING_GATEWAY as $gatewaySlug) {
                // Check gateway is active and has credentials
                $gateway = Gateway::where(['slug' => $gatewaySlug, 'status' => STATUS_ACTIVE])->first();

                if (!$gateway || empty($gateway->key) || empty($gateway->secret)) {
                    Log::info("Skipping {$gatewaySlug}: not configured");
                    continue;
                }

                // Setup payment service
                $currency = GatewayCurrency::where('gateway_id', $gateway->id)->value('currency') ?: 'USD';
                $paymentService = new Payment($gatewaySlug, ['currency' => $currency, 'type' => 'plan']);

                Log::info("Syncing {$gatewaySlug} for package: {$package->name}");

                // Prepare images
                $images = [];
                if ($package->icon) {
                    $iconUrl = getFileUrl($package->icon);
                    if (filter_var($iconUrl, FILTER_VALIDATE_URL) && str_starts_with($iconUrl, 'https://')) {
                        $images[] = $iconUrl;
                    }
                }

                // Prepare data for API
                $data = [
                    'name' => $package->name,
                    'description' => $package->description,
                    'images' => $images,
                    'metadata' => [
                        'package_id' => $package->id,
                        'package_slug' => $package->slug,
                        'ai_enabled' => $package->ai_enabled ? 'yes' : 'no',
                        'post_limit' => $package->post_limit ?? 0,
                        'created_at' => $package->created_at?->toISOString(),
                    ],
                    'monthly_price' => $request->monthly_price ?? $package->monthly_price,
                    'yearly_price' => $request->yearly_price ?? $package->yearly_price,
                ];

                // Add existing IDs for Stripe
                if ($gatewaySlug === 'stripe') {
                    $data['stripe_product_id'] = $package->getOriginal('stripe_product_id');
                    $data['monthlyPriceId'] = $package->getOriginal('stripe_monthly_plan_id');
                    $data['yearlyPriceId'] = $package->getOriginal('stripe_yearly_plan_id');
                }

                // Add existing IDs for PayPal
                if ($gatewaySlug === 'paypal') {
                    $data['paypal_product_id'] = $package->getOriginal('paypal_product_id');
                    $data['monthlyPriceId'] = $package->getOriginal('paypal_monthly_plan_id');
                    $data['yearlyPriceId'] = $package->getOriginal('paypal_yearly_plan_id');
                }

                // Call API
                $response = $paymentService->saveProductSaas($data);

                if (!$response['success']) {
                    Log::error("{$gatewaySlug} sync failed: " . ($response['message'] ?? 'Unknown error'));
                    continue;
                }

                // Update package with new IDs from Stripe
                if ($gatewaySlug === 'stripe') {
                    $updates = [];
                    if (isset($response['data']['product_id'])) {
                        $updates['stripe_product_id'] = $response['data']['product_id'];
                    }
                    if (isset($response['data']['monthly_price_id'])) {
                        $updates['stripe_monthly_plan_id'] = $response['data']['monthly_price_id'];
                    }
                    if (isset($response['data']['yearly_price_id'])) {
                        $updates['stripe_yearly_plan_id'] = $response['data']['yearly_price_id'];
                    }

                    if (!empty($updates)) {
                        $package->update($updates);
                        Log::info("Stripe sync success", $updates);
                    }
                }

                // Update package with new IDs from PayPal
                if ($gatewaySlug === 'paypal') {
                    $updates = [];
                    if (isset($response['data']['product_id'])) {
                        $updates['paypal_product_id'] = $response['data']['product_id'];
                    }
                    if (isset($response['data']['monthly_price_id'])) {
                        $updates['paypal_monthly_plan_id'] = $response['data']['monthly_price_id'];
                    }
                    if (isset($response['data']['yearly_price_id'])) {
                        $updates['paypal_yearly_plan_id'] = $response['data']['yearly_price_id'];
                    }

                    if (!empty($updates)) {
                        $package->update($updates);
                        Log::info("PayPal sync success", $updates);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error("Sync error for {$package->name}: " . $e->getMessage());
        }
    }


    public function getActiveAll()
    {
        return Package::where('status', STATUS_ACTIVE)->orderBy('id', 'DESC')->get();
    }

    public function getAllData()
    {
        $packages = Package::orderBy('id', 'DESC')->select('id', 'name', 'slug', 'monthly_price', 'yearly_price', 'status', 'icon', 'old_monthly_price', 'old_yearly_price', 'ai_enabled', 'features', 'description', 'post_limit', 'provider_limit');
        return datatables($packages)
            ->addIndexColumn()
            ->editColumn('status', function ($data) {
                $return = __('Inactive');
                if ($data->status == STATUS_ACTIVE) {
                    $return = __('Active');
                }

                return $return;
            })
            ->editColumn('ai_enabled', function ($data) {
                return $data->ai_enabled ? __('Yes') : __('No');
            })
            ->editColumn('post_limit', function ($data) {
                return $data->post_limit ? $data->post_limit . ' ' . __('days') : __('No');
            })
            ->addColumn('icon', function ($data) {
                return '<div class="min-w-160 d-flex align-items-center cg-10"><div class="flex-shrink-0 w-35 h-35 bd-one bd-c-cdef84 rounded-circle overflow-hidden bg-eaeaea d-flex justify-content-center align-items-center"><img src="' . getFileUrl($data->icon) . '" alt="icon" class="rounded avatar-xs w-100"></div><p>' . htmlspecialchars($data->name) . '</p></div>';
            })
            ->addColumn('action', function ($data) {
                if (auth()->user()->role == USER_ROLE_SUPER_ADMIN) {
                    $role = 'super_admin';
                } else {
                    $role = 'admin';
                }
                return '<ul class="d-flex align-items-center cg-5 justify-content-center">
                <li class="align-items-center d-flex gap-2">
                    <button onclick="getEditModal(\'' . route($role . '.packages.edit', $data->id) . '\', \'#edit-modal\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" data-bs-toggle="modal" data-bs-target="#alumniPhoneNo">
                        <img src="' . asset('assets/images/icon/edit.svg') . '" alt="edit" />
                    </button>
                    <button onclick="deleteItem(\'' . route($role . '.packages.delete', $data->id) . '\', \'commonDataTable\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" title="' . __('Delete') . '">
                        <img src="' . asset('assets/images/icon/delete-1.svg') . '" alt="delete">
                    </button>
                </li>
            </ul>';
            })
            ->rawColumns(['action', 'status', 'icon'])
            ->make(true);
    }

    /**
     * Get all packages for index view (non-ajax)
     */
    public function getAllPackages()
    {
        return Package::orderBy('id', 'DESC')->get();
    }

    /**
     * DataTables list for super_admin packages index (AJAX)
     */
    public function getPackagesListData()
    {
        $packages = Package::orderBy('id', 'DESC')
            ->select('id', 'name', 'slug', 'monthly_price', 'yearly_price', 'status', 'icon', 'old_monthly_price', 'old_yearly_price', 'ai_enabled', 'features', 'description', 'post_limit', 'provider_limit');
        return datatables($packages)
            ->addColumn('sl', function ($data) {
                $start = (int) request()->input('start', 0);
                static $count = 0;
                return $start + (++$count);
            })
            ->addColumn('icon', function ($data) {
                if ($data->icon) {
                    return '<img src="' . getFileUrl($data->icon) . '" alt="' . e($data->name) . '" class="package-icon" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">';
                }
                return '<span class="text-muted">-</span>';
            })
            ->addColumn('provider', function ($data) {
                if ($data->provider_limit && is_array($data->provider_limit) && count($data->provider_limit) > 0) {
                    $providers = defined('SOCIAL_MEDIA_PLATFORMS') ? SOCIAL_MEDIA_PLATFORMS : [];
                    $names = [];
                    foreach ((array) $data->provider_limit as $id) {
                        if (isset($providers[$id])) {
                            $names[] = '<span class="badge package-badge me-1">' . e($providers[$id]) . '</span>';
                        }
                    }
                    return count($names) > 0 ? implode('', $names) : '<span class="text-muted">' . __('N/A') . '</span>';
                }
                return '<span class="text-muted">' . __('N/A') . '</span>';
            })
            ->addColumn('price', function ($data) {
                return '$' . number_format((float) $data->monthly_price, 2);
            })
            ->addColumn('old_price', function ($data) {
                return $data->old_monthly_price ? '$' . number_format((float) $data->old_monthly_price, 2) : '<span class="text-muted">' . __('N/A') . '</span>';
            })
            ->addColumn('ai_enabled', function ($data) {
                return $data->ai_enabled
                    ? '<span class="status active">' . __('Yes') . '</span>'
                    : '<span class="status failed">' . __('No') . '</span>';
            })
            ->addColumn('features', function ($data) {
                if ($data->features && is_array($data->features) && count($data->features) > 0) {
                    $slice = array_slice($data->features, 0, 2);
                    $html = '';
                    foreach ($slice as $f) {
                        $html .= '<div>' . e(\Str::limit($f, 20)) . '</div>';
                    }
                    if (count($data->features) > 2) {
                        $html .= '<div class="text-muted">+' . (count($data->features) - 2) . ' ' . __('more') . '</div>';
                    }
                    return $html;
                }
                return '<span class="text-muted">' . __('No features') . '</span>';
            })
            ->addColumn('status', function ($data) {
                return $data->status
                    ? '<span class="status active">' . __('Active') . '</span>'
                    : '<span class="status failed">' . __('Inactive') . '</span>';
            })
            ->addColumn('action', function ($data) {
                $editUrl = route('super_admin.packages.update', $data->id);
                $deleteUrl = route('super_admin.packages.destroy', $data->id);
                return '<div class="dropdown options-area">
                    <a class="options-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#package-modal" onclick="openPackageModal(' . $data->id . ')">' . __('Edit') . '</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="deleteItem(\'' . $deleteUrl . '\', \'packagesDataTable\')">' . __('Delete') . '</a></li>
                    </ul>
                </div>';
            })
            ->addColumn('package_json', function ($data) {
                return json_encode($data->toArray());
            })
            ->rawColumns(['icon', 'provider', 'old_price', 'ai_enabled', 'features', 'status', 'action'])
            ->make(true);
    }

    public function store(PackageRequest $request): mixed
    {
        DB::beginTransaction();

        try {
            $package = new Package();

            // Handle icon upload via FileManager so that getFileUrl() works
            if ($request->hasFile('icon')) {
                $newFile = new FileManager();
                $uploaded = $newFile->upload('packages', $request->icon);

                if (is_null($uploaded)) {
                    throw new Exception(getMessage(SOMETHING_WENT_WRONG));
                }

                // Store FileManager ID in icon column
                $package->icon = $uploaded->id;
            }

            // Fill package data
            $package->fill([
                'name' => $request->name,
                'slug' => $request->slug,
                'description' => $request->description,
                'monthly_price' => $request->monthly_price,
                'old_monthly_price' => $request->old_monthly_price ?? 0.00,
                'yearly_price' => $request->yearly_price ?? 0.00,
                'old_yearly_price' => $request->old_yearly_price ?? 0.00,
                'stripe_monthly_plan_id' => $request->stripe_monthly_plan_id,
                'stripe_yearly_plan_id' => $request->stripe_yearly_plan_id,
                'paypal_monthly_plan_id' => $request->paypal_monthly_plan_id,
                'paypal_yearly_plan_id' => $request->paypal_yearly_plan_id,
                'ai_enabled' => $request->ai_enabled ?? false,
                'provider_limit' => $request->provider_limit ?? [],
                'features' => $request->features ?? [],
                'post_limit' => $request->post_limit ?? 0,
                'status' => $request->status ?? false,
            ]);

            $package->save();

            Log::info('Package created', ['package_id' => $package->id, 'data' => $package->toArray()]);

            // Create plan in payment gateway if requested
            if ($request->create_plan_in_gateway) {
                $this->createOrUpdateStripePlans($package, $request);
            }

            DB::commit();

            return $this->success(
                ['route' => route('super_admin.packages.index', $package->id)],
                getMessage(CREATED_SUCCESSFULLY)
            );
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }


    public function update(PackageRequest $request, $id): mixed
    {
        DB::beginTransaction();
        try {
            $package = Package::findOrFail($id);

            // Handle icon upload via FileManager so that getFileUrl() works
            if ($request->hasFile('icon')) {
                $fileId = $package->icon;

                // Reuse existing FileManager record if possible
                if ($fileId) {
                    $existingFile = FileManager::where('id', $fileId)->first();
                    if ($existingFile) {
                        $existingFile->removeFile();
                        $uploaded = $existingFile->upload('packages', $request->icon, '', $existingFile->id);
                    } else {
                        $newFile = new FileManager();
                        $uploaded = $newFile->upload('packages', $request->icon);
                    }
                } else {
                    $newFile = new FileManager();
                    $uploaded = $newFile->upload('packages', $request->icon);
                }

                if (is_null($uploaded)) {
                    throw new Exception(getMessage(SOMETHING_WENT_WRONG));
                }

                $package->icon = $uploaded->id;
            }

            // Update package data
            $package->fill([
                'name' => $request->name,
                'slug' => $request->slug,
                'description' => $request->description,
                'monthly_price' => $request->monthly_price,
                'old_monthly_price' => $request->old_monthly_price ?? 0.00,
                'yearly_price' => $request->yearly_price ?? 0.00,
                'old_yearly_price' => $request->old_yearly_price ?? 0.00,
                'stripe_monthly_plan_id' => $request->stripe_monthly_plan_id,
                'stripe_yearly_plan_id' => $request->stripe_yearly_plan_id,
                'paypal_monthly_plan_id' => $request->paypal_monthly_plan_id,
                'paypal_yearly_plan_id' => $request->paypal_yearly_plan_id,
                'ai_enabled' => $request->ai_enabled ?? false,
                'provider_limit' => $request->provider_limit ?? [],
                'features' => $request->features ?? [],
                'post_limit' => $request->post_limit ?? 0,
                'status' => $request->status ?? false,
            ]);

            $package->save();

            // Create plan in payment gateway if requested
            if ($request->create_plan_in_gateway) {
                $this->createOrUpdateStripePlans($package, $request);
            }

            DB::commit();

            return $this->success(
                ['route' => route('super_admin.packages.index', $package->id)],
                getMessage(UPDATED_SUCCESSFULLY)
            );
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }

    public function getById($id)
    {
        return Package::findOrFail($id);
    }

    public function deleteById($id)
    {
        DB::beginTransaction();
        try {
            $package = Package::findOrFail($id);
            $package->delete();
            DB::commit();
            $message = getMessage(DELETED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    public function getUserPackagesData($request, $user_id)
    {
        $packageable_id = $request->input('packageable_id');

        $ownerPackages = UserPackage::query()
            ->join('users', 'user_packages.user_id', '=', 'users.id')
            ->leftJoin('payments', 'user_packages.payment_id', '=', 'payments.id')
            ->leftJoin('gateways', 'payments.gateway_id', '=', 'gateways.id')
            ->leftJoin('packages', 'user_packages.packageable_id', '=', 'packages.id')
            ->when($packageable_id, function ($query) use ($packageable_id) {
                $query->where('user_packages.packageable_id', $packageable_id);
            })
            ->with('packageable:id,name')
            ->select(
                'user_packages.*',
                'users.name as userName',
                'users.email',
                'gateways.title as gateway_title',
                'packages.name as package_name'
            )
            ->orderBy('user_packages.id', 'desc');

        return datatables($ownerPackages)
            ->addIndexColumn()
            ->addColumn('user_name', function ($ownerPackage) {
                return $ownerPackage->userName;
            })
            ->addColumn('package_name', function ($ownerPackage) {
                if (!empty($ownerPackage->package_name)) {
                    return $ownerPackage->package_name;
                }

                return $ownerPackage->packageable ? $ownerPackage->packageable->name : 'N/A';
            })
            ->addColumn('gateway_name', function ($ownerPackage) {
                return $ownerPackage->gateway_title ?? 'N/A';
            })
            ->addColumn('start_date', function ($ownerPackage) {
                return date('Y-m-d', strtotime($ownerPackage->start_date));
            })
            ->addColumn('end_date', function ($ownerPackage) {
                return date('Y-m-d', strtotime($ownerPackage->end_date));
            })
            ->addColumn('action', function ($data) {
                 return '<div class="inline-flex">
                             <div class="dropdown options-area">
                                 <a class="options-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                     <i class="fa-solid fa-ellipsis"></i>
                                 </a>
                                 <ul class="dropdown-menu dropdown-menu-end">
                                     <li>
                                         <a class="dropdown-item" href="javascript:void(0)" onclick="getEditModal(\'' . route('super_admin.packages.edit_user_package', $data->id) . '\', \'#edit-modal\')">
                                             ' . __('Edit') . '
                                         </a>
                                     </li>
                                     <li>
                                         <a class="dropdown-item revoke-package" href="javascript:void(0)" data-url="' . route('super_admin.packages.revoke', $data->id) . '">
                                             ' . __('Revoke') . '
                                         </a>
                                     </li>
                                 </ul>
                             </div>
                         </div>';
             })
            ->addColumn('status', function ($ownerPackage) {
                if ($ownerPackage->status == STATUS_ACTIVE && $ownerPackage->end_date >= now()) {
                    return '<div class="d-inline-block py-6 px-10 bd-ra-6 fs-14 fw-500 lh-16 text-0fa958 bg-0fa958-10">' . __('Active') . '</div>';
                } elseif ($ownerPackage->status == STATUS_CANCELLED) {
                    return '<div class="d-inline-block py-6 px-10 bd-ra-6 fs-14 fw-500 lh-16 text-f5b40a bg-f5b40a-10">' . __('Deactive') . '</div>';
                } elseif ($ownerPackage->status == STATUS_REFUND) {
                    return '<div class="d-inline-block py-6 px-10 bd-ra-6 fs-14 fw-500 lh-16 text-ea4335 bg-ea4335-10">' . __('Refund') . '</div>';
                } else {
                    return '<div class="d-inline-block py-6 px-10 bd-ra-6 fs-14 fw-500 lh-16 text-ea4335 bg-ea4335-10">' . __('Expired') . '</div>';
                }
            })
            ->rawColumns(['user_name', 'package_name', 'start_date', 'end_date', 'status', 'action'])
            ->make(true);
    }

    public function assignPackage($request, $package)
    {
        DB::beginTransaction();
        try {
            $userId = $request->user_id;

            UserPackage::where('user_id', $userId)
                ->where('status', STATUS_ACTIVE)
                ->where('end_date', '>=', now())
                ->update(['status' => STATUS_CANCELLED]);

            $expiredDate = $request->duration_type == SUBSCRIPTION_TYPE_MONTHLY ? now()->addMonth() : now()->addYear();

            $package->userPackage()->create([
                'user_id' => $userId,
                'start_date' => now(),
                'end_date' => $expiredDate,
                'status' => STATUS_ACTIVE,
                'subscription_type' => $request->duration_type,
            ]);

            DB::commit();
            return $this->success([], __('Assigned Successful'));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }

    public function revokePackage($id)
    {
        DB::beginTransaction();
        try {
            $userPackage = UserPackage::findOrFail($id);
            $userPackage->update(['status' => STATUS_CANCELLED]);

            DB::commit();
            return $this->success([], __('Revoke Successful'));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }

    public function getUserPackageById($id)
    {
        return UserPackage::with('packageable:id,name')->findOrFail($id);
    }

    public function updateUserPackageStatus($id, $status)
    {
        DB::beginTransaction();
        try {
            $userPackage = UserPackage::findOrFail($id);
            $userPackage->status = $status;
            $userPackage->save();

            DB::commit();
            return $this->success([], __('Status updated successfully'));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }
}