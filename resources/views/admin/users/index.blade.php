@extends('layouts.app')

@section('content')
<div class="users-page">
    <header><div><p>ADMINISTRATION</p><h1>User Management</h1></div><a href="{{ route('dashboard.admin') }}">← Dashboard</a></header>

    @if(session('status'))<div class="notice">{{ session('status') }}</div>@endif

    <form class="filters" method="GET">
        <input name="q" value="{{ request('q') }}" placeholder="Search name, email or phone">
        <select name="role">
            <option value="">All roles</option>
            @foreach($roles as $role)<option value="{{ $role->slug }}" @selected(request('role')===$role->slug)>{{ $role->name }}</option>@endforeach
        </select>
        <button>Search</button>
    </form>

    <div class="table-card"><div class="scroll"><table>
        <thead><tr><th>User</th><th>Contact</th><th>Current roles</th><th>Manage roles</th></tr></thead>
        <tbody>
        @forelse($users as $user)
        <tr>
            <td><strong>{{ $user->name }}</strong><br><small>#{{ $user->id }}</small></td>
            <td>{{ $user->email }}<br><small>{{ $user->phone ?: '—' }}</small></td>
            <td>{{ $user->roles->pluck('role.name')->filter()->join(', ') ?: 'No role' }}</td>
            <td>
                <form method="POST" action="{{ route('admin.users.roles', $user) }}" class="role-form">
                    @csrf
                    @php($assigned=$user->roles->pluck('role.slug')->filter()->all())
                    @foreach($roles as $role)
                    <label><input type="checkbox" name="roles[]" value="{{ $role->slug }}" @checked(in_array($role->slug,$assigned))> {{ $role->name }}</label>
                    @endforeach
                    <button>Save</button>
                </form>
            </td>
        </tr>
        @empty<tr><td colspan="4">No users found.</td></tr>@endforelse
        </tbody>
    </table></div>
    <div class="pagination">{{ $users->links() }}</div></div>
</div>
<style>
.users-page{max-width:1400px;margin:auto;padding:36px;background:#f6f8fb;min-height:100vh;font-family:Arial,sans-serif}.users-page header{display:flex;justify-content:space-between;align-items:center;margin-bottom:24px}.users-page header p{font-size:12px;letter-spacing:1px;color:#627d98;font-weight:bold}.users-page h1{margin:4px 0;color:#102a43}.users-page a{text-decoration:none;color:#176b87;font-weight:bold}.filters{display:flex;gap:10px;margin-bottom:18px}.filters input,.filters select{padding:12px;border:1px solid #d9e2ec;border-radius:8px;background:#fff}.filters input{flex:1}.filters button,.role-form button{background:#176b87;color:#fff;border:0;padding:10px 14px;border-radius:7px;cursor:pointer}.table-card{background:#fff;border-radius:12px;padding:18px;box-shadow:0 2px 12px rgba(0,0,0,.05)}.scroll{overflow:auto}table{width:100%;border-collapse:collapse;min-width:900px}th,td{padding:14px;text-align:left;border-bottom:1px solid #edf2f7;vertical-align:top}th{font-size:12px;color:#627d98}small{color:#627d98}.role-form{display:flex;gap:8px;flex-wrap:wrap;align-items:center}.role-form label{font-size:13px;background:#f5f7fa;padding:6px;border-radius:6px}.notice{background:#e6fffa;padding:12px;border-radius:8px;margin-bottom:15px}.pagination{margin-top:16px}@media(max-width:650px){.users-page{padding:18px}.filters{flex-direction:column}}
</style>
@endsection
