@extends('layouts.app')
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
                                                <td class="text-center">
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
                    <div class="col-md-3 mb-4">
                        <div class="card border-0 shadow-lg rounded-3 py-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-users fs-10 text-primary me-2"></i>
                                        <h6 class="text-muted mb-0">Residents</h6>
                                    </div>
                                </div>
                                <h3 class="fw-bold float-end fs-5">2,190</h3>
                            </div>
                        </div>
                    </div>

                    <!-- Total Streets Counter -->
                    <div class="col-md-3 mb-4">
                        <div class="card border-0 shadow-lg rounded-3 py-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-road fs-10 text-success me-2"></i>
                                        <h6 class="text-muted mb-0">Streets</h6>
                                    </div>
                                </div>
                                <h3 class="fw-bold float-end fs-5">21</h3>
                            </div>
                        </div>
                    </div>

                    <!-- Total Pickups Counter -->
                    <div class="col-md-3 mb-4">
                        <div class="card border-0 shadow-lg rounded-3 py-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-truck fs-10 text-warning me-2"></i>
                                        <h6 class="text-muted">Pickups</h6>
                                    </div>
                                </div>
                                <h3 class="fw-bold float-end fs-5">9</h3>
                            </div>
                        </div>
                    </div>

                    <!-- Today's Collection Counter -->
                    <div class="col-md-3 mb-4">
                        <div class="card border-0 shadow-lg rounded-3 py-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-recycle fs-10 text-danger me-2"></i>
                                        <h6 class="text-muted">Collection</h6>
                                    </div>
                                </div>
                                <h3 class="fw-bold float-end fs-5" style="color: orange;">$ 200.00</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!--  Row 1 -->
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card w-100">
                            <div class="card-body">
                                <div class="d-md-flex align-items-center">
                                    <div>
                                        <h4 class="card-title">Pickup Requests Overview</h4>
                                    </div>
                                </div>
                                <div id="sales-overview" class="mt-4 mx-n6"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card overflow-hidden">
                            <div class="card-body pb-0">
                                <div class="d-flex align-items-start">
                                    <div>
                                        <h4 class="card-title">Weekly Stats</h4>
                                        {{-- <p class="card-subtitle">Average sales</p> --}}
                                    </div>
                                    <div class="ms-auto">
                                        <div class="dropdown">
                                            <a href="javascript:void(0)" class="text-muted" id="year1-dropdown"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ti ti-dots fs-7"></i>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="year1-dropdown">
                                                <li>
                                                    <a class="dropdown-item" href="javascript:void(0)">Action</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="javascript:void(0)">Another action</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="javascript:void(0)">Something else
                                                        here</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 pb-3 d-flex align-items-center">
                                    <span class="btn btn-primary rounded-circle round-48 hstack justify-content-center">
                                        <i class="ti ti-shopping-cart fs-6"></i>
                                    </span>
                                    <div class="ms-3">
                                        <h5 class="mb-0 fw-bolder fs-4">Top Sales</h5>
                                        <span class="text-muted fs-3">Johnathan Doe</span>
                                    </div>
                                    <div class="ms-auto">
                                        <span class="badge bg-secondary-subtle text-muted">+68%</span>
                                    </div>
                                </div>
                                <div class="py-3 d-flex align-items-center">
                                    <span class="btn btn-warning rounded-circle round-48 hstack justify-content-center">
                                        <i class="ti ti-star fs-6"></i>
                                    </span>
                                    <div class="ms-3">
                                        <h5 class="mb-0 fw-bolder fs-4">Best Seller</h5>
                                        <span class="text-muted fs-3">MaterialPro Admin</span>
                                    </div>
                                    <div class="ms-auto">
                                        <span class="badge bg-secondary-subtle text-muted">+68%</span>
                                    </div>
                                </div>
                                <div class="py-3 d-flex align-items-center">
                                    <span class="btn btn-success rounded-circle round-48 hstack justify-content-center">
                                        <i class="ti ti-message-dots fs-6"></i>
                                    </span>
                                    <div class="ms-3">
                                        <h5 class="mb-0 fw-bolder fs-4">Most Commented</h5>
                                        <span class="text-muted fs-3">Ample Admin</span>
                                    </div>
                                    <div class="ms-auto">
                                        <span class="badge bg-secondary-subtle text-muted">+68%</span>
                                    </div>
                                </div>
                                <div class="pt-3 mb-7 d-flex align-items-center">
                                    <span class="btn btn-secondary rounded-circle round-48 hstack justify-content-center">
                                        <i class="ti ti-diamond fs-6"></i>
                                    </span>
                                    <div class="ms-3">
                                        <h5 class="mb-0 fw-bolder fs-4">Top Budgets</h5>
                                        <span class="text-muted fs-3">Sunil Joshi</span>
                                    </div>
                                    <div class="ms-auto">
                                        <span class="badge bg-secondary-subtle text-muted">+15%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-md-flex align-items-center">
                                    <div>
                                        <h4 class="card-title">Overall Residents Performance</h4>
                                        {{-- <p class="card-subtitle">
                                    Ample Admin Vs Pixel Admin
                                </p> --}}
                                    </div>
                                    <div class="ms-auto mt-3 mt-md-0">
                                        <select class="form-select theme-select border-0"
                                            aria-label="Default select example">
                                            <option value="1">March 2025</option>
                                            <option value="2">March 2025</option>
                                            <option value="3">March 2025</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="table-responsive mt-4">
                                    <table class="table mb-0 text-nowrap varient-table align-middle fs-3">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="px-0 text-muted">
                                                    Assigned
                                                </th>
                                                <th scope="col" class="px-0 text-muted">Name</th>
                                                <th scope="col" class="px-0 text-muted">
                                                    Priority
                                                </th>
                                                <th scope="col" class="px-0 text-muted text-end">
                                                    Budget
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="px-0">
                                                    <div class="d-flex align-items-center">
                                                        <img src="./assets/images/profile/user-3.jpg"
                                                            class="rounded-circle" width="40" alt="flexy" />
                                                        <div class="ms-3">
                                                            <h6 class="mb-0 fw-bolder">Sunil Joshi</h6>
                                                            <span class="text-muted">Web Designer</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-0">Elite Admin</td>
                                                <td class="px-0">
                                                    <span class="badge bg-info">Low</span>
                                                </td>
                                                <td class="px-0 text-dark fw-medium text-end">
                                                    $3.9K
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-0">
                                                    <div class="d-flex align-items-center">
                                                        <img src="./assets/images/profile/user-5.jpg"
                                                            class="rounded-circle" width="40" alt="flexy" />
                                                        <div class="ms-3">
                                                            <h6 class="mb-0 fw-bolder">
                                                                Andrew McDownland
                                                            </h6>
                                                            <span class="text-muted">Project Manager</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-0">Real Homes WP Theme</td>
                                                <td class="px-0">
                                                    <span class="badge text-bg-primary">Medium</span>
                                                </td>
                                                <td class="px-0 text-dark fw-medium text-end">
                                                    $24.5K
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-0">
                                                    <div class="d-flex align-items-center">
                                                        <img src="./assets/images/profile/user-6.jpg"
                                                            class="rounded-circle" width="40" alt="flexy" />
                                                        <div class="ms-3">
                                                            <h6 class="mb-0 fw-bolder">
                                                                Christopher Jamil
                                                            </h6>
                                                            <span class="text-muted">SEO Manager</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-0">MedicalPro WP Theme</td>
                                                <td class="px-0">
                                                    <span class="badge bg-warning">Hight</span>
                                                </td>
                                                <td class="px-0 text-dark fw-medium text-end">
                                                    $12.8K
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-0">
                                                    <div class="d-flex align-items-center">
                                                        <img src="./assets/images/profile/user-7.jpg"
                                                            class="rounded-circle" width="40" alt="flexy" />
                                                        <div class="ms-3">
                                                            <h6 class="mb-0 fw-bolder">Nirav Joshi</h6>
                                                            <span class="text-muted">Frontend Engineer</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-0">Hosting Press HTML</td>
                                                <td class="px-0">
                                                    <span class="badge bg-danger">Low</span>
                                                </td>
                                                <td class="px-0 text-dark fw-medium text-end">
                                                    $2.4K
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-0">
                                                    <div class="d-flex align-items-center">
                                                        <img src="./assets/images/profile/user-8.jpg"
                                                            class="rounded-circle" width="40" alt="flexy" />
                                                        <div class="ms-3">
                                                            <h6 class="mb-0 fw-bolder">Micheal Doe</h6>
                                                            <span class="text-muted">Content Writer</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-0">Helping Hands WP Theme</td>
                                                <td class="px-0">
                                                    <span class="badge bg-success">Low</span>
                                                </td>
                                                <td class="px-0 text-dark fw-medium text-end">
                                                    $9.3K
                                                </td>
                                            </tr>
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
                                    <div>
                                        <span><img src="./assets/images/profile/user-3.jpg" class="rounded-circle"
                                                alt="user" width="50" /></span>
                                    </div>
                                    <div class="comment-text w-100">
                                        <h6 class="fw-medium">James Anderson</h6>
                                        <p class="mb-1 fs-2 text-muted">
                                            Lorem Ipsum is simply dummy text of the printing and
                                            type etting industry
                                        </p>
                                        <div class="comment-footer mt-2">
                                            <div class="d-flex align-items-center">
                                                <span
                                                    class="
                              badge
                              bg-info-subtle
                              text-info
                              
                            ">Pending</span>
                                                <span class="action-icons">
                                                    <a href="javascript:void(0)" class="ps-3"><i
                                                            class="ti ti-edit fs-5"></i></a>
                                                    <a href="javascript:void(0)" class="ps-3"><i
                                                            class="ti ti-check fs-5"></i></a>
                                                    <a href="javascript:void(0)" class="ps-3"><i
                                                            class="ti ti-heart fs-5"></i></a>
                                                </span>
                                            </div>
                                            <span
                                                class="
                            text-muted
                            ms-auto
                            fw-normal
                            fs-2
                            d-block
                            mt-2
                            text-end
                          ">April
                                                14, 2025</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Comment Row -->
                                <div class="d-flex flex-row comment-row border-bottom active p-3 gap-3">
                                    <div>
                                        <span><img src="./assets/images/profile/user-5.jpg" class="rounded-circle"
                                                alt="user" width="50" /></span>
                                    </div>
                                    <div class="comment-text active w-100">
                                        <h6 class="fw-medium">Michael Jorden</h6>
                                        <p class="mb-1 fs-2 text-muted">
                                            Lorem Ipsum is simply dummy text of the printing and
                                            type setting industry.
                                        </p>
                                        <div class="comment-footer mt-2">
                                            <div class="d-flex align-items-center">
                                                <span
                                                    class="
                              badge
                              bg-success-subtle
                              text-success
                              
                            ">Approved</span>
                                                <span class="action-icons active">
                                                    <a href="javascript:void(0)" class="ps-3"><i
                                                            class="ti ti-edit fs-5"></i></a>
                                                    <a href="javascript:void(0)" class="ps-3"><i
                                                            class="ti ti-circle-x fs-5"></i></a>
                                                    <a href="javascript:void(0)" class="ps-3"><i
                                                            class="ti ti-heart text-danger fs-5"></i></a>
                                                </span>
                                            </div>
                                            <span
                                                class="
                            text-muted
                            ms-auto
                            fw-normal
                            fs-2
                            text-end
                            mt-2
                            d-block
                          ">April
                                                14, 2025</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Comment Row -->
                                <div class="d-flex flex-row comment-row border-bottom p-3 gap-3">
                                    <div>
                                        <span><img src="./assets/images/profile/user-6.jpg" class="rounded-circle"
                                                alt="user" width="50" /></span>
                                    </div>
                                    <div class="comment-text w-100">
                                        <h6 class="fw-medium">Johnathan Doeting</h6>
                                        <p class="mb-1 fs-2 text-muted">
                                            Lorem Ipsum is simply dummy text of the printing and
                                            type setting industry.
                                        </p>
                                        <div class="comment-footer mt-2">
                                            <div class="d-flex align-items-center">
                                                <span
                                                    class="
                              badge
                              bg-danger-subtle
                              text-danger
                              
                            ">Rejected</span>
                                                <span class="action-icons">
                                                    <a href="javascript:void(0)" class="ps-3"><i
                                                            class="ti ti-edit fs-5"></i></a>
                                                    <a href="javascript:void(0)" class="ps-3"><i
                                                            class="ti ti-check fs-5"></i></a>
                                                    <a href="javascript:void(0)" class="ps-3"><i
                                                            class="ti ti-heart fs-5"></i></a>
                                                </span>
                                            </div>
                                            <span
                                                class="
                            text-muted
                            ms-auto
                            fw-normal
                            fs-2
                            d-block
                            mt-2
                            text-end
                          ">April
                                                14, 2025</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Comment Row -->
                                <div class="d-flex flex-row comment-row p-3 gap-3">
                                    <div>
                                        <span><img src="./assets/images/profile/user-4.jpg" class="rounded-circle"
                                                alt="user" width="50" /></span>
                                    </div>
                                    <div class="comment-text w-100">
                                        <h6 class="fw-medium">James Anderson</h6>
                                        <p class="mb-1 fs-2 text-muted">
                                            Lorem Ipsum is simply dummy text of the printing and
                                            type setting industry.
                                        </p>
                                        <div class="comment-footer mt-2">
                                            <div class="d-flex align-items-center">
                                                <span
                                                    class="
                              badge
                              bg-info-subtle
                              text-info
                              
                            ">Pending</span>
                                                <span class="action-icons">
                                                    <a href="javascript:void(0)" class="ps-3"><i
                                                            class="ti ti-edit fs-5"></i></a>
                                                    <a href="javascript:void(0)" class="ps-3"><i
                                                            class="ti ti-check fs-5"></i></a>
                                                    <a href="javascript:void(0)" class="ps-3"><i
                                                            class="ti ti-heart fs-5"></i></a>
                                                </span>
                                            </div>
                                            <span
                                                class="
                            text-muted
                            ms-auto
                            fw-normal
                            fs-2
                            d-block
                            text-end
                            mt-2
                          ">April
                                                14, 2025</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <h4 class="card-title mb-0">Pickup Schedules</h4>
                                    {{-- <select class="form-select w-auto ms-auto">
                                <option selected="">Today</option>
                                <option value="1">Weekly</option>
                            </select> --}}
                                </div>
                                {{-- <div class="d-flex align-items-center flex-row mt-4">
                            <div class="p-2 display-5 text-primary">
                                <i class="ti ti-cloud-snow"></i>
                                <span>73<sup>°</sup></span>
                            </div>
                            <div class="p-2">
                                <h3 class="mb-0">Saturday</h3>
                                <small>Ahmedabad, India</small>
                            </div>
                        </div> --}}
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td>Wind</td>
                                            <td class="fw-medium">ESE 17 mph</td>
                                        </tr>
                                        <tr>
                                            <td>Humidity</td>
                                            <td class="fw-medium">83%</td>
                                        </tr>
                                        <tr>
                                            <td>Pressure</td>
                                            <td class="fw-medium">28.56 in</td>
                                        </tr>
                                        <tr>
                                            <td>Cloud Cover</td>
                                            <td class="fw-medium">78%</td>
                                        </tr>
                                        <tr>
                                            <td>Ceiling</td>
                                            <td class="fw-medium">25760 ft</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
@endsection
