@extends('layouts.app')

@section('content')
<div class="container py-4 finance-page">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <p class="text-uppercase small fw-semibold text-primary mb-1">Administration</p>
            <h2 class="mb-1">Finance & Commission</h2>
            <p class="text-muted mb-0">Manage commission rules, vendor balances and payout requests.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="text-muted small mb-2">Vendor Available Balance</div>
                    <h3 class="mb-0 fw-bold">৳ {{ number_format($walletTotal, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="text-muted small mb-2">Pending Payouts</div>
                    <h3 class="mb-0 fw-bold">৳ {{ number_format($pendingPayouts, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="text-muted small mb-2">Paid Payouts</div>
                    <h3 class="mb-0 fw-bold">৳ {{ number_format($paidPayouts, 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0">Add Commission Rule</h5>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.finance.rules.store') }}" class="row g-3">
                @csrf
                <div class="col-md-4 col-lg-3">
                    <label class="form-label">Scope Type</label>
                    <input class="form-control" name="scope_type" placeholder="platform, vendor or property" required>
                </div>
                <div class="col-md-3 col-lg-2">
                    <label class="form-label">Scope ID</label>
                    <input class="form-control" type="number" name="scope_id" placeholder="Optional">
                </div>
                <div class="col-md-3 col-lg-2">
                    <label class="form-label">Rule Type</label>
                    <select class="form-select" name="type">
                        <option value="percentage">Percentage</option>
                        <option value="fixed">Fixed</option>
                    </select>
                </div>
                <div class="col-md-3 col-lg-2">
                    <label class="form-label">Value</label>
                    <input class="form-control" type="number" step="0.01" name="value" placeholder="0.00" required>
                </div>
                <div class="col-md-2 col-lg-1">
                    <label class="form-label">Priority</label>
                    <input class="form-control" type="number" name="priority" value="0">
                </div>
                <div class="col-md-4 col-lg-2 d-flex align-items-end">
                    <button class="btn btn-primary w-100">Add Rule</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0">Commission Rules</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Scope</th>
                            <th>Type</th>
                            <th>Value</th>
                            <th>Priority</th>
                            <th class="pe-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rules as $rule)
                            <tr>
                                <td class="ps-4">{{ $rule->scope_type }} #{{ $rule->scope_id ?? 'All' }}</td>
                                <td>{{ ucfirst($rule->type) }}</td>
                                <td>{{ $rule->value }}</td>
                                <td>{{ $rule->priority }}</td>
                                <td class="pe-4">
                                    @if($rule->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No commission rules yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0">Payout Management</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Vendor</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th class="pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payouts as $payout)
                            <tr>
                                <td class="ps-4">#{{ $payout->id }}</td>
                                <td>#{{ $payout->vendor_id }}</td>
                                <td class="fw-semibold">৳ {{ number_format($payout->amount, 2) }}</td>
                                <td>
                                    @if($payout->status === 'requested')
                                        <span class="badge bg-warning text-dark">Requested</span>
                                    @elseif($payout->status === 'approved')
                                        <span class="badge bg-primary">Approved</span>
                                    @elseif($payout->status === 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @elseif($payout->status === 'rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($payout->status) }}</span>
                                    @endif
                                </td>
                                <td class="pe-4">
                                    @if(in_array($payout->status, ['requested', 'approved']))
                                        <form method="POST" action="{{ route('admin.finance.payouts.process', $payout) }}" class="d-flex flex-wrap gap-2 align-items-center">
                                            @csrf
                                            @if($payout->status === 'requested')
                                                <button name="action" value="approve" class="btn btn-sm btn-primary">Approve</button>
                                                <button name="action" value="reject" class="btn btn-sm btn-outline-danger">Reject</button>
                                            @else
                                                <input name="reference" class="form-control form-control-sm" style="min-width:150px" placeholder="Payment reference" required>
                                                <button name="action" value="mark_paid" class="btn btn-sm btn-success">Mark Paid</button>
                                            @endif
                                        </form>
                                    @else
                                        <span class="text-muted small">No action available</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No payouts found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if(method_exists($payouts, 'links'))
            <div class="card-footer bg-white border-top py-3">
                {{ $payouts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection