@extends('layouts.app')

@section('content')
<style>
.verify-page{max-width:1280px;margin:0 auto;padding:32px 20px;color:#1f2937}.verify-page *{box-sizing:border-box}
.verify-head{display:flex;justify-content:space-between;align-items:center;gap:20px;margin-bottom:24px}.verify-kicker{margin:0 0 6px;color:#0f766e;font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase}.verify-page h2{margin:0;font-size:28px}.verify-sub{margin:7px 0 0;color:#64748b}
.verify-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:22px}.verify-stat,.verify-panel{background:#fff;border:1px solid #e5e7eb;border-radius:14px;box-shadow:0 4px 16px rgba(15,23,42,.05)}.verify-stat{padding:18px}.verify-stat-label{font-size:13px;color:#64748b}.verify-stat-value{font-size:25px;font-weight:700;margin-top:7px;color:#0f172a}
.verify-panel{overflow:hidden}.verify-panel-head{padding:18px 22px;border-bottom:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center;gap:15px}.verify-panel-title{font-size:18px;font-weight:700}.verify-filter{display:flex;gap:10px;align-items:center}.verify-filter select{min-width:210px;height:42px;padding:0 12px;border:1px solid #cbd5e1;border-radius:8px;background:#fff}
.verify-table-wrap{overflow-x:auto}.verify-table{width:100%;border-collapse:collapse;min-width:850px}.verify-table th{background:#f8fafc;color:#475569;font-size:12px;text-transform:uppercase;letter-spacing:.04em;text-align:left}.verify-table th,.verify-table td{padding:15px 18px;border-bottom:1px solid #e5e7eb}.verify-table tr:last-child td{border-bottom:0}.verify-user{font-weight:600;color:#0f172a}.verify-id{font-size:12px;color:#94a3b8}.verify-type{font-weight:700;font-size:12px;letter-spacing:.04em}.verify-badge{display:inline-flex;padding:5px 10px;border-radius:999px;font-size:12px;font-weight:700}.verify-pending{background:#fef3c7;color:#92400e}.verify-processing{background:#dbeafe;color:#1d4ed8}.verify-approved{background:#dcfce7;color:#15803d}.verify-rejected{background:#fee2e2;color:#b91c1c}.verify-other{background:#e5e7eb;color:#475569}.verify-actions{display:flex;gap:7px;flex-wrap:wrap}.verify-actions button{border:0;border-radius:8px;padding:8px 12px;font-size:13px;font-weight:600;cursor:pointer}.verify-approve{background:#16a34a;color:#fff}.verify-reject{background:#fff;color:#dc2626;border:1px solid #dc2626!important}.verify-empty{text-align:center;color:#94a3b8;padding:34px!important}
.verify-footer{padding:16px 20px;border-top:1px solid #e5e7eb;background:#fff}.verify-page .pagination{margin:0;justify-content:center}
@media(max-width:900px){.verify-stats{grid-template-columns:repeat(2,1fr)}}@media(max-width:560px){.verify-page{padding:20px 12px}.verify-page h2{font-size:23px}.verify-head{align-items:flex-start;flex-direction:column}.verify-filter{width:100%}.verify-filter select{width:100%}.verify-stats{grid-template-columns:1fr 1fr}.verify-panel-head{align-items:flex-start;flex-direction:column}}
</style>

<div class="verify-page">
    <div class="verify-head">
        <div><p class="verify-kicker">Administration</p><h2>KYC & Identity Verification</h2><p class="verify-sub">Review customer identity submissions and approve or reject pending requests.</p></div>
        <a href="{{ route('dashboard.admin') }}" class="btn btn-outline-secondary">← Dashboard</a>
    </div>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

    @php
        $verificationItems = collect($verifications->items());
        $pendingCount = $verificationItems->whereIn('status',['pending','processing'])->count();
        $approvedCount = $verificationItems->where('status','approved')->count();
        $rejectedCount = $verificationItems->where('status','rejected')->count();
    @endphp
    <div class="verify-stats">
        <div class="verify-stat"><div class="verify-stat-label">Showing Pending / Processing</div><div class="verify-stat-value">{{ $pendingCount }}</div></div>
        <div class="verify-stat"><div class="verify-stat-label">Approved</div><div class="verify-stat-value">{{ $approvedCount }}</div></div>
        <div class="verify-stat"><div class="verify-stat-label">Rejected</div><div class="verify-stat-value">{{ $rejectedCount }}</div></div>
        <div class="verify-stat"><div class="verify-stat-label">Current Page</div><div class="verify-stat-value">{{ $verificationItems->count() }}</div></div>
    </div>

    <div class="verify-panel">
        <div class="verify-panel-head">
            <div class="verify-panel-title">Verification Requests</div>
            <form class="verify-filter" method="GET"><select name="status" onchange="this.form.submit()"><option value="">All statuses</option>@foreach(['pending','processing','approved','rejected'] as $status)<option value="{{ $status }}" @selected(request('status')===$status)>{{ ucfirst($status) }}</option>@endforeach</select></form>
        </div>
        <div class="verify-table-wrap">
            <table class="verify-table"><thead><tr><th>ID</th><th>User</th><th>Document Type</th><th>Status</th><th>Submitted</th><th>Action</th></tr></thead><tbody>
            @forelse($verifications as $verification)
                <tr>
                    <td><strong>#{{ $verification->id }}</strong></td>
                    <td><div class="verify-user">{{ $verification->user?->name ?? $verification->user?->email ?? 'User #'.$verification->user_id }}</div><div class="verify-id">{{ $verification->user?->email ?? '' }}</div></td>
                    <td><span class="verify-type">{{ strtoupper($verification->type) }}</span></td>
                    <td>@php $statusClass='verify-other'; if($verification->status==='pending')$statusClass='verify-pending'; elseif($verification->status==='processing')$statusClass='verify-processing'; elseif($verification->status==='approved')$statusClass='verify-approved'; elseif($verification->status==='rejected')$statusClass='verify-rejected'; @endphp<span class="verify-badge {{ $statusClass }}">{{ ucfirst($verification->status) }}</span></td>
                    <td>{{ $verification->created_at?->format('d M Y, h:i A') ?? '—' }}</td>
                    <td>@if(in_array($verification->status,['pending','processing']))<div class="verify-actions"><form method="POST" action="{{ route('admin.verifications.approve',$verification) }}">@csrf<button class="verify-approve">Approve</button></form><form method="POST" action="{{ route('admin.verifications.reject',$verification) }}">@csrf<input type="hidden" name="reason" value="Rejected by administrator"><button class="verify-reject">Reject</button></form></div>@else<span class="text-muted">No action</span>@endif</td>
                </tr>
            @empty
                <tr><td colspan="6" class="verify-empty">No verification records found.</td></tr>
            @endforelse
            </tbody></table>
        </div>
        @if(method_exists($verifications,'links'))<div class="verify-footer">{{ $verifications->links() }}</div>@endif
    </div>
</div>
@endsection
