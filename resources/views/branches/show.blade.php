@extends('layouts.sidebar')

@section('title', $branch->name)
@section('subtitle', ($branch->location ? $branch->location . ' · ' : '') . ucfirst($branch->status))

@section('styles')
    @media (max-width: 768px) {
    /* Stack: Businesses list on top, branch content below */
    .master-detail {
    display: grid !important;
    grid-template-columns: minmax(0, 1fr) !important;
    gap: 14px !important;
    }
    .master-detail > * { min-width: 0; }

    /* Businesses list becomes a horizontal scroller */
    .master-detail .biz-list {
    display: flex !important;
    flex-direction: row !important;
    gap: 8px;
    overflow-x: auto;
    padding: 10px 12px;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    }
    .master-detail .biz-list::-webkit-scrollbar { display: none; }
    .master-detail .biz-list a {
    flex: 0 0 auto;
    white-space: nowrap;
    width: auto !important;
    border: 1px solid var(--border);
    border-radius: 999px;
    padding: 8px 14px !important;
    }

    /* Header wraps instead of overflowing */
    .master-detail .detail-header {
    flex-wrap: wrap;
    gap: 8px;
    }

    /* Tabs scroll sideways instead of being clipped */
    .master-detail .filter-tabs {
    display: flex !important;
    flex-wrap: nowrap !important;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    }
    .master-detail .filter-tabs::-webkit-scrollbar { display: none; }
    .master-detail .filter-tabs .tab {
    flex: 0 0 auto;
    white-space: nowrap;
    }
    }
@endsection

@section('content')

    @include('partials._breadcrumbs', [
        'bc_items' => [
            ['label' => 'Branches', 'url' => route('branches')],
            ['label' => $branch->name, 'url' => route('branches.show', $branch) . '?tab=' . $tab],
            ['label' => ucfirst($tab), 'url' => null],
        ]
    ])

    <div class="master-detail">
        <div class="widget">
            <div class="widget-head">
                <h2>Businesses</h2>
            </div>
            <div class="biz-list">
                @foreach ($all_branches as $b)
                    <a href="{{ route('branches.show', $b) }}?tab={{ $tab }}"
                        class="{{ $b->id === $branch->id ? 'active' : '' }}">
                        <span class="dot {{ $b->status === 'active' ? 'green' : 'red' }}"></span>
                        {{ $b->name }}
                    </a>
                @endforeach
            </div>
        </div>

        <div>
            <div class="detail-header">
                <h2>{{ $branch->name }}</h2>
                <span
                    class="badge {{ $branch->status === 'active' ? 'green' : 'gray' }}">{{ ucfirst($branch->status) }}</span>
                <span class="detail-sub">{{ $branch->location ?? '' }}</span>
            </div>

            <div class="filter-tabs">
                <a href="{{ route('branches.show', $branch) }}?tab=analytics"
                    class="tab {{ $tab === 'analytics' ? 'active' : '' }}">Analytics</a>
                <a href="{{ route('branches.show', $branch) }}?tab=recipe"
                    class="tab {{ $tab === 'recipe' ? 'active' : '' }}">Recipe</a>
                <a href="{{ route('branches.show', $branch) }}?tab=workers"
                    class="tab {{ $tab === 'workers' ? 'active' : '' }}">Workers</a>
                <a href="{{ route('branches.show', $branch) }}?tab=logistics"
                    class="tab {{ $tab === 'logistics' ? 'active' : '' }}">Logistics</a>
            </div>

            @include('branches.tabs.' . $tab)
        </div>
    </div>

@endsection