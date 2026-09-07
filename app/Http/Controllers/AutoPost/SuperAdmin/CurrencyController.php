<?php

namespace App\Http\Controllers\AutoPost\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\CurrencyRequest;
use App\Http\Services\CurrencyService;
use App\Models\Currency;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    use ResponseTrait;

    private $currencyService;

    public function __construct()
    {
        $this->currencyService = new CurrencyService;
    }

    public function index(Request $request)
    {
        $perPage = 7;
        $page = $request->get('page', 1);
        $currencies = Currency::orderBy('id', 'desc')->get();
        $total = $currencies->count();
        $currencies = $currencies->slice(($page - 1) * $perPage, $perPage);
        
        $data['currencies'] = $currencies;
        $data['title'] = __('Currency Setting');
        $data['showManageApplicationSetting'] = 'show';
        $data['activeApplicationSetting'] = 'active';
        $data['subCurrencySettingActiveClass'] = 'active';
        $data['total'] = $total;
        $data['perPage'] = $perPage;
        $data['page'] = $page;

        if ($request->ajax()) {
            return view('auto_posts.super_admin.setting.currencies.partials.currencies_table', $data)->render();
        }
        
        return view('auto_posts.super_admin.setting.currencies.index', $data);
    }

    public function edit($id)
    {
        $currency = Currency::findOrFail($id);
        return $this->success([
            'currency' => $currency,
        ], 'Currency data retrieved successfully');
    }


    public function store(CurrencyRequest $request)
    {
        return $this->currencyService->store($request);
    }

    public function update(CurrencyRequest $request, $id)
    {
        return $this->currencyService->update($request, $id);
    }

    public function delete($id)
    {
        return $this->currencyService->deleteById($id);
    }
}