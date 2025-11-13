<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Home | UBD</title>
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
  <div class="dashboard-container">
  @include('admin.navbar')
  
    <div class="program-detail">
    <h2>User activity logs</h2>

    <form method="GET" action="{{ route('admin.logs') }}" style="margin-bottom: 20px;">
    <input type="text" name="search" placeholder="Search logs..."
           value="{{ request('search') }}" style="padding: 8px;">

    <select name="action" style="padding: 8px;">
        <option value="">All Actions</option>
        <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Created</option>
        <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Updated</option>
        <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Deleted</option>
        <option value="restored" {{ request('action') == 'restored' ? 'selected' : '' }}>Restored</option>
    </select>

    <button type="submit" style="padding: 8px 12px;">Search</button>
    </form>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">User</th>
                    <th scope="col">Action</th>
                    <th scope="col">Model</th>
                    <th scope="col">Description</th>
                    <th scope="col">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $index => $log)
                    <tr>
                        <td>{{ $logs->firstItem() + $index }}</td>
                        <td>{{ $log->user->username ?? 'Unknown' }}</td>
                        <td>
                            <span class="badge 
                                @if($log->action == 'created') bg-success
                                @elseif($log->action == 'updated') bg-info
                                @elseif($log->action == 'deleted') bg-danger
                                @elseif($log->action == 'restored') bg-warning
                                @endif">
                                {{ ucfirst($log->action) }}
                            </span>
                        </td>
                        <td>{{ $log->model }}</td>
                        <td>{{ $log->description }}</td>
                        <td>{{ $log->created_at->format('d M Y, h:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            No logs found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $logs->withQueryString()->links() }}
    </div>
</div>