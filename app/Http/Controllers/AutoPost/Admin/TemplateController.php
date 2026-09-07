<?php

namespace App\Http\Controllers\AutoPost\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AutoPost\Admin\TemplateRequest;
use App\Http\Services\Admin\TemplateService;
use App\Http\Services\SubscriptionService;
use App\Models\Category;
use App\Models\Gallery;
use App\Models\Video;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    use ResponseTrait;

    private TemplateService $templateService;

    private SubscriptionService $subscriptionService;

    public function __construct()
    {
        $this->templateService = new TemplateService();
        $this->subscriptionService = new SubscriptionService();
    }

    public function index()
    {
        $data['categories']          = Category::where('status', STATUS_ACTIVE)->get();
        $data['activeTemplates']     = 'active';
        $data['showTemplatesMenu']   = 'active';
        $data['activeTemplateList']  = 'active';

        return view('auto_posts.admin.template.index', $data);
    }

    public function datatable(Request $request)
    {
        return $this->templateService->getAllData($request);
    }

    public function create()
    {
        $data['categories']           = Category::where('status', STATUS_ACTIVE)->get();
        $data['galleries']            = Gallery::latest()->get();
        $data['videos']               = Video::latest()->get();
        $data['title']                = 'Template Create';
        $data['activeTemplates']      = 'active';
        $data['showTemplatesMenu']    = 'active';
        $data['activeTemplateCreate'] = 'active';
        $data['ai_enabled']           = $this->isAiEnabledForUser();
        $data['ai_upgrade_message']   = __('Your current plan does not include AI features. Please upgrade your package.');
        $data['allowedProviders']     = getUserAllowedProviders();
        

        return view('auto_posts.admin.template.create', $data);
    }

    public function store(TemplateRequest $request)
    {
        return $this->templateService->store($request);
    }

    public function edit($id)
    {
        $data = $this->templateService->getEditData((int) $id);
        $data['activeTemplates']    = 'active';
        $data['showTemplatesMenu']  = 'active';
        $data['activeTemplateList'] = 'active';
        $data['ai_enabled']         = $this->isAiEnabledForUser();
        $data['ai_upgrade_message'] = __('Your current plan does not include AI features. Please upgrade your package.');
        $data['allowedProviders']   = getUserAllowedProviders();
        

        return view('auto_posts.admin.template.edit', $data);
    }

    private function isAiEnabledForUser(): bool
    {
        $currentPackage = $this->subscriptionService->getCurrentPlan(auth()->id());

        return $currentPackage
            && $currentPackage->packageable
            && $currentPackage->packageable->ai_enabled;
    }

    public function update(TemplateRequest $request, $id)
    {
        return $this->templateService->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->templateService->deleteById($id);
    }

    public function list(Request $request)
    {
        return response()->json(['templates' => $this->templateService->getTemplateList()]);
    }
}