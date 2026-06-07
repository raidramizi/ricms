<x-app-layout>

<style>

/* SEARCH */
.search-box {
    display: flex;
    gap: 10px;
    margin-bottom: 18px;
}

.search-input {
    flex: 1;
    padding: 12px 14px;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    outline: none;
}

.search-btn {
    padding: 12px 16px;
    background: linear-gradient(135deg,#4f46e5,#7c3aed);
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: 600;
}

/* TABLE */
.table-wrapper {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0,0,0,0.06);
}

table {
    width: 100%;
    border-collapse: collapse;
}

thead {
    background: #f9fafb;
    font-size: 12px;
    text-transform: uppercase;
    color: #6b7280;
}

th, td {
    padding: 14px;
    border-bottom: 1px solid #f3f4f6;
}

th {
    text-align: center;
    font-weight: 600;
}

td {
    vertical-align: middle;
}

tr:hover {
    background: #fafafa;
}

/* USER */
.user-box {
    display: flex;
    align-items: center;
    gap: 10px;
}

.avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: linear-gradient(135deg,#6366f1,#a855f7);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

/* DROPDOWN */
.select-box {
    padding: 10px 12px;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    background: white;
    font-weight: 500;
}

/* BUTTON */
.btn-update {
    padding: 9px 14px;
    border-radius: 12px;
    border: none;
    background: #4f46e5;
    color: white;
    font-weight: 600;
    cursor: pointer;
}

.btn-update:hover {
    background: #4338ca;
}

/* PAGINATION */
.pagination {
    margin-top: 20px;
}

</style>
<x-slot name="title">
    User Management
</x-slot>
<x-slot name="header">
    <h2 class="text-xl font-semibold text-gray-800">
        Head - User Management
    </h2>
</x-slot>

<div class="p-6 max-w-7xl mx-auto">

<!-- SEARCH -->
<form method="GET" class="search-box">
    <input type="text"
           name="search"
           value="{{ request('search') }}"
           placeholder="Search name / staff ID / email..."
           class="search-input">

    <button class="search-btn">🔍 Search</button>
</form>

@if(session('success'))
<div class="mb-4 p-3 bg-green-50 text-green-700 border-l-4 border-green-500 rounded-lg">
    {{ session('success') }}
</div>
@endif

<!-- TABLE -->
<div class="table-wrapper">

<table>

<thead>
<tr>
    <th>No</th>
    <th>User</th>
    <th>Staff ID</th>
    <th>Email</th>
    <th>Role</th>
    <th>Status</th>
    <th>Action</th>
</tr>
</thead>

<tbody>

@foreach($users as $index => $user)

<tr>

    <!-- NUMBER -->
    <td class="text-center text-gray-500 font-semibold">
        {{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}
    </td>

    <!-- USER -->
    <td>
        <div class="user-box">
            <div class="avatar">
                {{ strtoupper(substr($user->name,0,1)) }}
            </div>
            <div>
                <div><b>{{ $user->name }}</b></div>
                <div style="font-size:11px;color:gray;">
                    {{ $user->role }}
                </div>
            </div>
        </div>
    </td>

    <td>{{ $user->staff_id }}</td>
    <td>{{ $user->email }}</td>

    <!-- FORM START INSIDE CELL -->
    <td>
        <form method="POST" action="{{ route('head.users.update', $user->id) }}">
        @csrf

        <select name="role" class="select-box">
            <option value="Academician" {{ $user->role=='Academician'?'selected':'' }}>Academician</option>
            <option value="R&I Staff" {{ $user->role=='R&I Staff'?'selected':'' }}>R&I Staff</option>
            <option value="Head" {{ $user->role=='Head'?'selected':'' }}>Head</option>
        </select>
    </td>

    <td>
        <select name="status" class="select-box">
            <option value="Active" {{ $user->status=='Active'?'selected':'' }}>Active</option>
            <option value="Inactive" {{ $user->status=='Inactive'?'selected':'' }}>Inactive</option>
        </select>
    </td>

    <td>
        <button class="btn-update">
            Update
        </button>
        </form>
    </td>

</tr>

@endforeach

</tbody>

</table>

</div>

<!-- PAGINATION -->
<div class="pagination">
    {{ $users->links() }}
</div>

</div>

</x-app-layout>
