<?php

namespace App\Http\Controllers\Admin;

use App\Models\HomeTheme;
use App\Models\ThemeColor;
use App\Models\OfferBanner;
use Illuminate\Http\Request;
use App\Models\GeneraleSetting;
use App\Http\Controllers\Controller;
use App\Repositories\ThemeColorRepository;
use App\Repositories\OfferBannerRepository;
use App\Repositories\GeneraleSettingRepository;

class ThemeColorController extends Controller
{
    public function index()
    {
        $themeColors = ThemeColorRepository::getAll();

        $generaleSetting = GeneraleSetting::first();

        $currentTheme = ThemeColorRepository::query()->where('is_default', true)->first()->toArray() ?? null;
        $homeThemes = HomeTheme::orderBy('id', 'asc')->get();

        return view('admin.theme-color', compact('themeColors', 'currentTheme', 'homeThemes'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'selected_id' => 'required|exists:theme_colors,id',
        ]);


        ThemeColorRepository::DefaultColorUpdate($request);

        return back()->with('success', __('Theme color updated successfully'));
    }

    public function change(Request $request)
    {
        if (! $request->generated_color_variants) {
            return back()->with('error', __('Please generated color variants'));
        }

        // if (app()->environment('local')) {
        //     return back()->with('demoMode', __('Sorry! You can not change color in demo mode'));
        // }

        ThemeColorRepository::updateColorPalette($request);

        return back()->with('success', __('Theme color updated successfully'));
    }

    public function themeStatus(HomeTheme $homeTheme)
    {
        if ($homeTheme->is_active) {
            return back()->with('success', 'Theme status updated');
        }
        HomeTheme::where('is_active', true)->update(['is_active' => false]);
        ThemeColor::query()->update(['is_default' => false]);
        $homeTheme->update(['is_active' => true]);
        $themeColor = ThemeColor::where('theme_name', $homeTheme->theme_name)->first();
        $themeColor->update(['is_default' => true]);

        GeneraleSettingRepository::updateOrCreateThemeColor($themeColor->primary, $themeColor->secondary);
        ThemeColorRepository::changeStyleCSS($themeColor->primary, $themeColor->secondary);
        
        return back()->with('success', 'Theme status updated successfully');
    }

    public function offerBannerIndex(HomeTheme $homeTheme)
    {
        $offerBanners = OfferBanner::where('home_theme_id', $homeTheme->id)->orderBy('id', 'asc')->get();
        return view('admin.offerBanner.index', compact('offerBanners'));
    }
    public function offerBannerEdit(OfferBanner $offerBanner)
    {
        return view('admin.offerBanner.edit', compact('offerBanner'));
    }

    public function offerBannerUpdate(OfferBanner $offerBanner, Request $request)
    {
        $request->validate([
            'thumbnail' => 'nullable|string|max:255',
            'link' => 'nullable|url|max:255',
        ]);

        OfferBannerRepository::updateByRequest($offerBanner, $request);
        return back()->with('success', 'Offer Banner updated successfully');
    }
}
