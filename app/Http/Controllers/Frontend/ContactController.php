<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\ContactNotification;
use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('frontend.contact.index');
    }

    public function send(\App\Http\Requests\Generated\ContactFormRequest $request)
    {
        $data = $request->validated();
        if (Auth::check()) {
            $data['user_id'] = Auth::id();
        }

        $contactMessage = ContactMessage::create($data);

        // Send email notification to Admin and Staff
        try {
            $recipients = User::whereIn('role', [User::ROLE_ADMIN, User::ROLE_STAFF])->pluck('email');

            if ($recipients->isNotEmpty()) {
                Mail::to($recipients)->send(new ContactNotification($contactMessage));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send contact notification: '.$e->getMessage());
        }

        if ($request->ajax()) {
            return response()->json(['message' => __('messages.contact_success')]);
        }

        return redirect()->route('contact.index')
            ->with('success', __('messages.contact_success'));
    }
}
