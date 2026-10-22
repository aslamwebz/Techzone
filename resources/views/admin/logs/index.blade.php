@extends('admin.logs.layout')

@section('title', 'Activity Logs')

@section('header-actions')
    <a href="{{ route('admin.logs.dashboard') }}" class="btn btn-sm btn-info">
        <i class="fas fa-chart-bar"></i> Dashboard
    </a>
    <a href="{{ route('admin.logs.user-activity') }}" class="btn btn-sm btn-primary">
        <i class="fas fa-users"></i> User Activity
    </a>
@endsection

@section('log-content')
    <div class="row mb-3">
        <div class="col-md-12">
            <form method="GET" action="{{ route('admin.logs.index') }}" class="form-inline">
                <div class="input-group input-group-sm w-100">
                    <input type="text" name="search" class="form-control" placeholder="Search logs..." value="{{ request('search') }}">
                    <select name="event" class="form-control ml-2">
                        <option value="">All Events</option>
                        @foreach($filterData['events'] as $event)
                            <option value="{{ $event }}" {{ request('event') == $event ? 'selected' : '' }}>{{ $event }}</option>
                        @endforeach
                    </select>
                    <select name="log_name" class="form-control ml-2">
                        <option value="">All Logs</option>
                        @foreach($filterData['logNames'] as $logName)
                            <option value="{{ $logName }}" {{ request('log_name') == $logName ? 'selected' : '' }}>{{ $logName }}</option>
                        @endforeach
                    </select>
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('admin.logs.index') }}" class="btn btn-secondary">
                            <i class="fas fa-undo"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Description</th>
                    <th>User</th>
                    <th>Event</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>#{{ $log->id }}</td>
                        <td>
                            <div class="text-truncate" style="max-width: 300px;" title="{{ $log->description }}">
                                {{ $log->description }}
                            </div>
                        </td>
                        <td>
                            @if($log->causer)
                                <a href="{{ route('admin.users.show', $log->causer) }}">
                                    {{ $log->causer->name }}
                                </a>
                            @else
                                System
                            @endif
                        </td>
                        <td>
                            @php
                                $badgeClass = match($log->event) {
                                    'CREATED' => 'badge-success',
                                    'UPDATED' => 'badge-primary',
                                    'DELETED' => 'badge-danger',
                                    default => 'badge-secondary',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }} badge-event">{{ $log->event }}</span>
                        </td>
                        <td>{{ $log->created_at->diffForHumans() }}</td>
                        <td>
                            <a href="{{ route('admin.logs.show', $log) }}" class="btn btn-sm btn-info" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No activity logs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $logs->withQueryString()->links() }}
    </div>
@endsection
