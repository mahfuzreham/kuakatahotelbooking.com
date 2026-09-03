@extends('layouts.app')
@section('content')
<div class="container py-4"><h2>Notification Center</h2>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
<div class="card p-3 mb-4"><h4>Send Notification</h4>
<form method="POST" action="{{ route('admin.notifications.send') }}" class="row g-3">@csrf
<div class="col-md-3"><label>User ID</label><input class="form-control" name="user_id" placeholder="Optional"></div>
<div class="col-md-3"><label>Recipient</label><input class="form-control" name="recipient" placeholder="Email or phone"></div>
<div class="col-md-2"><label>Channel</label><select class="form-select" name="channel"><option value="email">Email</option><option value="sms">SMS</option><option value="in_app">In App</option></select></div>
<div class="col-md-4"><label>Template code</label><input class="form-control" name="template_code" placeholder="Optional active template"></div>
<div class="col-md-6"><label>Subject</label><input class="form-control" name="subject" placeholder="Email subject"></div>
<div class="col-md-6"><label>Message</label><input class="form-control" name="message" placeholder="Notification message"></div>
<div class="col-12"><button class="btn btn-primary">Send Notification</button></div>
</form></div>
<div class="card p-3"><h4>Notification Logs</h4><div class="table-responsive"><table class="table"><thead><tr><th>ID</th><th>Recipient</th><th>Channel</th><th>Template</th><th>Status</th><th>Sent</th></tr></thead><tbody>@forelse($logs as $log)<tr><td>#{{ $log->id }}</td><td>{{ $log->recipient }}</td><td>{{ $log->channel }}</td><td>{{ $log->template_code }}</td><td>{{ $log->status }}</td><td>{{ $log->sent_at ?? '—' }}</td></tr>@empty<tr><td colspan="6">No notification logs.</td></tr>@endforelse</tbody></table></div>{{ $logs->links() }}</div></div>
@endsection