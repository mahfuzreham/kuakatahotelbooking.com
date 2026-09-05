@extends('layouts.app')

@section('content')
<style>
.notify-page{max-width:1280px;margin:0 auto;padding:32px 20px;color:#1f2937}.notify-page *{box-sizing:border-box}.notify-head{display:flex;justify-content:space-between;align-items:center;gap:20px;margin-bottom:24px}.notify-kicker{margin:0 0 6px;color:#0f766e;font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase}.notify-page h2{margin:0;font-size:28px}.notify-sub{margin:7px 0 0;color:#64748b}.notify-grid{display:grid;grid-template-columns:minmax(0,1fr) 1.35fr;gap:20px}.notify-panel{background:#fff;border:1px solid #e5e7eb;border-radius:14px;box-shadow:0 4px 16px rgba(15,23,42,.05);overflow:hidden}.notify-panel-head{padding:18px 22px;border-bottom:1px solid #e5e7eb;font-size:18px;font-weight:700}.notify-panel-body{padding:22px}.notify-form{display:grid;grid-template-columns:1fr 1fr;gap:16px}.notify-field label{display:block;font-size:13px;font-weight:600;margin-bottom:7px;color:#475569}.notify-page input,.notify-page select,.notify-page textarea{width:100%;padding:11px 12px;border:1px solid #cbd5e1;border-radius:8px;background:#fff;font-size:14px}.notify-page input,.notify-page select{height:43px}.notify-page textarea{min-height:120px;resize:vertical}.notify-full{grid-column:1/-1}.notify-submit{display:flex;justify-content:flex-end}.notify-submit button{border:0;border-radius:8px;padding:11px 18px;background:#0f766e;color:#fff;font-weight:700;cursor:pointer}.notify-hint{font-size:12px;color:#94a3b8;margin-top:5px}.notify-table-wrap{overflow-x:auto}.notify-table{width:100%;border-collapse:collapse;min-width:800px}.notify-table th{background:#f8fafc;color:#475569;font-size:12px;text-transform:uppercase;letter-spacing:.04em;text-align:left}.notify-table th,.notify-table td{padding:14px 16px;border-bottom:1px solid #e5e7eb}.notify-table tr:last-child td{border-bottom:0}.notify-channel{display:inline-flex;padding:5px 9px;border-radius:999px;background:#eef2ff;color:#4338ca;font-size:12px;font-weight:700}.notify-status{font-size:12px;font-weight:700}.notify-success{color:#15803d}.notify-failed{color:#b91c1c}.notify-other{color:#64748b}.notify-empty{text-align:center;color:#94a3b8;padding:34px!important}.notify-footer{padding:16px 20px;border-top:1px solid #e5e7eb}@media(max-width:950px){.notify-grid{grid-template-columns:1fr}}@media(max-width:600px){.notify-page{padding:20px 12px}.notify-head{align-items:flex-start;flex-direction:column}.notify-page h2{font-size:23px}.notify-form{grid-template-columns:1fr}.notify-full{grid-column:auto}.notify-submit{justify-content:stretch}.notify-submit button{width:100%}}
</style>

<div class="notify-page">
    <div class="notify-head"><div><p class="notify-kicker">Administration</p><h2>Notification Center</h2><p class="notify-sub">Send customer notifications and review delivery history from one place.</p></div><a href="{{ route('dashboard.admin') }}" class="btn btn-outline-secondary">← Dashboard</a></div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

    <div class="notify-grid">
        <div class="notify-panel"><div class="notify-panel-head">Send Notification</div><div class="notify-panel-body"><form method="POST" action="{{ route('admin.notifications.send') }}" class="notify-form">@csrf
            <div class="notify-field"><label>User ID</label><input name="user_id" value="{{ old('user_id') }}" placeholder="Optional"><div class="notify-hint">Use this when sending to a registered user.</div></div>
            <div class="notify-field"><label>Recipient</label><input name="recipient" value="{{ old('recipient') }}" placeholder="Email or phone"></div>
            <div class="notify-field"><label>Channel</label><select name="channel"><option value="email" @selected(old('channel','email')==='email')>Email</option><option value="sms" @selected(old('channel')==='sms')>SMS</option><option value="in_app" @selected(old('channel')==='in_app')>In App</option></select></div>
            <div class="notify-field"><label>Template Code</label><input name="template_code" value="{{ old('template_code') }}" placeholder="Optional active template"></div>
            <div class="notify-field notify-full"><label>Subject</label><input name="subject" value="{{ old('subject') }}" placeholder="Notification subject"></div>
            <div class="notify-field notify-full"><label>Message</label><textarea name="message" placeholder="Write your notification message...">{{ old('message') }}</textarea></div>
            <div class="notify-submit notify-full"><button type="submit">Send Notification</button></div>
        </form></div></div>

        <div class="notify-panel"><div class="notify-panel-head">Notification Logs</div><div class="notify-table-wrap"><table class="notify-table"><thead><tr><th>ID</th><th>Recipient</th><th>Channel</th><th>Template</th><th>Status</th><th>Sent</th></tr></thead><tbody>
        @forelse($logs as $log)<tr><td><strong>#{{ $log->id }}</strong></td><td>{{ $log->recipient ?: '—' }}</td><td><span class="notify-channel">{{ strtoupper(str_replace('_',' ',$log->channel)) }}</span></td><td>{{ $log->template_code ?: '—' }}</td><td>@php $status=$log->status; @endphp<span class="notify-status {{ in_array($status,['sent','delivered','success'])?'notify-success':($status==='failed'?'notify-failed':'notify-other') }}">{{ ucfirst($status) }}</span></td><td>{{ $log->sent_at ? \Carbon\Carbon::parse($log->sent_at)->format('d M Y, h:i A') : '—' }}</td></tr>
        @empty<tr><td colspan="6" class="notify-empty">No notification logs yet.</td></tr>@endforelse
        </tbody></table></div>@if(method_exists($logs,'links'))<div class="notify-footer">{{ $logs->links() }}</div>@endif</div>
    </div>
</div>
@endsection
