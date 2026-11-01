<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::withCount('inventaris')->orderBy('name')->paginate(10);
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
            'description' => ['nullable', 'string'],
        ], [
            'name.required' => 'Nama kategori barang wajib diisi.',
            'name.unique' => 'Nama kategori ini sudah ada.',
        ]);

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', "Kategori {$validated['name']} berhasil ditambahkan!");
    }

    public function update(Request $request, int $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name,' . $category->id],
            'description' => ['nullable', 'string'],
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', "Kategori {$category->name} berhasil diperbarui!");
    }

    public function destroy(int $id)
    {
        $category = Category::findOrFail($id);

        if ($category->inventaris()->exists()) {
            return redirect()->route('categories.index')
                ->with('error', "Kategori {$category->name} tidak dapat dihapus karena masih menampung data barang inventaris.");
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', "Kategori {$category->name} berhasil dihapus.");
    }
}
