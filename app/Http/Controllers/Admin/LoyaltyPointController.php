<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class LoyaltyPointController extends Controller
{
    public function index()
    {
        $points = \App\Models\LoyaltyPoint::with(['user', 'order'])
            ->latest()
            ->paginate(15);

        return view('admin.loyalty-points.index', compact('points'));
    }
}
