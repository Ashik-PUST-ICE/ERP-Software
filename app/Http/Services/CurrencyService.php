<?php

namespace App\Http\Services;

use App\Models\Currency;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;

class CurrencyService
{
    use ResponseTrait;

    public function getAllData($routePrefix)
    {
        $currencies = Currency::orderBy('id', 'desc')->select('id', 'currency_code', 'current_currency', 'symbol', 'currency_placement');
        return datatables($currencies)
            ->addIndexColumn()
            ->editColumn('currency_code', function ($data) {
                $currencyCode = $data->currency_code;
                if ($data->current_currency == STATUS_ACTIVE) {
                    $currencyCode = $currencyCode . ' <span class="badge bg-success ms-2">' . __('Default') . '</span>';
                }
                return $currencyCode;
            })
            ->editColumn('currency_placement', function ($data) {
                return $data->currency_placement == 'before' ? __('Before Amount') : __('After Amount');
            })
            ->addColumn('action', function ($data) use ($routePrefix) {
                return '<div class="inline-flex">
                            <div class="dropdown options-area">
                                <a class="options-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="openEditModal(\'' . route($routePrefix . '.edit', $data->id) . '\', ' . $data->id . ')">
                                            ' . __('Edit') . '
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="deleteItem(\'' . route($routePrefix . '.delete', $data->id) . '\', \'\')">
                                            ' . __('Delete') . '
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>';
            })
            ->rawColumns(['action', 'currency_code', 'DT_RowIndex'])
            ->make(true);
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $currency = new Currency();
            $currency->currency_code = $request->currency_code;
            $currency->symbol = $request->symbol;
            $currency->currency_placement = $request->currency_placement;
            $currency->save();

            if ($request->current_currency) {
                Currency::where('id', $currency->id)->update(['current_currency' => STATUS_ACTIVE]);
                Currency::where('id', '!=', $currency->id)->update(['current_currency' => STATUS_PENDING]);
            }

            DB::commit();

            $message = getMessage(CREATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $currency = Currency::findOrFail($id);
            $currency->currency_code = $request->currency_code;
            $currency->symbol = $request->symbol;
            $currency->currency_placement = $request->currency_placement;
            $currency->save();
            
            if ($request->current_currency) {
                Currency::where('id', $currency->id)->update(['current_currency' => STATUS_ACTIVE]);
                Currency::where('id', '!=', $currency->id)->update(['current_currency' => STATUS_PENDING]);
            }

            DB::commit();

            $message = getMessage(UPDATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    public function getById($id)
    {
        return Currency::findOrFail($id);
    }

    public function deleteById($id)
    {

        try {
            DB::beginTransaction();
            $currency = Currency::findOrFail($id);
            $currency->delete();
            DB::commit();
            $message = getMessage(DELETED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (\Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }
}
