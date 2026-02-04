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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $message = \App\Models\ContactMessage::findOrFail($id);
        $message->delete();
        
        return redirect()->route('admin.contact-messages.index')
            ->with('success', 'Message deleted successfully.');
    }
}
