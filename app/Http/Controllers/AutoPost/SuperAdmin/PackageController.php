<?php

namespace App\Http\Controllers\AutoPost\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\PackageRequest;
use App\Http\Services\PackageService;
use App\Models\User;
use App\Models\Package;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PackageController extends Controller
{
    use ResponseTrait;

    private $packageService;

    public function __construct()
    {
        $this->packageService = new PackageService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['title'] = __('Packages');
        $data['breadcrumb'] = __('Billing Center') . ' / ' . __('Packages');
        $data['activePackages'] = 'active';
        $data['showSubscriptionService'] = 'show';
        return view('auto_posts.super_admin.packages.index', $data);
    }

    public function data(Request $request)
    {
        return $this->packageService->getPackagesListData();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Redirect to index page as we use modal for create/edit
        return redirect()->route('super_admin.packages.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PackageRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PackageRequest $request)
    {
        try {
            DB::beginTransaction();
            $result = $this->packageService->store($request);
            DB::commit();

            if ($request->ajax()) {
                return $this->success([], __('Package created successfully.'));
            }

            return redirect()->route('super_admin.packages.index')->with('success', __('Package created successfully.'));
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Package creation failed: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return $this->error([], getErrorMessage($e, $e->getMessage()));
            }
            
            return redirect()->back()->with('error', getErrorMessage($e, $e->getMessage()));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Return package data as JSON for AJAX requests, otherwise redirect
        if (request()->ajax()) {
            $package = $this->packageService->getById($id);
            return $this->success($package, __('Package data loaded successfully.'));
        }
        
        // Redirect to index page as we use modal for create/edit
        return redirect()->route('super_admin.packages.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PackageRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(PackageRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $package = $this->packageService->getById($id);
            $this->packageService->update($request, $id);
            DB::commit();

            if ($request->ajax()) {
                return $this->success([], __('Package updated successfully.'));
            }

            return redirect()->route('super_admin.packages.index')->with('success', __('Package updated successfully.'));
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax()) {
                return $this->error([], getErrorMessage($e, $e->getMessage()));
            }
            return redirect()->back()->with('error', getErrorMessage($e, $e->getMessage()));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $package = $this->packageService->getById($id);
            $this->packageService->deleteById($id);
            DB::commit();
            $message = __('Package deleted successfully.');
            if (request()->ajax()) {
                return $this->success([], $message);
            }
            return redirect()->route('super_admin.packages.index')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            if (request()->ajax()) {
                return $this->error([], $message);
            }
            return redirect()->back()->with('error', $message);
        }
    }

    public function userPackages(Request $request)
    {
        if ($request->ajax()) {
            return $this->packageService->getUserPackagesData($request, auth()->id());
        }

        $data['title'] = __('User Packages');
        $data['breadcrumb'] = __('Billing Center') . ' / ' . __('User Packages');
        $data['activeUserPackages'] = 'active';
        $data['packages'] = Package::orderBy('id', 'DESC')->get();
        $data['users'] = User::where('role', USER_ROLE_ADMIN)->get();
        return view('auto_posts.super_admin.packages.user', $data);
    }

    public function assignPackage(Request $request)
    {
        try {
            DB::beginTransaction();
            $package = $this->packageService->getById($request->package_id);
            $result = $this->packageService->assignPackage($request, $package);
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }

    public function editUserPackage($id)
    {
        $data['userPackage'] = $this->packageService->getUserPackageById($id);
        return view('auto_posts.super_admin.packages.edit_user_package_form', $data);
    }

    public function updateUserPackage(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $result = $this->packageService->updateUserPackageStatus($id, $request->status);
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }

    public function revokePackage($id)
    {
        try {
            DB::beginTransaction();
            $result = $this->packageService->revokePackage($id);
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }
}