<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $messages = \App\Models\ContactMessage::latest()->paginate(10);

        return view('admin.contact-messages.index', compact('messages'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $message = \App\Models\ContactMessage::findOrFail($id);

        if ($message->status == 'unread') {
            $message->update(['status' => 'read']);
        }

        return view('admin.contact-messages.show', compact('message'));
    }

    /**
     * Reply to the contact message.
     */
    public function reply(\App\Http\Requests\Generated\ContactReplyRequest $request, string $id)
    {
        $message = \App\Models\ContactMessage::findOrFail($id);
        $validated = $request->validated();

        try {
            // Send email
            \Illuminate\Support\Facades\Mail::to($message->email)->send(new \App\Mail\ContactReply($message, $validated['reply_message']));

            // Update message status
            $message->update([
                'reply_message' => $validated['reply_message'],
                'replied_at' => now(),
                'status' => 'replied',
            ]);

            return redirect()->route('admin.contact-messages.show', $message)
                ->with('success', 'Email phản hồi đã được gửi thành công.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi gửi email: '.$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $message = \App\Models\ContactMessage::findOrFail($id);
        $message->delete();

        return redirect()->route('admin.contact-messages.index')
            ->with('success', 'Xóa tin nhắn thành công.');
    }
}
