<aside class="left-sidebar">
    <div>
        <div class="brand-logo align-items-center justify-content-between mb-2">
            <a href="#" class="text-nowrap logo-img">
                <img src="{{ asset('/assets/images/logos/logojpjxx.jpg') }}" alt="" class="rounded mt-1"
                    width="220" height="70" />
            </a>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-6"></i>
            </div>
        </div>

        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            {{-- ADMIN --}}
            @if (Auth::check())
                @if (Auth::user()->user_type == 1)
                    <ul id="sidebarnav">
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('dashboard') }}">
                                <i class="ti ti-dashboard"></i>
                                <span class="hide-menu">Dashboard</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="#">
                                <i class="ti ti-chart-bar"></i>
                                <span class="hide-menu">Analytics & Reports</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('schedule.pickups.day') }}">
                                <i class="ti ti-calendar-event"></i>
                                <span class="hide-menu">Schedule Pickups</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('user.management') }}">
                                <i class="ti ti-users"></i>
                                <span class="hide-menu">User Management</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="#">
                                <i class="ti ti-settings"></i>
                                <span class="hide-menu">Manage Complaints</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('pickup.requests') }}">
                                <i class="ti ti-list-check"></i>
                                <span class="hide-menu">Pickup Requests</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('transactions.view') }}">
                                <i class="ti ti-currency-dollar"></i>
                                <span class="hide-menu">Transactions</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="#">
                                <i class="ti ti-link"></i>
                                <span class="hide-menu">Blockchain Ledger</span>
                            </a>
                        </li>
                    </ul>
                @endif
            @endif

            {{-- WASTE COLLECTORS --}}
            @if (false)
                <ul id="sidebarnav">
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('dashboard') }}">
                            <i class="ti ti-dashboard"></i>
                            <span class="hide-menu">Dashboard</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link" href="#">
                            <i class="ti ti-calendar-event"></i>
                            <span class="hide-menu">Schedule Pickup</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link" href="#">
                            <i class="ti ti-list-check"></i>
                            <span class="hide-menu">Pickup Requests</span>
                        </a>
                    </li>
                </ul>
            @endif

            {{-- CUTOMERS & RESIDENTS --}}
            @if (Auth::check())
                @if (Auth::user()->user_type == 3)
                    <ul id="sidebarnav">
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('dashboard') }}">
                                <i class="ti ti-dashboard"></i>
                                <span class="hide-menu">Dashboard</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('schedule.pickup') }}">
                                <i class="ti ti-calendar-event"></i>
                                <span class="hide-menu">Schedule Pickup</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="#">
                                <i class="ti ti-map-pin"></i>
                                <span class="hide-menu">Track Truck</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="#">
                                <i class="ti ti-book"></i>
                                <span class="hide-menu">Segregation Tips</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('recycle.exchange') }}">
                                <i class="ti ti-recycle"></i>
                                <span class="hide-menu">Recycling Exchange</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="#">
                                <i class="ti ti-gift"></i>
                                <span class="hide-menu">Rewards</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="#">
                                <i class="ti ti-message-report"></i>
                                <span class="hide-menu">Complaints</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="#">
                                <i class="ti ti-alert-triangle"></i>
                                <span class="hide-menu">Emergency Alerts</span>
                            </a>
                        </li>
                    </ul>
                @endif
            @endif

        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
