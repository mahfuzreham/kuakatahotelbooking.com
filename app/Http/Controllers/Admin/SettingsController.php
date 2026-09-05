<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    private array $defaults = [
        'site_name' => 'KuakataStay',
        'support_email' => '',
        'support_phone' => '',
        'currency' => 'BDT',
        'timezone' => 'Asia/Dhaka',
        'check_in_time' => '14:00',
        'check_out_time' => '12:00',
        'booking_cancellation_hours' => '24',
        'minimum_booking_nights' => '1',
        'vendor_registration_enabled' => '1',
        'maintenance_mode' => '0',
        'email_notifications_enabled' => '1',
        'sms_notifications_enabled' => '0',
        'customer_registration_enabled' => '1',
        'default_commission_percent' => '10',
        'meta_title' => 'KuakataStay - Hotel Booking',
        'meta_description' => 'Find and book hotels and resorts in Kuakata, Bangladesh.',
    ];

    public function index()
    {
        $values = [];
        foreach ($this->defaults as $key => $default) {
            $values[$key] = Setting::getValue($key, $default);
        }

        return view('admin.settings.index', compact('values'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'site_name' => ['required', 'string', 'max:100'],
            'support_email' => ['nullable', 'email', 'max:255'],
            'support_phone' => ['nullable', 'string', 'max:30'],
            'currency' => ['required', 'in:BDT,USD,INR,PKR'],
            'timezone' => ['required', 'timezone'],
            'check_in_time' => ['required', 'date_format:H:i'],
            'check_out_time' => ['required', 'date_format:H:i'],
            'booking_cancellation_hours' => ['required', 'integer', 'min:0', 'max:720'],
            'minimum_booking_nights' => ['required', 'integer', 'min:1', 'max:30'],
            'vendor_registration_enabled' => ['nullable', 'boolean'],
            'maintenance_mode' => ['nullable', 'boolean'],
            'email_notifications_enabled' => ['nullable', 'boolean'],
            'sms_notifications_enabled' => ['nullable', 'boolean'],
            'customer_registration_enabled' => ['nullable', 'boolean'],
            'default_commission_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
        ]);

        foreach (['vendor_registration_enabled','maintenance_mode','email_notifications_enabled','sms_notifications_enabled','customer_registration_enabled'] as $key) {
            $data[$key] = $request->boolean($key) ? '1' : '0';
        }

        DB::transaction(function () use ($data) {
            foreach ($data as $key => $value) {
                Setting::putValue($key, $value);
            }
        });

        ActivityLogger::log($request->user()->id, 'settings.updated', null, 'Admin updated application settings', ['keys' => array_keys($data)]);

        return back()->with('success', 'Settings saved successfully.');
    }
}
