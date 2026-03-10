<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatbotSuggestedQuestion;
use Illuminate\Http\Request;

class ChatbotSuggestedQuestionController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.settings.chatbot', ['tab' => 'questions']);
    }

    public function create()
    {
        return view('admin.chatbot.questions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'nullable|string',
            'order' => 'required|integer',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        ChatbotSuggestedQuestion::create($data);
        \Illuminate\Support\Facades\Cache::forget('chatbot_suggested_questions');

        return redirect()->route('admin.settings.chatbot', ['tab' => 'questions'])->with('success', 'Thêm câu hỏi gợi ý thành công!');
    }

    public function edit(ChatbotSuggestedQuestion $question)
    {
        return view('admin.chatbot.questions.edit', compact('question'));
    }

    public function update(Request $request, ChatbotSuggestedQuestion $question)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'nullable|string',
            'order' => 'required|integer',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        $question->update($data);
        \Illuminate\Support\Facades\Cache::forget('chatbot_suggested_questions');

        return redirect()->route('admin.settings.chatbot', ['tab' => 'questions'])->with('success', 'Cập nhật câu hỏi gợi ý thành công!');
    }

    public function destroy(ChatbotSuggestedQuestion $question)
    {
        $question->delete();
        \Illuminate\Support\Facades\Cache::forget('chatbot_suggested_questions');

        return redirect()->route('admin.settings.chatbot', ['tab' => 'questions'])->with('success', 'Xóa câu hỏi gợi ý thành công!');
    }
}
