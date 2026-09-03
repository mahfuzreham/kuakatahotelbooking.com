<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Property;
use App\Models\Refund;
use App\Models\User;
use App\Models\Vendor;

class DashboardController extends Controller
{
    public function admin()
    {
        $stats = [
            'users' => User::count(),
            'vendors' => Vendor::count(),
            'pending_vendors' => Vendor::where('status', 'pending')->count(),
            'properties' => Property::count(),
            'pending_properties' => Property::where('status', 'pending')->count(),
            'active_properties' => Property::where('status', 'active')->count(),
            'bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending_payment')->count(),
            'revenue' => Payment::where('status', 'paid')->sum('amount'),
            'pending_refunds' => Refund::where('status', 'pending_review')->count(),
            'today_bookings' => Booking::whereDate('created_at', today())->count(),
            'today_revenue' => Payment::where('status', 'paid')->whereDate('paid_at', today())->sum('amount'),
        ];

        $recentBookings = Booking::with('property')->latest()->take(8)->get();
        $bookingTrend = Booking::selectRaw('DATE(created_at) as day, COUNT(*) as total')->where('created_at','>=',now()->subDays(6)->startOfDay())->groupBy('day')->orderBy('day')->get();
        $recentPayments = Payment::with('booking.property')->where('status','paid')->latest('paid_at')->take(6)->get();

        return view('dashboard.admin', compact('stats', 'recentBookings', 'bookingTrend', 'recentPayments'));
    }

    public function vendor()
    {
        return view('dashboard.vendor');
    }

    public function hotel()
    {
        return view('dashboard.hotel');
    }
}
