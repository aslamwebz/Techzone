@extends('admin.logs.layout')

@section('title', 'Activity Dashboard')

@section('header-actions')
    <a href="{{ route('admin.logs.index') }}" class="btn btn-sm btn-secondary">
        <i class="fas fa-list"></i> View All Logs
    </a>
    <a href="{{ route('admin.logs.user-activity') }}" class="btn btn-sm btn-primary">
        <i class="fas fa-users"></i> User Activity
    </a>
@endsection

@section('log-content')
    <div class="row">
        <!-- Activity Summary -->
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Activity Overview (Last 30 Days)</h5>
                </div>
                <div class="card-body">
                    <canvas id="activityChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Recent Activities</h5>
                    <a href="{{ route('admin.logs.index') }}" class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="activity-timeline p-4">
                        @forelse($recentActivities as $activity)
                            @php
                                $timelineClass = match($activity->event) {
                                    'CREATED' => 'timeline-item-success',
                                    'UPDATED' => 'timeline-item-primary',
                                    'DELETED' => 'timeline-item-danger',
                                    default => 'timeline-item-info',
                                };
                                
                                $icon = match($activity->event) {
                                    'CREATED' => 'plus-circle',
                                    'UPDATED' => 'edit',
                                    'DELETED' => 'trash',
                                    default => 'info-circle',
                                };
                            @endphp
                            
                            <div class="timeline-item {{ $timelineClass }}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="mr-3">
                                        <i class="fas fa-{{ $icon }} mr-1"></i>
                                        {{ $activity->description }}
                                        @if($activity->causer)
                                            <span class="text-muted">by {{ $activity->causer->name }}</span>
                                        @endif
                                    </div>
                                    <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                </div>
                                @if($activity->event)
                                    <div class="mt-1">
                                        <span class="badge bg-{{ strtolower($activity->event) }}">
                                            {{ $activity->event }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">
                                No recent activities found.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Stats -->
        <div class="col-md-4">
            <!-- Top Users -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Top Users</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($topUsers as $user)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    @if($user->causer)
                                        <a href="{{ route('admin.users.show', $user->causer) }}" class="font-weight-bold">
                                            {{ $user->causer->name }}
                                        </a>
                                    @else
                                        <span>System</span>
                                    @endif
                                    <div class="text-muted small">
                                        {{ $user->causer->email ?? 'N/A' }}
                                    </div>
                                </div>
                                <span class="badge bg-primary rounded-pill">{{ $user->activity_count }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-3">
                                No user activity data available.
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Activity by Event Type -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Activity by Event Type</h5>
                </div>
                <div class="card-body">
                    <canvas id="eventTypeChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Activity Over Time Chart
        const activityCtx = document.getElementById('activityChart').getContext('2d');
        const activityLabels = {!! json_encode($activityByDay->keys()->map(fn($date) => \Carbon\Carbon::parse($date)->format('M d'))) !!};
        const activityData = {!! json_encode($activityByDay->values()) !!};
        
        new Chart(activityCtx, {
            type: 'line',
            data: {
                labels: activityLabels,
                datasets: [{
                    label: 'Activities',
                    data: activityData,
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    borderColor: '#007bff',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });

        // Event Type Chart
        const eventCtx = document.getElementById('eventTypeChart').getContext('2d');
        const eventLabels = {!! json_encode($activityByEvent->keys()) !!};
        const eventData = {!! json_encode($activityByEvent->values()) !!};
        
        // Generate colors based on event type
        const backgroundColors = eventLabels.map(label => {
            return {
                'CREATED': 'rgba(40, 167, 69, 0.8)',
                'UPDATED': 'rgba(23, 162, 184, 0.8)',
                'DELETED': 'rgba(220, 53, 69, 0.8)',
            }[label] || 'rgba(108, 117, 125, 0.8)';
        });
        
        new Chart(eventCtx, {
            type: 'doughnut',
            data: {
                labels: eventLabels,
                datasets: [{
                    data: eventData,
                    backgroundColor: backgroundColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    });
</script>
@endpush
