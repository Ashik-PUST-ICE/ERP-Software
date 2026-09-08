<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Models\Garments\GarmentPaymentGateway;
use Illuminate\Http\Request;

class GarmentPaymentGatewayController extends Controller
{
    public function index()
    {
        return view('admin.garments.payment-gateways.index', [
            'title' => __('Garments Payment Gateways'),
            'gateways' => GarmentPaymentGateway::with('currencies')->orderBy('title')->get(),
            'activeGarments' => 'active',
            'activeGarmentPaymentGateways' => 'active',
            'showGarmentsMenu' => 'show',
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id' => ['nullable', 'integer'],
            'title' => ['required', 'string', 'max:120'],
            'slug' => ['required', 'string', 'max:60', 'regex:/^[a-z0-9_-]+$/'],
            'status' => ['required', 'boolean'],
            'mode' => ['nullable', 'integer'],
            'url' => ['nullable', 'string', 'max:255'],
            'key' => ['nullable', 'string'],
            'secret' => ['nullable', 'string'],
            'currencies' => ['required', 'array', 'min:1'],
            'currencies.*.currency' => ['required', 'string', 'max:8'],
            'currencies.*.conversion_rate' => ['required', 'numeric', 'gt:0'],
        ]);

        $gatewayData = collect($data)->except(['id', 'currencies'])->all();
        $gateway = !empty($data['id'])
            ? GarmentPaymentGateway::findOrFail($data['id'])
            : GarmentPaymentGateway::updateOrCreate(
                ['tenant_id' => auth()->user()->tenant_id, 'slug' => $data['slug']],
                array_merge($gatewayData, ['tenant_id' => auth()->user()->tenant_id])
            );

        $gateway->fill($gatewayData);
        $gateway->save();
        $gateway->currencies()->delete();
        $gateway->currencies()->createMany(array_map(
            fn ($currency) => [
                'tenant_id' => $gateway->tenant_id,
                'currency' => strtoupper($currency['currency']),
                'conversion_rate' => $currency['conversion_rate'],
            ],
            $data['currencies']
        ));

        return back()->with('success', __('Garments payment gateway saved successfully.'));
    }

    public function destroy($id)
    {
        GarmentPaymentGateway::findOrFail($id)->delete();

        return back()->with('success', __('Garments payment gateway deleted successfully.'));
    }
}
