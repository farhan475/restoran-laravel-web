<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::latest()->paginate(10);
        return view('menu.index', compact('menus'));
    }

    public function create()
    {
        return view('menu.form', ['menu' => new Menu]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'harga' => ['required', 'integer', 'min:0'],
            'aktif' => ['required', 'boolean'],
        ]);
        Menu::create($data);
        return redirect()->route('menu.index')->with('ok', 'Menu dibuat');
    }

    public function edit(Menu $menu)
    {
        return view('menu.form', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'harga' => ['required', 'integer', 'min:0'],
            'aktif' => ['required', 'boolean'],
        ]);
        $menu->update($data);
        return redirect()->route('menu.index')->with('ok', 'Menu diperbarui');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return back()->with('ok', 'Menu dihapus');
    }
}
