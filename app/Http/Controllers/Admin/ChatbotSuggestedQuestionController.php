<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatbotSuggestedQuestion;
use Illuminate\Http\Request;

class ChatbotSuggestedQuestionController extends Controller
{
    public function index()
    {
        $questions = ChatbotSuggestedQuestion::orderBy('order')->orderBy('created_at', 'desc')->get();
        return view('admin.chatbot.questions.index', compact('questions'));
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
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        ChatbotSuggestedQuestion::create($data);

        return redirect()->route('admin.chatbot.questions.index')->with('success', 'Thêm câu hỏi gợi ý thành công!');
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
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        $question->update($data);

        return redirect()->route('admin.chatbot.questions.index')->with('success', 'Cập nhật câu hỏi gợi ý thành công!');
    }

    public function destroy(ChatbotSuggestedQuestion $question)
    {
        $question->delete();
        return redirect()->route('admin.chatbot.questions.index')->with('success', 'Xóa câu hỏi gợi ý thành công!');
    }
}
