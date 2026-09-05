@extends('layouts.app')

@section('content')
<style>
.settings-shell{min-height:100vh;background:#f5f7fb;display:flex;font-family:Arial,sans-serif}.settings-sidebar{width:255px;background:#102a43;color:#fff;padding:28px 18px}.settings-sidebar h2{margin:0 0 26px}.settings-sidebar a{display:block;color:#d9e2ec;text-decoration:none;padding:11px 12px;border-radius:8px;margin:3px 0}.settings-sidebar a:hover,.settings-sidebar a.active{background:#243b53;color:#fff}.settings-main{flex:1;padding:38px;min-width:0}.settings-wrap{max-width:1100px;margin:0 auto}.settings-kicker{color:#627d98;font-weight:700;font-size:12px;letter-spacing:1px}.settings-main h1{margin:5px 0;color:#102a43}.settings-subtitle{color:#627d98;margin:0 0 24px}.settings-card{background:#fff;border-radius:14px;padding:24px;margin-bottom:20px;box-shadow:0 2px 12px rgba(0,0,0,.05)}.settings-card h2{font-size:19px;color:#102a43;margin:0 0 5px}.settings-card p.help{color:#7b8794;font-size:13px;margin:0 0 20px}.settings-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px}.settings-field label{display:block;font-size:13px;font-weight:700;color:#334e68;margin-bottom:7px}.settings-field input,.settings-field select,.settings-field textarea{width:100%;box-sizing:border-box;border:1px solid #cbd5e1;border-radius:9px;padding:11px 12px;background:#fff;font:inherit}.settings-field textarea{min-height:90px;resize:vertical}.settings-toggle{display:flex;align-items:center;gap:10px;padding:12px;border:1px solid #e5e7eb;border-radius:9px}.settings-toggle input{width:18px;height:18px}.settings-actions{position:sticky;bottom:15px;display:flex;justify-content:flex-end;padding:15px 0}.settings-save{background:#176b87;color:#fff;border:0;border-radius:9px;padding:12px 22px;font-weight:700;cursor:pointer}.settings-alert{padding:13px 16px;border-radius:9px;background:#dcfce7;color:#166534;margin-bottom:20px}.settings-error{padding:13px 16px;border-radius:9px;background:#fee2e2;color:#991b1b;margin-bottom:20px}.settings-error ul{margin:6px 0 0 18px}@media(max-width:800px){.settings-shell{display:block}.settings-sidebar{width:auto;display:flex;gap:5px;overflow:auto;padding:12px}.settings-sidebar h2{display:none}.settings-sidebar a{white-space:nowrap}.settings-main{padding:20px}.settings-grid{grid-template-columns:1fr}}
</style>

<div class="settings-shell">
    <aside class="settings-sidebar">
        <h2>KuakataStay</h2>
        <a href="{{ route('dashboard.admin') }}">Overview</a>
        <a href="{{ route('admin.customers.index') }}">Customers</a>
        <a href="{{ route('admin.users.index') }}">Users & Roles</a>
        <a href="{{ route('admin.vendors.index') }}">Vendors</a>
        <a href="{{ route('admin.properties.index') }}">Properties</a>
        <a href="{{ route('admin.bookings.index') }}">Bookings</a>
        <a href="{{ route('admin.finance.index') }}">Finance</a>
        <a href="{{ route('admin.refunds.index') }}">Refunds</a>
        <a href="{{ route('admin.verifications.index') }}">KYC / Verification</a>
        <a href="{{ route('admin.notifications.index') }}">Notifications</a>
        <a href="{{ route('admin.audit.index') }}">Audit Logs</a>
        <a class="active" href="{{ route('admin.settings.index') }}">Settings</a>
    </aside>

    <main class="settings-main">
        <div class="settings-wrap">
            <p class="settings-kicker">ADMIN CONTROL CENTER</p>
            <h1>Settings</h1>
            <p class="settings-subtitle">Manage website, booking, notification and SEO behaviour from one place.</p>

            @if(session('success'))<div class="settings-alert">{{ session('success') }}</div>@endif
            @if($errors->any())<div class="settings-error"><strong>Please fix the following:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf

                <section class="settings-card">
                    <h2>General Settings</h2><p class="help">Basic identity and contact information shown across the platform.</p>
                    <div class="settings-grid">
                        <div class="settings-field"><label>Site Name</label><input name="site_name" value="{{ old('site_name',$values['site_name']) }}" required></div>
                        <div class="settings-field"><label>Currency</label><select name="currency"><option value="BDT" @selected(old('currency',$values['currency'])==='BDT')>BDT — ৳</option><option value="USD" @selected(old('currency',$values['currency'])==='USD')>USD — $</option><option value="INR" @selected(old('currency',$values['currency'])==='INR')>INR — ₹</option><option value="PKR" @selected(old('currency',$values['currency'])==='PKR')>PKR — ₨</option></select></div>
                        <div class="settings-field"><label>Support Email</label><input type="email" name="support_email" value="{{ old('support_email',$values['support_email']) }}"></div>
                        <div class="settings-field"><label>Support Phone</label><input name="support_phone" value="{{ old('support_phone',$values['support_phone']) }}"></div>
                        <div class="settings-field"><label>Timezone</label><select name="timezone"><option value="Asia/Dhaka" @selected(old('timezone',$values['timezone'])==='Asia/Dhaka')>Asia/Dhaka</option><option value="Asia/Kolkata" @selected(old('timezone',$values['timezone'])==='Asia/Kolkata')>Asia/Kolkata</option><option value="Asia/Karachi" @selected(old('timezone',$values['timezone'])==='Asia/Karachi')>Asia/Karachi</option><option value="UTC" @selected(old('timezone',$values['timezone'])==='UTC')>UTC</option></select></div>
                    </div>
                </section>

                <section class="settings-card">
                    <h2>Booking Settings</h2><p class="help">Control standard stay times and cancellation rules.</p>
                    <div class="settings-grid">
                        <div class="settings-field"><label>Check-in Time</label><input type="time" name="check_in_time" value="{{ old('check_in_time',$values['check_in_time']) }}" required></div>
                        <div class="settings-field"><label>Check-out Time</label><input type="time" name="check_out_time" value="{{ old('check_out_time',$values['check_out_time']) }}" required></div>
                        <div class="settings-field"><label>Cancellation Window (hours)</label><input type="number" min="0" max="720" name="booking_cancellation_hours" value="{{ old('booking_cancellation_hours',$values['booking_cancellation_hours']) }}" required></div>
                        <div class="settings-field"><label>Minimum Booking Nights</label><input type="number" min="1" max="30" name="minimum_booking_nights" value="{{ old('minimum_booking_nights',$values['minimum_booking_nights']) }}" required></div>
                        <div class="settings-field"><label>Default Commission (%)</label><input type="number" step="0.01" min="0" max="100" name="default_commission_percent" value="{{ old('default_commission_percent',$values['default_commission_percent']) }}" required></div>
                    </div>
                </section>

                <section class="settings-card">
                    <h2>Registration & Notifications</h2><p class="help">Enable or disable platform-level registration and notification channels.</p>
                    <div class="settings-grid">
                        <label class="settings-toggle"><input type="checkbox" name="customer_registration_enabled" value="1" @checked(old('customer_registration_enabled',$values['customer_registration_enabled']))><span>Customer registration enabled</span></label>
                        <label class="settings-toggle"><input type="checkbox" name="vendor_registration_enabled" value="1" @checked(old('vendor_registration_enabled',$values['vendor_registration_enabled']))><span>Vendor registration enabled</span></label>
                        <label class="settings-toggle"><input type="checkbox" name="email_notifications_enabled" value="1" @checked(old('email_notifications_enabled',$values['email_notifications_enabled']))><span>Email notifications enabled</span></label>
                        <label class="settings-toggle"><input type="checkbox" name="sms_notifications_enabled" value="1" @checked(old('sms_notifications_enabled',$values['sms_notifications_enabled']))><span>SMS notifications enabled</span></label>
                        <label class="settings-toggle"><input type="checkbox" name="maintenance_mode" value="1" @checked(old('maintenance_mode',$values['maintenance_mode']))><span>Maintenance mode</span></label>
                    </div>
                </section>

                <section class="settings-card">
                    <h2>SEO Settings</h2><p class="help">Default metadata for search engines and social previews.</p>
                    <div class="settings-grid">
                        <div class="settings-field"><label>Meta Title</label><input name="meta_title" value="{{ old('meta_title',$values['meta_title']) }}"></div>
                        <div class="settings-field"><label>Meta Description</label><textarea name="meta_description">{{ old('meta_description',$values['meta_description']) }}</textarea></div>
                    </div>
                </section>

                <div class="settings-actions"><button class="settings-save" type="submit">Save Settings</button></div>
            </form>
        </div>
    </main>
</div>
@endsection
