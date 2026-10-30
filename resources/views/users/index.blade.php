<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px;
            font-family: Arial, sans-serif;
            background: #f4f1fb;
            color: #29233a;
        }

        .container {
            max-width: 1100px;
            margin: auto;
        }

        .header, .section {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 22px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.05);
        }

        .header {
            background: #5b21b6;
            color: white;
        }

        h1, h2 {
            margin-top: 0;
        }

        .button {
            display: inline-block;
            border: none;
            border-radius: 7px;
            padding: 10px 15px;
            text-decoration: none;
            cursor: pointer;
            font-weight: bold;
            background: #5b21b6;
            color: white;
        }

        .back {
            background: white;
            color: #5b21b6;
            margin-top: 10px;
        }

        .delete {
            background: #dc2626;
        }

        .success, .error {
            padding: 12px;
            border-radius: 7px;
            margin-bottom: 15px;
        }

        .success {
            background: #dcfce7;
            color: #166534;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 13px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        th {
            background: #f5f3ff;
            color: #5b21b6;
        }

        select {
            padding: 9px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-right: 5px;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
            align-items: center;
        }

        .current {
            color: #777;
            font-size: 13px;
        }

        @media (max-width: 700px) {
            body {
                padding: 12px;
            }

            .section {
                overflow-x: auto;
            }

            table {
                min-width: 700px;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <h1>Employee Management</h1>
        <p>View employee accounts and manage their system roles.</p>

        <a href="{{ route('dashboard') }}" class="button back">
            Back to Dashboard
        </a>
    </div>

    <div class="section">

        @if(session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="error">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <h2>Registered Employees</h2>

        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Current Role</th>
                    <th>Manage Role</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>
                            {{ $user->name }}

                            @if($user->id === Auth::id())
                                <div class="current">Your account</div>
                            @endif
                        </td>

                        <td>{{ $user->email }}</td>

                        <td>{{ strtoupper($user->role) }}</td>

                        <td>
                            @if($user->id === Auth::id())
                                <span class="current">Cannot change own role</span>
                            @else
                                <form method="POST"
                                      action="{{ route('users.update', $user) }}"
                                      class="actions">
                                    @csrf
                                    @method('PATCH')

                                    <select name="role" required>
                                        <option value="admin"
                                            {{ $user->role === 'admin' ? 'selected' : '' }}>
                                            Admin
                                        </option>

                                        <option value="cashier"
                                            {{ $user->role === 'cashier' ? 'selected' : '' }}>
                                            Cashier
                                        </option>
                                    </select>

                                    <button type="submit" class="button">
                                        Update
                                    </button>
                                </form>
                            @endif
                        </td>

                        <td>
                            @if($user->id !== Auth::id())
                                <form method="POST"
                                      action="{{ route('users.destroy', $user) }}"
                                      onsubmit="return confirm('Are you sure you want to delete this account?');">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="button delete">
                                        Delete
                                    </button>
                                </form>
                            @else
                                <span class="current">Current account</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No employee accounts found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            {{ $users->links() }}
        </div>

    </div>

</div>

</body>
</html>
