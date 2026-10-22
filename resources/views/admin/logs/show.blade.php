@extends('admin.logs.layout')

@section('title', 'Activity Log Details')

@section('header-actions')
    <a href="{{ route('admin.logs.index') }}" class="btn btn-sm btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Logs
    </a>
@endsection

@section('log-content')
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Log Details</h5>
                </div>
                <div class="card-body">
                    <div class="log-property">
                        <label>ID</label>
                        <div>#{{ $log->id }}</div>
                    </div>
                    
                    <div class="log-property">
                        <label>Description</label>
                        <div>{{ $log->description }}</div>
                    </div>
                    
                    <div class="log-property">
                        <label>Event</label>
                        <div>
                            @php
                                $badgeClass = match($log->event) {
                                    'CREATED' => 'badge-success',
                                    'UPDATED' => 'badge-primary',
                                    'DELETED' => 'badge-danger',
                                    default => 'badge-secondary',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $log->event }}</span>
                        </div>
                    </div>
                    
                    <div class="log-property">
                        <label>Log Name</label>
                        <div>{{ $log->log_name ?? 'default' }}</div>
                    </div>
                    
                    <div class="log-property">
                        <label>Date & Time</label>
                        <div>{{ $log->created_at->format('M d, Y h:i A') }} ({{ $log->created_at->diffForHumans() }})</div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">User Information</h5>
                </div>
                <div class="card-body">
                    @if($log->causer)
                        <div class="log-property">
                            <label>Name</label>
                            <div>
                                <a href="{{ route('admin.users.show', $log->causer) }}">
                                    {{ $log->causer->name }}
                                </a>
                            </div>
                        </div>
                        
                        <div class="log-property">
                            <label>Email</label>
                            <div>{{ $log->causer->email }}</div>
                        </div>
                        
                        @if($log->causer->last_login_at)
                            <div class="log-property">
                                <label>Last Login</label>
                                <div>{{ $log->causer->last_login_at->diffForHumans() }} ({{ $log->causer->last_login_ip }})</div>
                            </div>
                        @endif
                    @else
                        <div class="text-muted">System generated activity</div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Request Information</h5>
                </div>
                <div class="card-body">
                    <div class="log-property">
                        <label>URL</label>
                        <div class="text-truncate" title="{{ $log->properties['url'] ?? 'N/A' }}">
                            {{ $log->properties['url'] ?? 'N/A' }}
                        </div>
                    </div>
                    
                    <div class="log-property">
                        <label>Method</label>
                        <div>{{ $log->properties['method'] ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="log-property">
                        <label>IP Address</label>
                        <div>{{ $log->properties['ip'] ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="log-property">
                        <label>User Agent</label>
                        <div class="text-truncate" title="{{ $log->properties['user_agent'] ?? 'N/A' }}">
                            {{ $log->properties['user_agent'] ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Additional Data</h5>
                </div>
                <div class="card-body">
                    @if(!empty($log->properties['input']))
                        <div class="log-property">
                            <label>Input Data</label>
                            <pre class="p-2 bg-light rounded-sm"><code>{{ json_encode($log->properties['input'], JSON_PRETTY_PRINT) }}</code></pre>
                        </div>
                    @endif
                    
                    @if(!empty($log->properties['old']) || !empty($log->properties['attributes']))
                        <div class="row">
                            @if(!empty($log->properties['old']))
                                <div class="col-md-6">
                                    <div class="log-property">
                                        <label>Old Values</label>
                                        <pre class="p-2 bg-light rounded-sm"><code>{{ json_encode($log->properties['old'], JSON_PRETTY_PRINT) }}</code></pre>
                                    </div>
                                </div>
                            @endif
                            
                            @if(!empty($log->properties['attributes']))
                                <div class="col-md-6">
                                    <div class="log-property">
                                        <label>New Values</label>
                                        <pre class="p-2 bg-light rounded-sm"><code>{{ json_encode($log->properties['attributes'], JSON_PRETTY_PRINT) }}</code></pre>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @elseif(count($log->properties) > 0)
                        <pre class="p-2 bg-light rounded-sm"><code>{{ json_encode($log->properties, JSON_PRETTY_PRINT) }}</code></pre>
                    @else
                        <div class="text-muted">No additional data available</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    @if($log->subject)
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Related Subject</h5>
            </div>
            <div class="card-body">
                <div class="log-property">
                    <label>Type</label>
                    <div>{{ class_basename($log->subject_type) }}</div>
                </div>
                
                <div class="log-property">
                    <label>ID</label>
                    <div>{{ $log->subject_id }}</div>
                </div>
                
                @if(method_exists($log->subject, 'getActivityLogUrl'))
                    <div class="mt-3">
                        <a href="{{ $log->subject->getActivityLogUrl() }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-external-link-alt"></i> View Subject
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif
@endsection
