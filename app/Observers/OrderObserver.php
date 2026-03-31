<?php

namespace App\Observers;

use App\Models\Order;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        if ($order->isDirty('payment_status') && $order->payment_status === 'paid') {
            // Notify admins (database notification)
            $admins = \App\Models\User::getAdmins();
            \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\PaymentSuccessNotification($order));

            // Send payment success email to customer
            try {
                $order->loadMissing('items.product');
                $email = $order->email ?? ($order->user ? $order->user->email : null);
                if ($email) {
                    \Illuminate\Support\Facades\Mail::to($email)->send(new \App\Mail\PaymentSuccessMail($order));
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to send payment success email for order #'.$order->id.': '.$e->getMessage());
            }
        }
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
