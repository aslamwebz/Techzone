<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    /**
     * Number of items per page for pagination.
     *
     * @var int
     */
    protected $perPage = 25;

    /**
     * Display a listing of the activity logs.
     */
    public function index(Request $request): View
    {
        $query = ActivityLog::with(['causer', 'subject'])
            ->latest();

        // Apply filters
        $query = $this->applyFilters($query, $request);

        // Get paginated results
        $logs = $query->paginate($this->perPage);

        // Get filter data for the view
        $filterData = $this->getFilterData();

        return view('admin.logs.index', compact('logs', 'filterData'));
    }

    /**
     * Display the specified activity log.
     */
    public function show(string $id): View
    {
        $log = ActivityLog::with(['causer', 'subject'])->findOrFail($id);

        return view('admin.logs.show', compact('log'));
    }

    /**
     * Show the activity log dashboard.
     */
    public function dashboard(): View
    {
        // Get recent activities
        $recentActivities = ActivityLog::with('causer')
            ->latest()
            ->take(10)
            ->get();

        // Get activity counts by user
        $topUsers = ActivityLog::select([
            'causer_id',
            'causer_type',
            DB::raw('count(*) as activity_count'),
        ])
            ->groupBy('causer_id', 'causer_type')
            ->orderBy('activity_count', 'desc')
            ->with('causer')
            ->take(5)
            ->get();

        // Get activity counts by event type
        $activityByEvent = ActivityLog::select([
            'event',
            DB::raw('count(*) as count'),
        ])
            ->groupBy('event')
            ->orderBy('count', 'desc')
            ->get()
            ->pluck('count', 'event');

        // Get activity counts by day for the last 30 days
        $activityByDay = ActivityLog::select([
            DB::raw('DATE(created_at) as date'),
            DB::raw('count(*) as count'),
        ])
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date');

        return view('admin.logs.dashboard', [
            'recentActivities' => $recentActivities,
            'topUsers' => $topUsers,
            'activityByEvent' => $activityByEvent,
            'activityByDay' => $activityByDay,
        ]);
    }

    /**
     * Show the user activity report.
     */
    public function userActivity(Request $request, ?User $user = null): View
    {
        $query = ActivityLog::with('subject')
            ->where('causer_type', User::class);

        if ($user->exists) {
            $query->where('causer_id', $user->id);
            $user->loadCount('activityLogs');
        } else {
            $query->join('users', 'activity_logs.causer_id', '=', 'users.id')
                ->select('activity_logs.*', 'users.name as user_name', 'users.email as user_email')
                ->withCasts([
                    'created_at' => 'datetime',
                    'updated_at' => 'datetime',
                ]);
        }

        // Apply filters
        $query = $this->applyFilters($query, $request);

        // Get paginated results
        $logs = $query->latest('activity_logs.created_at')
            ->paginate($this->perPage);

        // Get filter data for the view
        $filterData = $this->getFilterData();

        return view('admin.logs.user-activity', [
            'logs' => $logs,
            'filterData' => $filterData,
            'selectedUser' => $user->exists ? $user : null,
        ]);
    }

    /**
     * Apply filters to the query.
     *
     * @param  Builder  $query
     * @return Builder
     */
    protected function applyFilters($query, Request $request)
    {
        // Filter by event type
        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        // Filter by log name
        if ($request->filled('log_name')) {
            $query->where('log_name', $request->log_name);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by search term
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('properties', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    /**
     * Get filter data for the view.
     */
    protected function getFilterData(): array
    {
        return [
            'events' => ActivityLog::select('event')
                ->distinct()
                ->orderBy('event')
                ->pluck('event'),

            'logNames' => ActivityLog::select('log_name')
                ->distinct()
                ->orderBy('log_name')
                ->pluck('log_name'),
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
