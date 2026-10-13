<style>
    .workers-tab .toolbar {
        flex-wrap: wrap;
        gap: 8px;
    }

    @media (max-width: 768px) {
        .workers-tab {
            display: flex !important;
            flex-direction: column !important;
            width: 100%;
            min-width: 0;
            gap: 12px;
        }

        .workers-tab .toolbar {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 10px;
            margin-bottom: 0 !important;
        }

        .workers-tab .toolbar>div {
            width: 100%;
        }

        .workers-tab .toolbar .btn-pill {
            width: 100%;
            min-height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-sizing: border-box;
        }

        .workers-tab .card-panel {
            width: 100%;
            min-width: 0;
            box-sizing: border-box;
            padding: 0;
            background: transparent;
            border: 0;
            box-shadow: none;
        }

        .workers-tab .data-table,
        .workers-tab .data-table tbody,
        .workers-tab .data-table tr,
        .workers-tab .data-table td {
            display: block;
            width: 100%;
        }

        .workers-tab .data-table thead {
            position: absolute;
            width: 1px;
            height: 1px;
            overflow: hidden;
            clip: rect(0 0 0 0);
        }

        .workers-tab .data-table tbody tr {
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 6px 14px;
            margin-bottom: 10px;
            background: var(--card);
        }

        .workers-tab .data-table td {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 9px 0;
            border: 0;
            border-bottom: 1px solid var(--border);
            text-align: right;
            font-size: 13px;
            word-break: break-word;
        }

        .workers-tab .data-table td:last-child {
            border-bottom: 0;
        }

        .workers-tab .data-table td::before {
            content: attr(data-label);
            flex-shrink: 0;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            opacity: .5;
            text-align: left;
        }

        .workers-tab .data-table td.cell-primary {
            justify-content: flex-start;
            text-align: left;
            font-size: 15px;
            font-weight: 800;
        }

        .workers-tab .data-table td.cell-primary::before {
            display: none;
        }

        .workers-tab .data-table td.empty-cell {
            display: block;
            text-align: center;
            padding: 0;
        }

        .workers-tab .data-table td.empty-cell::before {
            display: none;
        }

        .workers-tab .data-table tbody tr:has(td.empty-cell) {
            border: 0;
            padding: 0;
            background: transparent;
        }
    }
</style>

<div class="dash-main workers-tab">

    <div class="toolbar" style="margin-bottom: 0;">
        <div style="font-size:13px;font-weight:600;opacity:.6;">
            {{ $workers->count() }} {{ Str::plural('worker', $workers->count()) }}
        </div>
        <div style="display:flex;gap:8px;">
            <a href="{{ url('/business/workers') }}" class="btn-pill">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round"
                    style="display:inline;vertical-align:middle;margin-right:4px;">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                Manage All Workers
            </a>
        </div>
    </div>

    <div class="card-panel">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Branch</th>
                    <th>Status</th>
                    <th>Joined</th>
                </tr>
            </thead>
            <tbody>
                @php
                    // Single query for all open shifts (avoids N+1)
                    $openShiftUserIds = \App\Models\ShiftLog::where('status', 'open')
                        ->whereIn('user_id', $workers->pluck('id'))
                        ->pluck('user_id')
                        ->unique()
                        ->toArray();
                @endphp
                @forelse ($workers as $worker)
                    @php $isOnShift = in_array($worker->id, $openShiftUserIds); @endphp
                    <tr>
                        <td class="cell-primary" data-label="Name">
                            <a href="{{ url('/business/workers') }}?worker={{ $worker->id }}"
                                style="color:var(--brown);text-decoration:none;">
                                {{ $worker->name }}
                            </a>
                        </td>
                        <td data-label="Email">{{ $worker->email ?? '—' }}</td>
                        <td data-label="Role">
                            <span class="badge {{ $worker->role === \App\Models\User::ROLE_MANAGER ? 'blue' : 'gray' }}">
                                {{ $worker->role === \App\Models\User::ROLE_MANAGER ? 'Manager' : 'Staff' }}
                            </span>
                        </td>
                        <td data-label="Branch">{{ $worker->branch?->name ?? '—' }}</td>
                        <td data-label="Status">
                            <span class="badge {{ $isOnShift ? 'green' : 'gray' }}">
                                {{ $isOnShift ? 'On Shift' : 'Off Duty' }}
                            </span>
                        </td>
                        <td data-label="Joined">{{ $worker->created_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-cell">
                            <div class="empty-state-icon" style="padding:32px 20px">
                                <div style="font-size:36px;margin-bottom:10px">👥</div>
                                <div style="font-size:15px;font-weight:700;color:var(--text);margin-bottom:4px">No workers
                                    yet</div>
                                <div style="font-size:12px;color:var(--text-2);margin-bottom:16px">Assign workers to this
                                    branch to track attendance and manage shifts.</div>
                                <div
                                    style="padding:12px;background:var(--bg);border-radius:8px;text-align:left;font-size:11px;color:var(--text-2);max-width:280px;margin:0 auto">
                                    <div style="font-weight:600;color:var(--text);margin-bottom:6px">👤 Worker features:
                                    </div>
                                    <div style="margin-bottom:3px">• Clock in / clock out tracking</div>
                                    <div style="margin-bottom:3px">• Shift logs and attendance history</div>
                                    <div style="margin-bottom:3px">• Pay rate management and payslips</div>
                                    <div>• Peer reviews and goal tracking</div>
                                </div>
                                <a href="{{ url('/business/workers') }}" class="btn-pill"
                                    style="margin-top:14px;display:inline-block;text-decoration:none">+ Add Workers</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>