<?php

namespace App\Http\Controllers\AutoPost\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;

class SettingController extends Controller
{
    use ResponseTrait;

    public function colorSettings()
    {
        $data['title'] = __("Color Settings");
        $data['showManageApplicationSetting'] = 'show';
        $data['activeColorSettings'] = 'active';
        return view('auto_posts.admin.settings.general_settings.color-settings', $data);
    }

    public function timezoneSettings()
    {
        $data['title'] = __("Timezone Settings");
        $data['activeTimezoneSettings'] = 'active';
        $data['timezones'] = getTimeZone();
        $data['app_timezone'] = getAppTimeZone();
        return view('auto_posts.admin.settings.timezone-settings', $data);
    }

    public function timezoneSettingsUpdate(Request $request)
    {
        $request->validate([
            'app_timezone' => ['required', 'string', \Illuminate\Validation\Rule::in(getTimeZone())],
        ]);

        $option = Setting::firstOrCreate(['option_key' => 'app_timezone']);
        $option->option_value = $request->app_timezone;
        $option->save();

        return $this->success([], getMessage(UPDATED_SUCCESSFULLY));
    }

    public function applicationSettingUpdate(Request $request)
    {
        $inputs = Arr::except($request->all(), ['_token']);

        foreach ($inputs as $key => $value) {
            $option = Setting::firstOrCreate(['option_key' => $key]);
            $option->option_value = $value;
            $option->save();
        }

        // Update dynamic color CSS if custom colors are set
        if ($request->has('app_color_design_type') && $request->app_color_design_type == CUSTOM_COLOR) {
            $this->updateDynamicColorFile($request);
        }

        return $this->success([], getMessage(UPDATED_SUCCESSFULLY));
    }

    private function updateDynamicColorFile($request)
    {
        $primaryColor = getOption('app_primary_color', '#FF4F02');
        $hoverColor = getOption('app_hover_color', '#d93900');
        $textColor = getOption('app_text_color', '#1b1c17');
        $textSecondaryColor = getOption('app_text_secondary_color', '#707070');
        $sidebarBgColor = getOption('app_sidebar_bg_color', '#1b1c17');
        $sidebarTextColor = getOption('app_sidebar_text_color', '#f6f5f5');

        $css = ":root {
            --primary-color: {$primaryColor};
            --black-color: {$textColor};
            --text-black: {$textColor};
            --para-color: {$textSecondaryColor};
            --colorOne: {$textSecondaryColor};
            --bColor: {$textSecondaryColor};
            --hover-color: {$hoverColor};
            --black: {$textColor};
            --sidebar-text-color: {$sidebarTextColor};
            --sidebar-bg-color: {$sidebarBgColor};
            --sidebar-hover-text-color: {$sidebarTextColor};
        }";

        $filePath = resource_path('views/auto_posts/admin/layouts/dynamic-color.blade.php');
        file_put_contents($filePath, '<style>' . $css . '</style>');
    }
}
