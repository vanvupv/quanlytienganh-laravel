<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;

class AchievementAdminController extends Controller
{
    public function index()
    {
        $achievements = Achievement::all();
        return view('achievements.admin.index', compact('achievements'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'required|string|max:255',
            'category' => 'required|string|in:vocab,grammar,review,streak',
            'condition_type' => 'required|string|in:vocab_studied,vocab_mastered,grammar_lessons_done,reviews_done,retention_rate,streak_days',
            'target_value' => 'required|integer|min:1',
        ]);

        Achievement::create($data);

        return redirect()->back()->with('success', 'Thêm mới huy hiệu thành tựu thành công!');
    }

    public function update(Request $request, $id)
    {
        $ach = Achievement::findOrFail($id);
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'required|string|max:255',
            'category' => 'required|string|in:vocab,grammar,review,streak',
            'condition_type' => 'required|string|in:vocab_studied,vocab_mastered,grammar_lessons_done,reviews_done,retention_rate,streak_days',
            'target_value' => 'required|integer|min:1',
        ]);

        $ach->update($data);

        return redirect()->back()->with('success', 'Cập nhật huy hiệu thành tựu thành công!');
    }

    public function destroy($id)
    {
        $ach = Achievement::findOrFail($id);
        // Detach from users first
        $ach->users()->detach();
        $ach->delete();

        return redirect()->back()->with('success', 'Xóa huy hiệu thành tựu thành công!');
    }
}
