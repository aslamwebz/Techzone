@extends('admin.logs.layout')

@section('title', isset($selectedUser) ? "Activity for {$selectedUser->name}" : 'User Activity Logs')

@section('header-actions')
    <a href="{{ route('admin.logs.dashboard') }}" class="btn btn-sm btn-info">
        <i class="fas fa-chart-bar"></i> Dashboard
    </a>
    <a href="{{ route('admin.logs.index') }}" class="btn btn-sm btn-secondary">
        <i class="fas fa-list"></i> All Logs
    </a>
@endsection

@section('log-content')
    <div class="row mb-3">
        <div class="col-md-12">
            <form method="GET" action="{{ route('admin.logs.user-activity') }}" class="form-inline">
                <div class="input-group input-group-sm w-100">
                    @if(!isset($selectedUser))
                        <input type="text" name="search" class="form-control" placeholder="Search users..." value="{{ request('search') }}">
                    @endif
                    
                    <select name="event" class="form-control ml-2">
                        <option value="">All Events</option>
                        @foreach($filterData['events'] as $event)
                            <option value="{{ $event }}" {{ request('event') == $event ? 'selected' : '' }}>{{ $event }}</option>
                        @endforeach
                    </select>
                    
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        @if(request()->hasAny(['search', 'event', 'user']))
                            <a href="{{ route('admin.logs.user-activity') }}" class="btn btn-secondary">
                                <i class="fas fa-undo"></i> Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(isset($selectedUser))
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="shrink-0">
                        <div class="avatar avatar-lg">
                            <span class="avatar-title rounded-circle bg-primary text-white">
                                {{ substr($selectedUser->name, 0, 1) }}
                            </span>
                        </div>
                    </div>
                    <div class="grow ms-3">
                        <h5 class="mb-1">{{ $selectedUser->name }}</h5>
                        <p class="text-muted mb-1">{{ $selectedUser->email }}</p>
                        <div class="small text-muted">
                            <span class="me-3">
                                <i class="fas fa-calendar me-1"></i> 
                                Joined {{ $selectedUser->created_at->diffForHumans() }}
                            </span>
                            <span>
                                <i class="fas fa-sign-in-alt me-1"></i> 
                                Last login: {{ $selectedUser->last_login_at ? $selectedUser->last_login_at->diffForHumans() : 'Never' }}
                            </span>
                        </div>
                    </div>
                    <div class="shrink-0">
                        <span class="badge bg-primary">{{ $selectedUser->activity_logs_count }} activities</span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            @if(!isset($selectedUser))
                                <th>User</th>
                            @endif
                            <th>Description</th>
                            <th>Event</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            @php
                                $badgeClass = match($log->event) {
                                    'CREATED' => 'badge-success',
                                    'UPDATED' => 'badge-primary',
                                    'DELETED' => 'badge-danger',
                                    default => 'badge-secondary',
                                };
                            @endphp
                            <tr>
                                @if(!isset($selectedUser))
                                    <td>
                                        @if($log->causer)
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-xs me-2">
                                                    <span class="avatar-title rounded-circle bg-primary text-white">
                                                        {{ substr($log->causer->name, 0, 1) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <div>{{ $log->causer->name }}</div>
                                                    <div class="small text-muted">{{ $log->causer->email }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">System</span>
                                        @endif
                                    </td>
                                @endif
                                <td>
                                    <div class="text-truncate" style="max-width: 300px;" title="{{ $log->description }}">
                                        {{ $log->description }}
                                    </div>
                                    @if(isset($log->user_name) && !isset($selectedUser))
                                        <div class="small text-muted">
                                            {{ $log->user_name }} • {{ $log->user_email }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $badgeClass }} badge-event">{{ $log->event }}</span>
                                </td>
                                <td>
                                    <span title="{{ $log->created_at->format('M d, Y h:i A') }}">
                                        {{ $log->created_at->diffForHumans() }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.logs.show', $log) }}" class="btn btn-sm btn-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(isset($selectedUser) && $log->subject)
                                        <a href="#" class="btn btn-sm btn-outline-primary" title="View Subject">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ isset($selectedUser) ? 4 : 5 }}" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-2x mb-2"></i>
                                        <p class="mb-0">No activity logs found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($logs->hasPages())
            <div class="card-footer">
                {{ $logs->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection
