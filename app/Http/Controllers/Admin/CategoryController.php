<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /** Daftar seluruh kategori campaign. */
    public function index(): View
    {
        $categories = Category::withCount('campaigns')->orderBy('name')->get();

        return view('admin.categories.index', [
            'categories' => $categories,
        ]);
    }

    /** Simpan kategori baru. */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:100', 'unique:categories,name'],
            'description' => ['nullable', 'string', 'max:255'],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique'   => 'Kategori dengan nama ini sudah ada.',
        ]);

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', "Kategori \"{$validated['name']}\" berhasil ditambahkan.");
    }

    /** Update kategori. */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:100', Rule::unique('categories', 'name')->ignore($category->id_category, 'id_category')],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', "Kategori \"{$category->name}\" berhasil diperbarui.");
    }

    /** Hapus kategori jika belum ada campaign yang menggunakannya. */
    public function destroy(Category $category): RedirectResponse
    {
        if ($category->campaigns()->exists()) {
            return back()->with('error', "Kategori \"{$category->name}\" tidak dapat dihapus karena masih digunakan oleh campaign.");
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', "Kategori \"{$category->name}\" berhasil dihapus.");
    }
}
