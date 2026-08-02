<?php

namespace App\Http\Controllers\Grammar;

use App\Http\Controllers\Controller;
use App\Models\Grammar\GrammarCategory;
use Illuminate\Http\Request;

class GrammarCategoryController extends Controller
{
    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        GrammarCategory::create([
            'name' => $request->name,
            'description' => $request->description,
            'order' => $request->order ?? 0,
        ]);

        return redirect()->route('grammar.admin.index')->with('success', 'Giai đoạn học đã được thêm thành công!');
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        $category = GrammarCategory::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'order' => $request->order ?? 0,
        ]);

        return redirect()->route('grammar.admin.index')->with('success', 'Giai đoạn học đã được cập nhật thành công!');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy($id)
    {
        $category = GrammarCategory::findOrFail($id);
        $category->delete();

        return redirect()->route('grammar.admin.index')->with('success', 'Giai đoạn học đã được xóa thành công!');
    }
}
