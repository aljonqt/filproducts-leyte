@extends('admin.layout')

@section('content')

<style>
    .content {
        background: #f4f6f9;
        padding: 20px;
        border-radius: 10px;
        min-height: 100vh;

        /* FORCE VERTICAL STACK */
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .card {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        width: 100%;
    }

    input, select {
        padding: 10px;
        margin: 5px;
        border: 1px solid #ddd;
        border-radius: 6px;
    }

    button {
        padding: 10px 15px;
        background: #003366;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
    }

    button:hover {
        background: #002244;
    }

    .delete-btn {
        background: red;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 12px;
        border: 1px solid #ddd;
    }

    th {
        background: #003366;
        color: white;
    }

    .success {
        color: green;
    }

    .error {
        color: red;
    }
</style>

<div class="content">

    {{-- ALERTS --}}
    @if(session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p class="error">{{ session('error') }}</p>
    @endif

    {{-- =========================
        ADD STAFF (TOP)
    ========================== --}}
    <div class="card">
        <h3>Add Staff</h3>

        <form method="POST" action="{{ route('admin.staff.store') }}">
            @csrf

            <input type="text" name="firstname" placeholder="First Name" required>
            <input type="text" name="lastname" placeholder="Last Name" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>

            <select name="role">
                <option value="staff">Staff</option>
                <option value="admin">Admin</option>
            </select>

            <br>

            <label><input type="checkbox" name="can_manage_users" value="1"> Manage Users</label>
            <label><input type="checkbox" name="can_view_reports" value="1"> View Reports</label>
            <label><input type="checkbox" name="can_edit_data" value="1"> Edit Data</label>

            <br><br>

            <button type="submit">Add Staff</button>
        </form>
    </div>

    {{-- =========================
        STAFF LIST (BOTTOM)
    ========================== --}}
    <div class="card">
        <h3>Staff List</h3>

        <table>
            <tr>
                <th>Name</th>
                <th>Username</th>
                <th>Role</th>
                <th>Action</th>
            </tr>

            @foreach($users as $user)
            <tr>
                <td>{{ $user->firstname }} {{ $user->lastname }}</td>
                <td>{{ $user->username }}</td>
                <td>{{ ucfirst($user->role) }}</td>
                <td>
                    <form method="POST" action="{{ route('admin.staff.delete', $user->id) }}">
                        @csrf
                        <button class="delete-btn">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
    </div>

</div>

@endsection