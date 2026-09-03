@extends('layouts.app')
@section('content')
<div class="container py-4">
<div class="d-flex justify-content-between align-items-center mb-3"><h2>KYC & Identity Verification</h2><a href="{{ route('dashboard.admin') }}" class="btn btn-outline-secondary">Dashboard</a></div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<form class="mb-3"><select name="status" onchange="this.form.submit()" class="form-select" style="max-width:230px"><option value="">All statuses</option>@foreach(['pending','processing','approved','rejected'] as $status)<option value="{{ $status }}" @selected(request('status')===$status)>{{ ucfirst($status) }}</option>@endforeach</select></form>
<div class="table-responsive"><table class="table table-hover align-middle"><thead><tr><th>ID</th><th>User</th><th>Type</th><th>Status</th><th>Submitted</th><th>Action</th></tr></thead><tbody>
@forelse($verifications as $verification)<tr><td>#{{ $verification->id }}</td><td>{{ $verification->user?->email ?? 'User #'.$verification->user_id }}</td><td>{{ strtoupper($verification->type) }}</td><td><span class="badge bg-secondary">{{ $verification->status }}</span></td><td>{{ $verification->created_at?->format('d M Y H:i') }}</td><td>@if(in_array($verification->status,['pending','processing']))<form method="POST" action="{{ route('admin.verifications.approve',$verification) }}" class="d-inline">@csrf<button class="btn btn-sm btn-success">Approve</button></form><form method="POST" action="{{ route('admin.verifications.reject',$verification) }}" class="d-inline">@csrf<input type="hidden" name="reason" value="Rejected by administrator"><button class="btn btn-sm btn-danger">Reject</button></form>@endif</td></tr>
@empty<tr><td colspan="6" class="text-center">No verification records found.</td></tr>@endforelse
</tbody></table></div>{{ $verifications->links() }}</div>
@endsection