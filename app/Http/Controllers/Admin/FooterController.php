<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Footer;
use App\Models\FooterItem;
use App\Models\Menu;
use App\Models\Page;
use Illuminate\Http\Request;

class FooterController extends Controller
{
    public function index()
    {
        $footers = Footer::with('items')->OrderBy('order')->get();

        $menus = Menu::OrderBy('order')->get();
        $pages = Page::where('is_active', true)->get();
        $disableItems = FooterItem::where('is_active', false)->get();

        return view('admin.footer.index', compact('footers', 'menus', 'pages', 'disableItems'));
    }

    public function update(Request $request, Footer $footer)
    {
        $request->validate([
            'title' => 'required',
        ]);

        $footer->update([
            'title' => $request->title,
        ]);

        return back()->withSuccess('updated successfully');
    }

    public function updateItem(Request $request, FooterItem $footerItem)
    {
        $request->validate([
            'title' => 'required',
        ]);

        $footerItem->update([
            'title' => $request->title,
            'url' => $request->url ?? $footerItem->url,
        ]);

        return back()->withSuccess('updated successfully');
    }

    public function sectionSort(Request $request)
    {
        foreach ($request->sorted_data ?? [] as $item) {
            $footer = Footer::find($item['id']);
            $footer->update([
                'order' => $item['position'],
            ]);
        }

        return $this->json('section sorted successfully', [], 200);
    }

    public function addedNew(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'type' => 'required',
            'position' => 'required',
            'section_id' => 'required',
        ]);

        if ($request->type == 'menu') {
            $menu = Menu::find($request->id);
            FooterItem::create([
                'footer_id' => $request->section_id,
                'type' => 'link',
                'title' => $menu->name,
                'url' => '/'.$menu->url,
                'order' => $request->position,
                'target' => $menu->target,
                'is_active' => true,
                'is_default' => false,
            ]);
        } elseif ($request->type == 'page') {
            $page = Page::find($request->id);
            FooterItem::create([
                'footer_id' => $request->section_id,
                'type' => 'link',
                'title' => $page->title,
                'url' => '/'.$page->url,
                'order' => $request->position,
                'target' => '_self',
                'is_active' => true,
                'is_default' => false,
            ]);
        } else {
            $footerItem = FooterItem::find($request->id);
            $footerItem->update([
                'footer_id' => $request->section_id,
                'is_active' => true,
                'order' => $request->position,
            ]);
        }

        return $this->json('added successfully', [], 200);
    }

    public function itemSort(Request $request)
    {
        foreach ($request->sorted_data ?? [] as $item) {

            if (! $item['id'] || ! $item['position'] || ! $item['section_id']) {
                continue;
            }

            $footerItem = FooterItem::find($item['id']);
            $footerItem->update([
                'order' => $item['position'],
                'footer_id' => $item['section_id'],
            ]);
        }

        return $this->json('section sorted successfully', [], 200);
    }

    public function disabled(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        FooterItem::find($request->id)?->update([
            'is_active' => false,
        ]);

        return $this->json('disabled successfully', [], 200);
    }

    public function destroy(FooterItem $footerItem)
    {
        if ($footerItem->is_default) {
            $footerItem->update([
                'is_active' => false,
            ]);

            return back()->withSuccess('disabled successfully');
        }

        $footerItem->delete();

        return back()->withSuccess('deleted successfully');
    }
}
