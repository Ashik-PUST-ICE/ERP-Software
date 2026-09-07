<div class="table-responsive zTable-responsive">
    <table class="display search-datatable primary-table dataTable dtr-inline zTable">
        <thead>
            <tr>
                <th class="keep-show">{{ __("Code") }}</th>
                <th>{{ __("Symbol") }}</th>
                <th>{{ __("Placement") }}</th>
                <th class="keep-show">{{ __("Action") }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($currencies as $currency)
            <tr>
                <td>
                    {{ $currency->currency_code }}
                    @if($currency->current_currency == STATUS_ACTIVE)
                    <span class="badge bg-success ms-2">{{ __('Default') }}</span>
                    @endif
                </td>
                <td>{{ $currency->symbol }}</td>
                <td>{{ $currency->currency_placement == 'before' ? __('Before Amount') : __('After Amount') }}
                </td>
                <td>
                    <div class="inline-flex">
                        <div class="dropdown options-area">
                            <a class="options-btn" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="fa-solid fa-ellipsis"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)"
                                        onclick="openEditModal('{{ route('super_admin.setting.currencies.edit', $currency->id) }}', {{ $currency->id }})">
                                        {{ __('Edit') }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)"
                                        onclick="deleteItem('{{ route('super_admin.setting.currencies.delete', $currency->id) }}')">
                                        {{ __('Delete') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">{{ __('No currencies found') }}</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@include('auto_posts.super_admin.pagination.common-pagination')