<div class="table-responsive zTable-responsive">
    <table class="table zTable">
        <thead>
            <tr>
                <th class="min-w-160">
                    <div>{{ __('Key') }}</div>
                </th>
                <th class="min-w-160">
                    <div>{{ __('Value') }}</div>
                </th>
                <th class="text-center w-28">
                    <div>{{ __('Action') }}</div>
                </th>
            </tr>
        </thead>
        <tbody id="append">
            @forelse ($translators as $key => $value)
            <tr>
                <td data-label="Key">
                    <textarea type="text" class="key form-control" readonly required>{!! $key !!}</textarea>
                </td>
                <td data-label="Value">
                    <input type="hidden" value="0" class="is_new">
                    <textarea type="text" class="val form-control" required>{!! $value !!}</textarea>
                </td>
                <td data-label="Action" class="text-end col-1">
                    <button type="button" class="primary-btn updateLangItem">{{ __('Update') }}</button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center">{{__('No Data Found')}}</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>


@include('auto_posts.super_admin.pagination.common-pagination')