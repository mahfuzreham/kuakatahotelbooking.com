@extends('layouts.app')
@section('content')
<div class="container py-4">
<h2>Finance & Commission</h2>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="row g-3 mb-4">
<div class="col-md-4"><div class="card p-3"><small>Vendor Available Balance</small><h3>৳ {{ number_format($walletTotal,2) }}</h3></div></div>
<div class="col-md-4"><div class="card p-3"><small>Pending Payouts</small><h3>৳ {{ number_format($pendingPayouts,2) }}</h3></div></div>
<div class="col-md-4"><div class="card p-3"><small>Paid Payouts</small><h3>৳ {{ number_format($paidPayouts,2) }}</h3></div></div>
</div>
<div class="card p-3 mb-4"><h4>Add Commission Rule</h4>
<form method="POST" action="{{ route('admin.finance.rules.store') }}" class="row g-2">@csrf
<div class="col-md-3"><input class="form-control" name="scope_type" placeholder="Scope type (platform/vendor/property)" required></div>
<div class="col-md-2"><input class="form-control" type="number" name="scope_id" placeholder="Scope ID"></div>
<div class="col-md-2"><select class="form-select" name="type"><option value="percentage">Percentage</option><option value="fixed">Fixed</option></select></div>
<div class="col-md-2"><input class="form-control" type="number" step="0.01" name="value" placeholder="Value" required></div>
<div class="col-md-1"><input class="form-control" type="number" name="priority" value="0"></div>
<div class="col-md-2"><button class="btn btn-primary w-100">Add Rule</button></div>
</form></div>
<div class="card p-3 mb-4"><h4>Commission Rules</h4><div class="table-responsive"><table class="table"><thead><tr><th>Scope</th><th>Type</th><th>Value</th><th>Priority</th><th>Status</th></tr></thead><tbody>@forelse($rules as $rule)<tr><td>{{ $rule->scope_type }} #{{ $rule->scope_id ?? 'All' }}</td><td>{{ $rule->type }}</td><td>{{ $rule->value }}</td><td>{{ $rule->priority }}</td><td>{{ $rule->is_active?'Active':'Inactive' }}</td></tr>@empty<tr><td colspan="5">No rules yet.</td></tr>@endforelse</tbody></table></div></div>
<div class="card p-3"><h4>Payout Management</h4><div class="table-responsive"><table class="table"><thead><tr><th>ID</th><th>Vendor</th><th>Amount</th><th>Status</th><th>Action</th></tr></thead><tbody>@forelse($payouts as $payout)<tr><td>#{{ $payout->id }}</td><td>#{{ $payout->vendor_id }}</td><td>৳ {{ number_format($payout->amount,2) }}</td><td>{{ $payout->status }}</td><td>@if(in_array($payout->status,['requested','approved']))<form class="d-inline" method="POST" action="{{ route('admin.finance.payouts.process',$payout) }}">@csrf@if($payout->status==='requested')<button name="action" value="approve" class="btn btn-sm btn-primary">Approve</button><button name="action" value="reject" class="btn btn-sm btn-danger">Reject</button>@else<input name="reference" class="form-control form-control-sm d-inline-block" style="width:130px" placeholder="Reference"><button name="action" value="mark_paid" class="btn btn-sm btn-success">Mark Paid</button>@endif</form>@endif</td></tr>@empty<tr><td colspan="5">No payouts found.</td></tr>@endforelse</tbody></table></div>{{ $payouts->links() }}</div>
</div>
@endsection