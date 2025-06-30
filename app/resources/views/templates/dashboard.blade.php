@extends('layouts.app')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@section('content')
    @if (Auth::check())
        @if (Auth::user()->user_type == 3)
            <div class="container-fluid">
                <x-flash-messages />
                <div class="card p-3 rounded">
                    <div class="tab-content mt-0" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-pick-up-list" role="tabpanel"
                            aria-labelledby="nav-profile-tab">
                            <h4 class="fs-5">My Requests Summary</h4>
                            <div class="table-responsive mt-0">
                                <table class="table mb-0 text-nowrap varient-table align-middle fs-3">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="px-0 text-muted">
                                                S/N
                                            </th>
                                            <th scope="col" class="px-0 text-muted">
                                                Pick-Up Date
                                            </th>
                                            <th scope="col" class="px-0 text-muted">
                                                Pick-Up Time
                                            </th>
                                            <th>
                                                Frequency
                                            </th>
                                            <th>
                                                Location
                                            </th>
                                            <th>
                                                Status
                                            </th>
                                            <th>
                                                Payment
                                            </th>
                                            <th>
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($myPickUpRequests as $mypicks)
                                            <tr>
                                                <td>
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($mypicks->pickup_date)->format('M d, Y') }}
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($mypicks->preferred_time)->format('h:i A') }}
                                                </td>
                                                <td class="text-center">
                                                    <strong>{{ strtoupper($mypicks->frequency) }}</strong>
                                                </td>
                                                <td class="text-start">
                                                    {{ $mypicks->location }}
                                                </td>

                                                <td class="text-center">
                                                    @if ($mypicks->status == 'pending')
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
                                                        {{ $mypicks->status . ' ... ' }}
                                                    @elseif($mypicks->status == 'accepted')
                                                        <i class="ti ti-circle-check text-success"></i>
                                                        {{ $mypicks->status }}
                                                    @else
                                                        <i class="ti ti-circle-check text-success"></i>
                                                        {{ $mypicks->status }}
                                                    @endif
                                                </td>
                                                @php
                                                    $encryptedId = Crypt::encrypt($mypicks->id);
                                                @endphp
                                                <td class="text-center"><strong>
                                                        @if ($mypicks->status == 'completed')
                                                            <span class="btn btn-primary btn-sm"><i
                                                                    class="ti ti-checklist text-success"></i>
                                                                {{ __('Paid ...') }} </span>
                                                        @else
                                                            {{ __('....') }}
                                                        @endif
                                                    </strong></td>
                                                <td class="text-center">
                                                    <a href="{{ route('my.request.details', $encryptedId) }}"
                                                        class="btn btn-primary btn-sm"><i class="ti ti-eye"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if (count($myPickUpRequests) == 0)
                                <span class="p-2 mt-2 mb-2 fs-3">No pick-up request for me here!</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card mt-3 p-3 rounded">
                    <h4 class="fs-5">Requests Analysis Summary</h4>
                    <div class="table-responsive mt-4">
                        <canvas id="pickupChart" width="400" height="200"></canvas>
                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                        <script>
                            const ctx = document.getElementById('pickupChart').getContext('2d');
                            const pickupChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: @json($months),
                                    datasets: [{
                                        label: 'Pickup Requests per Month',
                                        data: @json($totals),
                                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                                        borderColor: 'rgba(54, 162, 235, 1)',
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            title: {
                                                display: true,
                                                text: 'Total Requests'
                                            }
                                        },
                                        x: {
                                            title: {
                                                display: true,
                                                text: 'Month'
                                            }
                                        }
                                    }
                                }
                            });
                        </script>
                    </div>
                </div>
            </div>
        @endif

        @if (Auth::user()->user_type == 1)
            <div class="container-fluid">
                <x-flash-messages />
                <div class="row text-center">
                    <!-- Residents Counter -->
                    <a href="{{ route('residents.view') }}" class="col-md-3 mb-4">
                        <div class="card border-0 shadow-lg rounded-3 py-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-users fs-10 text-primary me-2"></i>
                                        <h6 class="text-muted mb-0">Residents</h6>
                                    </div>
                                </div>
                                <h3 class="fw-bold float-end fs-5">{{ number_format($residentsCounter) }}</h3>
                            </div>
                        </div>
                    </a>

                    <!-- Total Streets Counter -->
                    <a href="#" class="col-md-3 mb-4">
                        <div class="card border-0 shadow-lg rounded-3 py-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-road fs-10 text-success me-2"></i>
                                        <h6 class="text-muted mb-0">Streets</h6>
                                    </div>
                                </div>
                                <h3 class="fw-bold float-end fs-5">{{ number_format($streetCounter) }}</h3>
                            </div>
                        </div>
                    </a>

                    <!-- Total Pickups Counter -->
                    <a href="{{ route('pickup.requests') }}" class="col-md-3 mb-4">
                        <div class="card border-0 shadow-lg rounded-3 py-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-truck fs-10 text-warning me-2"></i>
                                        <h6 class="text-muted">Pickups</h6>
                                    </div>
                                </div>
                                <h3 class="fw-bold float-end fs-5">{{ number_format($requestsCounter) }}</h3>
                            </div>
                        </div>
                    </a>

                    <!-- Today's Collection Counter -->
                    <a href="{{ route('transactions.view') }}" class="col-md-3 mb-4">
                        <div class="card border-0 shadow-lg rounded-3 py-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-recycle fs-10 text-danger me-2"></i>
                                        <h6 class="text-muted">Collection</h6>
                                    </div>
                                </div>
                                <h3 class="fw-bold float-end fs-5" style="color: orange;">TZS
                                    {{ number_format($collectionEarlings, 2) }}</h3>
                            </div>
                        </div>
                    </a>
                </div>

                <!--  Row 1 -->
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card w-100">
                            <div class="card-body">
                                <div class="d-md-flex align-items-center">
                                    <div>
                                        <h4 class="card-title">Pickup Requests Overview <strong
                                                class="text-primary">({{ $startOfWeek->format('M d, Y') . ' ' . ' - ' . ' ' . $endOfWeek->format('M d, Y') }})</strong>
                                        </h4>
                                    </div>
                                </div>
                                <canvas id="pickupChart" class="mt-4 mx-n6" width="100%" height="55"></canvas>
                                {{-- <div id="sales-overview" ></div> --}}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card overflow-hidden">
                            <div class="card-body pb-0">
                                <div class="d-flex align-items-start">
                                    <div>
                                        <h4 class="card-title">Weekly Stats</h4>
                                    </div>
                                </div>

                                @foreach ($weeklyStats as $stat)
                                    <div class="mt-4 pb-3 d-flex align-items-center">
                                        <span class="btn btn-primary rounded-circle round-48 hstack justify-content-center">
                                            <i class="ti ti-user fs-6"></i>
                                        </span>
                                        <div class="ms-3">
                                            <h5 class="mb-0 fw-bolder fs-4">{{ $stat->names }} </h5>
                                            <span class="text-muted fs-3">{{ $stat->location }}</span>
                                        </div>
                                        <div class="ms-auto">
                                            <span
                                                class="badge bg-secondary-subtle text-muted">{{ number_format($stat->totalRequests) }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-md-flex align-items-center">
                                    <div>
                                        <h4 class="card-title">Overall Residents Performance</h4>
                                    </div>
                                </div>
                                <div class="table-responsive mt-4">
                                    <table class="table mb-0 text-nowrap varient-table align-middle fs-3">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="px-0 text-muted">
                                                    Info
                                                </th>
                                                <th scope="col" class="px-0 text-muted">
                                                    Requests
                                                </th>
                                                <th scope="col" class="px-0 text-muted">
                                                    Date Created
                                                </th>
                                                <th scope="col" class="px-0 text-muted">
                                                    Amount
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($allStats as $itm)
                                                <tr>
                                                    <td class="px-0">
                                                        <div class="d-flex align-items-center">
                                                            <span
                                                                class="btn btn-primary rounded-circle round-48 hstack justify-content-center">
                                                                <i class="ti ti-user fs-6"></i>
                                                            </span>
                                                            <div class="ms-3">
                                                                <h6 class="mb-0 fw-bolder">{{ $itm->names }}</h6>
                                                                <span class="text-muted">{{ $itm->location }}</span>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td class="px-0">{{ number_format($itm->totalRequests) }}</td>
                                                    <td class="px-0">
                                                        <span
                                                            class="badge bg-info">{{ \Carbon\Carbon::parse($itm->dueDate)->format('M d, Y') }}</span>
                                                    </td>
                                                    <td class="px-0 text-dark fw-medium">
                                                        {{ $itm->currency }} {{ number_format($itm->totalPaid, 2) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <!-- Card -->
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-0">Recent Complaints</h4>
                            </div>
                            <div class="comment-widgets scrollable mb-2 common-widget" style="height: 465px"
                                data-simplebar="">
                                <!-- Comment Row -->

                                <div class="d-flex flex-row comment-row border-bottom p-3 gap-3">
                                    @if (false)
                                        <div>
                                            <span
                                                class="btn btn-primary rounded-circle round-48 hstack justify-content-center">
                                                <i class="ti ti-user fs-6"></i>
                                            </span>
                                        </div>
                                    @endif
                                    <div class="comment-text w-100">
                                        @if (false)
                                            <h6 class="fw-medium">James Anderson</h6>
                                            <p class="mb-1 fs-2 text-muted">
                                                Lorem Ipsum is simply dummy text of the printing and
                                                type etting industry
                                            </p>
                                        @endif

                                        @if (true)
                                            <span class="p-2">No Complaints availlable</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-0">Pickup Schedules</h4>
                                <div class="d-flex align-items-center">
                                    <table class="table table-borderless">
                                        <tbody>
                                            @foreach ($schedules as $dule)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $dule->area }}</td>
                                                    <td class="fw-medium">{{ \Carbon\Carbon::parse($dule->day)->format('M d, Y') . ' ' . ' at ' . ' ' . \Carbon\Carbon::parse($dule->time)->format('H:i A') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        @endif
    @endif

    <script>
        const ctx = document.getElementById('pickupChart').getContext('2d');

        const pickupChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($labels),
                datasets: [{
                        label: 'Pickup Requests',
                        data: @json($requests),
                        backgroundColor: 'rgba(54, 162, 235, 0.7)'
                    },
                    {
                        label: 'Residents',
                        data: @json($residents),
                        backgroundColor: 'rgba(75, 192, 192, 0.7)'
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: false,
                        text: 'Weekly Waste Pickup Requests vs Residents'
                    },
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Count'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Day'
                        }
                    }
                }
            }
        });
    </script>

@endsection
