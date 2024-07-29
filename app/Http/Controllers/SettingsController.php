<?php

namespace App\Http\Controllers;

use App\Settings\GeneralSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class SettingsController extends Controller
{
    public function edit(GeneralSettings $settings)
    {
        return view('settings.edit', ['settings' => $settings]);
    }

    public function update(Request $request, GeneralSettings $settings)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'required|string|max:1000',
            'maintenance_mode' => 'boolean',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $settings->site_name = $request->site_name;
        $settings->site_description = $request->site_description;
        $settings->maintenance_mode = $request->has('maintenance_mode');

        if ($request->hasFile('logo')) {
            // Delete the old logo if it exists
            if ($settings->logo) {
                Storage::disk('public')->delete($settings->logo);
            }
            // Store the new logo
            $path = $request->file('logo')->store('logos', 'public');
            $settings->logo = $path;
        }

        $settings->save();

        return redirect()->route('settings.edit')->with('success', 'Settings updated successfully.');
    }
}
