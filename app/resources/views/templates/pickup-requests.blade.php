@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <x-flash-messages />
        <div class="row">
            <div class="col-md-3 mb-0">
                <div class="card border-0 shadow-lg rounded-3 py-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-list fs-10 text-primary me-2"></i>
                                <h6 class="text-muted mb-0">All Requests</h6>
                            </div>
                        </div>
                        <h3 class="fw-bold float-end fs-5">{{ number_format($allRequestsCounter) }}</h3>
                    </div>
                </div>
            </div>

            <!-- Total Streets Counter -->
            <div class="col-md-3 mb-0">
                <div class="card border-0 shadow-lg rounded-3 py-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-clock fs-10 text-primary me-2"></i>
                                <h6 class="text-muted mb-0">Pendings</h6>
                            </div>
                        </div>
                        <h3 class="fw-bold float-end fs-5">{{ number_format($pendingRequestsCounter) }}</h3>
                    </div>
                </div>
            </div>

            <!-- Total Pickups Counter -->
            <div class="col-md-3 mb-0">
                <div class="card border-0 shadow-lg rounded-3 py-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-circle-check text-success fs-10 text-primary me-2"></i>
                                <h6 class="text-muted mb-0">Accepted</h6>
                            </div>
                        </div>
                        <h3 class="fw-bold float-end fs-5">{{ number_format($acceptedRequestsCounter) }}</h3>
                    </div>
                </div>
            </div>

            <!-- Today's Collection Counter -->
            <div class="col-md-3 mb-0">
                <div class="card border-0 shadow-lg rounded-3 py-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-checklist fs-10 text-primary me-2"></i>
                                <h6 class="text-muted mb-0">Completed</h6>
                            </div>
                        </div>
                        <h3 class="fw-bold float-end fs-5">{{  number_format($compltedRequestsCounter)}}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <!--  Row 1 -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-header">
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-pick-up-list" type="button" role="tab"
                                        aria-controls="nav-home" aria-selected="true">New Pick-Up Requests</button>
                                    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-accepted-requests" type="button" role="tab"
                                        aria-controls="nav-profile" aria-selected="false">Accepted Requests</button>
                                    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-completed-requests" type="button" role="tab"
                                        aria-controls="nav-profile" aria-selected="false">Complete Requests</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="tab-content mt-0" id="nav-tabContent">
                                    <div class="tab-pane fade show active" id="nav-pick-up-list" role="tabpanel"
                                        aria-labelledby="nav-profile-tab">
                                        <div class="table-responsive mt-0">
                                            <table class="table mb-0 text-nowrap varient-table align-middle fs-3">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" class="px-0 text-muted">
                                                            S/N
                                                        </th>
                                                        <th scope="col" class="px-0 text-muted">
                                                            Resident
                                                        </th>
                                                        <th scope="col" class="px-0 text-muted">
                                                            Pick-Up Date
                                                        </th>
                                                        {{-- <th scope="col" class="px-0 text-muted">
                                                            Pick-Up Time
                                                        </th> --}}
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
                                                    @foreach ($incompeleteRequests as $incomplete)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $incomplete->name }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($incomplete->pickupDate)->format('M d, Y') }}
                                                                |
                                                                <strong>{{ \Carbon\Carbon::parse($incomplete->pickupTime)->format('H:i A') }}</strong>
                                                            </td>
                                                            <td>{{ strtoupper($incomplete->frequency) }}</td>
                                                            <td>{{ $incomplete->pickupLocation }}</td>
                                                            <td>
                                                                @if ($incomplete->status == 'pending')
                                                                    <span class="spinner-border spinner-border-sm"
                                                                        role="status" aria-hidden="true"></span>
                                                                    {{ $incomplete->status . ' ... ' }}
                                                                @else
                                                                    {{ $incomplete->status }}
                                                                @endif
                                                            </td>
                                                            @php
                                                                $encryptedId = Crypt::encrypt($incomplete->id);
                                                            @endphp
                                                            <td class="text-center">....</td>
                                                            <td class="text-center"><a
                                                                    href="{{ route('view.request', $encryptedId) }}"
                                                                    class="btn btn-primary btn-sm" role="button"><i
                                                                        class="ti ti-eye"></i></a></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            @if (count($incompeleteRequests) == 0)
                                                <span class="p-2">No requests found incomplete!</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="nav-accepted-requests" role="tabpanel"
                                        aria-labelledby="nav-profile-tab">
                                        <div class="table-responsive mt-0">
                                            <table class="table mb-0 text-nowrap varient-table align-middle fs-3">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" class="px-0 text-muted">
                                                            S/N
                                                        </th>
                                                        <th scope="col" class="px-0 text-muted">
                                                            Resident
                                                        </th>
                                                        <th scope="col" class="px-0 text-muted">
                                                            Pick-Up Date
                                                        </th>
                                                        {{-- <th scope="col" class="px-0 text-muted">
                                                            Pick-Up Time
                                                        </th> --}}
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
                                                    @foreach ($acceptedRequests as $accepted)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $accepted->name }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($accepted->pickupDate)->format('M d, Y') }}
                                                                |
                                                                <strong>{{ \Carbon\Carbon::parse($accepted->pickupTime)->format('H:i A') }}</strong>
                                                            </td>
                                                            <td>{{ strtoupper($accepted->frequency) }}</td>
                                                            <td>{{ $accepted->pickupLocation }}</td>
                                                            <td>
                                                                <i class="ti ti-circle-check text-success"></i>
                                                                {{ $accepted->status }}
                                                            </td>
                                                            @php
                                                                $encryptedId = Crypt::encrypt($accepted->id);
                                                            @endphp
                                                            <td class="text-center">....</td>
                                                            <td class="text-center"><a
                                                                    href="{{ route('view.request', $encryptedId) }}"
                                                                    class="btn btn-primary btn-sm" role="button"><i
                                                                        class="ti ti-eye"></i></a></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            @if (count($acceptedRequests) == 0)
                                                <span class="p-2 mt-5">No requests found accepted!</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="nav-completed-requests" role="tabpanel"
                                        aria-labelledby="nav-profile-tab">
                                        <div class="table-responsive mt-0">
                                            <table class="table mb-0 text-nowrap varient-table align-middle fs-3">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" class="px-0 text-muted">
                                                            S/N
                                                        </th>
                                                        <th scope="col" class="px-0 text-muted">
                                                            Resident
                                                        </th>
                                                        <th scope="col" class="px-0 text-muted">
                                                            Pick-Up Date
                                                        </th>
                                                        {{-- <th scope="col" class="px-0 text-muted">
                                                            Pick-Up Time
                                                        </th> --}}
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
                                                    @foreach ($completedRequests as $completed)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $completed->name }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($completed->pickupDate)->format('M d, Y') }}
                                                                |
                                                                <strong>{{ \Carbon\Carbon::parse($completed->pickupTime)->format('H:i A') }}</strong>
                                                            </td>
                                                            <td>{{ strtoupper($completed->frequency) }}</td>
                                                            <td>{{ $completed->pickupLocation }}</td>
                                                            <td>
                                                                <i class="ti ti-checklist"></i>
                                                                {{ $completed->status }}
                                                            </td>
                                                            @php
                                                                $encryptedId = Crypt::encrypt($completed->id);
                                                            @endphp
                                                            <td class="text-center">
                                                                @if ($completed->status == 'completed')
                                                                    <i class="ti ti-checklist"></i>
                                                                    {{ __('Paid') }}
                                                                @else
                                                                    {{ __('....') }}
                                                                @endif
                                                            </td>
                                                            <td class="text-center"><a
                                                                    href="{{ route('view.request', $encryptedId) }}"
                                                                    class="btn btn-primary btn-sm" role="button"><i
                                                                        class="ti ti-eye"></i></a></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            @if (count($completedRequests) == 0)
                                                <span class="p-2 mt-5">No requests found completed!</span>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
