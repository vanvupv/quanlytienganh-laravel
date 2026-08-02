<?php

namespace App\Http\Controllers\Grammar;

use App\Http\Controllers\Controller;
use App\Models\Grammar\GrammarTopic;
use Illuminate\Http\Request;

class GrammarTopicController extends Controller
{
    /**
     * Store a newly created topic in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:grammar_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        GrammarTopic::create([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'order' => $request->order ?? 0,
        ]);

        return redirect()->route('grammar.admin.index')->with('success', 'Lộ trình đã được thêm thành công!');
    }

    /**
     * Update the specified topic in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required|exists:grammar_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        $topic = GrammarTopic::findOrFail($id);
        $topic->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'order' => $request->order ?? 0,
        ]);

        return redirect()->route('grammar.admin.index')->with('success', 'Lộ trình đã được cập nhật thành công!');
    }

    /**
     * Remove the specified topic from storage.
     */
    public function destroy($id)
    {
        $topic = GrammarTopic::findOrFail($id);
        $topic->delete();

        return redirect()->route('grammar.admin.index')->with('success', 'Lộ trình đã được xóa thành công!');
    }
}
