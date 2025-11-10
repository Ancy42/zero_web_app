<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MenuRequest;
use App\Models\Menu;
use App\Models\Page;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $activeMenus = Menu::where('is_active', true)->orderBy('order')->get();
        $inActiveMenus = Menu::where('is_active', false)->orderBy('order')->get();

        $pages = Page::where('is_active', true)->get();

        return view('admin.menu.index', compact('activeMenus', 'inActiveMenus', 'pages'));
    }

    public function store(MenuRequest $request)
    {
        $page = null;
        if ($request->page_id) {
            $page = Page::find($request->page_id);
        }
        $maxOrder = Menu::max('order');

        Menu::create([
            'name' => $request->name,
            'title' => $request->name,
            'url' => $request->custom_url ? $request->custom_url : ('/'.$page?->url),
            'order' => $maxOrder + 1,
            'target' => $request->target ?? '_self',
            'is_active' => $request->is_active ? true : false,
            'is_external' => $request->custom_url ? true : false,
        ]);

        return back()->withSuccess('created successfully');
    }

    public function update(Request $request, Menu $menu)
    {
        $menu->update([
            'name' => $request->menu_name ?? $menu->name,
            'title' => $request->menu_title,
            'url' => $menu->is_default ? $menu->url : $request->menu_url,
        ]);

        return back()->withSuccess('updated successfully');
    }

    public function remove(Menu $menu)
    {
        $menu->update([
            'is_active' => false,
        ]);

        return back()->withSuccess('removed successfully');
    }

    public function destroy(Menu $menu)
    {
        if ($menu->is_default) {
            return back()->withError('cannot delete default menu');
        }

        $menu->delete();

        return back()->withSuccess('deleted successfully');
    }

    public function sort(Request $request)
    {
        $sortedData = $request->sorted_data;
        foreach ($sortedData ?? [] as $data) {
            if (! $data['id'] || ! $data['position']) {
                continue;
            }

            $menu = Menu::find($data['id']);
            $menu->update([
                'order' => $data['position'],
            ]);
        }

        return $this->json('updated successfully', [], 200);
    }

    public function drag(Request $request)
    {
        $id = $request->id;
        $menu = Menu::find($id);

        $menu->update([
            'is_active' => $request->status == 'active' ? true : false,
        ]);

        return $this->json('updated successfully', [], 200);
    }
}
