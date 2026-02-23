<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    protected $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    /**
     * Display a listing of the settings.
     */
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update the specified settings in storage.
     */
    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method']);

        foreach ($data as $key => $value) {
            $setting = Setting::where('key', $key)->first();

            if (!$setting)
                continue;

            // Handle file uploads
            if ($setting->type === 'image' && $request->hasFile($key)) {
                // Delete old image if exists
                if ($setting->value) {
                    Storage::disk('public')->delete($setting->value);
                }

                $path = $request->file($key)->store('settings', 'public');
                $value = $path;
            }

            // Handle boolean values (if not present in request, set to false)
            // But here we only iterate over present data. 
            // Better to handle checkbox toggles specifically if they are missing.

            $setting->update(['value' => $value]);
        }

        // Handle boolean settings that might be missing from the request (unchecked checkboxes)
        $group = $request->input('settings_group');
        if ($group) {
            $boolSettings = Setting::where('group', $group)->where('type', 'boolean')->get();
            foreach ($boolSettings as $setting) {
                if (!$request->has($setting->key)) {
                    $setting->update(['value' => '0']);
                }
                else {
                    $setting->update(['value' => '1']);
                }
            }
        }

        $this->settingsService->clearCache();

        return back()->with('success', 'Settings updated successfully.');
    }
}
