@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">@yield('title', 'Activity Logs')</h3>
                    <div class="card-tools">
                        @yield('header-actions')
                    </div>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    @yield('log-content')
                </div>
                @hasSection('card-footer')
                    <div class="card-footer">
                        @yield('card-footer')
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .log-property {
        margin-bottom: 1rem;
    }
    .log-property label {
        font-weight: 600;
        margin-bottom: 0.25rem;
        display: block;
    }
    .log-property pre {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 0.25rem;
        margin: 0;
        max-height: 300px;
        overflow-y: auto;
    }
    .badge-event {
        font-size: 0.8em;
        padding: 0.35em 0.65em;
    }
    .activity-timeline {
        position: relative;
        padding-left: 2rem;
    }
    .activity-timeline::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0.5rem;
        width: 2px;
        background: #dee2e6;
    }
    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        top: 0.25rem;
        left: -2rem;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        background: #6c757d;
        z-index: 1;
    }
    .timeline-item-primary::before {
        background: #007bff;
    }
    .timeline-item-success::before {
        background: #28a745;
    }
    .timeline-item-danger::before {
        background: #dc3545;
    }
    .timeline-item-warning::before {
        background: #ffc107;
    }
    .timeline-item-info::before {
        background: #17a2b8;
    }
</style>
@endpush
