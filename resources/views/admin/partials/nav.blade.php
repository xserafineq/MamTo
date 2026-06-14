@php
    $pendingApprovalsCount = \App\Models\Auction::where('approved', false)->count();
@endphp

<nav class="admin-panel-nav">
    <a
        href="{{ route('admin.panel') }}"
        class="admin-panel-nav-link {{ request()->routeIs('admin.panel') ? 'is-active' : '' }}"
    >
        Start
    </a>
    <a
        href="{{ route('admin.auctions.index') }}"
        class="admin-panel-nav-link {{ request()->routeIs('admin.auctions.*') ? 'is-active' : '' }}"
    >
        Aukcje
    </a>
    <a
        href="{{ route('admin.approvals.index') }}"
        class="admin-panel-nav-link {{ request()->routeIs('admin.approvals.*') ? 'is-active' : '' }}"
    >
        Do akceptacji
        @if($pendingApprovalsCount > 0)
            <span class="admin-panel-nav-badge">{{ $pendingApprovalsCount }}</span>
        @endif
    </a>
    <a
        href="{{ route('admin.administrators.index') }}"
        class="admin-panel-nav-link {{ request()->routeIs('admin.administrators.*') ? 'is-active' : '' }}"
    >
        Inni administratorzy
    </a>
    <a
        href="{{ route('admin.categories.index') }}"
        class="admin-panel-nav-link {{ request()->routeIs('admin.categories.*') ? 'is-active' : '' }}"
    >
        Kategorie
    </a>
</nav>
