@extends('layouts.app')

@section('content')
<style>
.finance-page{max-width:1280px;margin:0 auto;padding:32px 20px;font-family:Arial,sans-serif;color:#1f2937}
.finance-page *{box-sizing:border-box}
.finance-top{display:flex;justify-content:space-between;align-items:center;margin-bottom:24px}
.finance-kicker{margin:0 0 6px;color:#0f766e;font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase}
.finance-page h2{margin:0;font-size:28px}.finance-subtitle{margin:7px 0 0;color:#64748b}
.finance-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:18px;margin-bottom:24px}
.finance-card{background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:22px;box-shadow:0 4px 16px rgba(15,23,42,.06)}
.finance-label{color:#64748b;font-size:14px;margin-bottom:10px}.finance-value{font-size:26px;font-weight:700;color:#0f172a}
.finance-panel{background:#fff;border:1px solid #e5e7eb;border-radius:14px;margin-bottom:24px;overflow:hidden;box-shadow:0 4px 16px rgba(15,23,42,.05)}
.finance-panel-head{padding:18px 22px;border-bottom:1px solid #e5e7eb;font-size:18px;font-weight:700}.finance-panel-body{padding:22px}
.finance-form{display:grid;grid-template-columns:1.5fr 1fr 1fr 1fr .7fr 1fr;gap:14px;align-items:end}
.finance-field label{display:block;font-size:13px;font-weight:600;margin-bottom:7px;color:#475569}
.finance-page input,.finance-page select{width:100%;height:42px;padding:0 12px;border:1px solid #cbd5e1;border-radius:8px;background:#fff;font-size:14px}
.finance-page button{border:0;border-radius:8px;padding:10px 14px;font-weight:600;cursor:pointer}
.btn-primary{background:#0f766e!important;color:#fff}.btn-success{background:#16a34a!important;color:#fff}.btn-danger{background:#dc2626!important;color:#fff}.btn-outline-danger{background:#fff!important;color:#dc2626!important;border:1px solid #dc2626!important}
.finance-table-wrap{overflow-x:auto}.finance-table{width:100%;border-collapse:collapse;min-width:720px}.finance-table th{background:#f8fafc;color:#475569;font-size:12px;text-transform:uppercase;letter-spacing:.04em;text-align:left}.finance-table th,.finance-table td{padding:15px 18px;border-bottom:1px solid #e5e7eb}.finance-table tr:last-child td{border-bottom:0}
.finance-badge{display:inline-block;padding:5px 10px;border-radius:999px;font-size:12px;font-weight:700}.badge-requested{background:#fef3c7;color:#92400e}.badge-approved{background:#dbeafe;color:#1d4ed8}.badge-paid{background:#dcfce7;color:#15803d}.badge-rejected{background:#fee2e2;color:#b91c1c}.badge-other{background:#e5e7eb;color:#475569}
.payout-actions{display:flex;flex-wrap:wrap;gap:8px;align-items:center}.payout-actions input{width:160px}
.finance-empty{text-align:center;color:#94a3b8;padding:28px!important}.finance-alert{padding:14px 18px;background:#dcfce7;color:#166534;border-radius:10px;margin-bottom:20px}
@media(max-width:900px){.finance-grid{grid-template-columns:1fr}.finance-form{grid-template-columns:1fr 1fr}.finance-form .finance-submit{grid-column:span 2}}
@media(max-width:560px){.finance-page{padding:20px 12px}.finance-page h2{font-size:23px}.finance-form{grid-template-columns:1fr}.finance-form .finance-submit{grid-column:span 1}.finance-panel-body{padding:16px}}
</style>

<div class="finance-page">
    <div class="finance-top">
        <div>
            <p class="finance-kicker">Administration</p>
            <h2 class="mb-1">Finance & Commission</h2>
            <p class="finance-subtitle">Manage commission rules, vendor balances and payout requests.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="finance-alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="finance-grid">
        <div class="finance-card"><div class="finance-label">Vendor Available Balance</div><div class="finance-value">৳ {{ number_format($walletTotal, 2) }}</div></div>
        <div class="finance-card"><div class="finance-label">Pending Payouts</div><div class="finance-value">৳ {{ number_format($pendingPayouts, 2) }}</div></div>
        <div class="finance-card"><div class="finance-label">Paid Payouts</div><div class="finance-value">৳ {{ number_format($paidPayouts, 2) }}</div></div>
    </div>

    <div class="finance-panel">
        <div class="finance-panel-head">Add Commission Rule</div>
        <div class="finance-panel-body">
            <form method="POST" action="{{ route('admin.finance.rules.store') }}" class="finance-form">
                @csrf
                <div class="finance-field">
                    <label >Scope Type</label>
                    <input  name="scope_type" placeholder="platform, vendor or property" required>
                </div>
                <div class="finance-field">
                    <label >Scope ID</label>
                    <input  type="number" name="scope_id" placeholder="Optional">
                </div>
                <div class="finance-field">
                    <label >Rule Type</label>
                    <select  name="type">
                        <option value="percentage">Percentage</option>
                        <option value="fixed">Fixed</option>
                    </select>
                </div>
                <div class="finance-field">
                    <label >Value</label>
                    <input  type="number" step="0.01" name="value" placeholder="0.00" required>
                </div>
                <div class="finance-field">
                    <label >Priority</label>
                    <input  type="number" name="priority" value="0">
                </div>
                <div class="finance-field finance-submit">
                    <button class="btn btn-primary w-100">Add Rule</button>
                </div>
            </form>
        </div>
    </div>

    <div class="finance-panel">
        <div class="finance-panel-head">Commission Rules</div>
        <div class="finance-table-wrap">
            
                <table class="finance-table">
                    <thead >
                        <tr>
                            <th >Scope</th>
                            <th>Type</th>
                            <th>Value</th>
                            <th>Priority</th>
                            <th >Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rules as $rule)
                            <tr>
                                <td >{{ $rule->scope_type }} #{{ $rule->scope_id ?? 'All' }}</td>
                                <td>{{ ucfirst($rule->type) }}</td>
                                <td>{{ $rule->value }}</td>
                                <td>{{ $rule->priority }}</td>
                                <td >
                                    @if($rule->is_active)
                                        <span class="finance-badge badge-paid">Active</span>
                                    @else
                                        <span class="finance-badge badge-other">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="finance-empty">No commission rules yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
    </div>

    <div class="finance-panel">
        <div class="finance-panel-head">Payout Management</div>
        <div class="finance-table-wrap">
            
                <table class="finance-table">
                    <thead >
                        <tr>
                            <th >ID</th>
                            <th>Vendor</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th >Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payouts as $payout)
                            <tr>
                                <td >#{{ $payout->id }}</td>
                                <td>#{{ $payout->vendor_id }}</td>
                                <td class="fw-semibold">৳ {{ number_format($payout->amount, 2) }}</td>
                                <td>
                                    @if($payout->status === 'requested')
                                        <span class="finance-badge badge-requested">Requested</span>
                                    @elseif($payout->status === 'approved')
                                        <span class="finance-badge badge-approved">Approved</span>
                                    @elseif($payout->status === 'paid')
                                        <span class="finance-badge badge-paid">Paid</span>
                                    @elseif($payout->status === 'rejected')
                                        <span class="finance-badge badge-rejected">Rejected</span>
                                    @else
                                        <span class="finance-badge badge-other">{{ ucfirst($payout->status) }}</span>
                                    @endif
                                </td>
                                <td >
                                    @if(in_array($payout->status, ['requested', 'approved']))
                                        <form method="POST" action="{{ route('admin.finance.payouts.process', $payout) }}" class="payout-actions">
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
                                        <span class="finance-empty">No action available</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="finance-empty">No payouts found.</td>
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