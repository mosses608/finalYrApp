@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <x-flash-messages />
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
                                        aria-controls="nav-home" aria-selected="true"><strong
                                            style="color: #0000FF;">{{ $thisRequests->name }}</strong> | Pick-Up
                                        Requests</button>
                                    {{-- <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-accepted-requests" type="button" role="tab"
                                        aria-controls="nav-profile" aria-selected="false">Accepted Requests</button>
                                    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-completed-requests" type="button" role="tab"
                                        aria-controls="nav-profile" aria-selected="false">Complete Requests</button> --}}
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
                                                        {{-- <th scope="col" class="px-0 text-muted">
                                                            S/N
                                                        </th> --}}
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
                                                        {{-- <th>
                                                            Action
                                                        </th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {{-- @foreach ($acceptedRequests as $accepted) --}}
                                                    <tr>
                                                        {{-- <td>{{ $loop->iteration }}</td> --}}
                                                        <td>{{ $thisRequests->name }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($thisRequests->pickupDate)->format('M d, Y') }}
                                                            |
                                                            <strong>{{ \Carbon\Carbon::parse($thisRequests->pickupTime)->format('H:i A') }}</strong>
                                                        </td>
                                                        <td>{{ strtoupper($thisRequests->frequency) }}</td>
                                                        <td>{{ $thisRequests->pickupLocation }}</td>
                                                        <td>
                                                            @if ($thisRequests->status == 'pending')
                                                                <span class="spinner-border spinner-border-sm"
                                                                    role="status" aria-hidden="true"></span>
                                                                {{ $thisRequests->status . ' ... ' }}
                                                            @elseif($thisRequests->status == 'accepted')
                                                                <i class="ti ti-circle-check text-success"></i>
                                                                {{ $thisRequests->status }}
                                                            @else
                                                                <i class="ti ti-circle-check text-success"></i>
                                                                {{ $thisRequests->status }}
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            @if ($thisRequests->status == 'completed')
                                                                <i class="ti ti-checklist"></i>
                                                                {{ __('Paid') }}
                                                            @else
                                                                {{ __('....') }}
                                                            @endif
                                                        </td>
                                                        {{-- <td class="text-center"><a href="#"
                                                                    class="btn btn-primary btn-sm" role="button"><i
                                                                        class="ti ti-eye"></i></a></td> --}}
                                                    </tr>
                                                    {{-- @endforeach --}}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($thisRequests->status == 'pending')
                        <center>
                            <div class="col-6">
                                <form action="{{ route('accept.request') }}" method="POST">
                                    @method('PUT')
                                    @csrf
                                    @php
                                        $encryptedId = Crypt::encrypt($thisRequests->id);
                                    @endphp
                                    <input type="hidden" name="pickupId" id="" value="{{ $encryptedId }}">
                                    <button type="submit" class="btn btn-primary">Accept This Request</button>
                                </form>
                            </div>
                        </center>
                    @endif

                    @if ($thisRequests->status == 'completed')
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="fs-4">Payment Details</h4>
                                    <div class="table-responsive mt-0">
                                        <table class="table mb-0 text-nowrap varient-table align-middle fs-3">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="px-0 text-muted">S/N</th>
                                                    <th scope="col" class="px-0 text-muted">Transaction Reference</th>
                                                    <th>Status</th>
                                                    <th>Date Created</th>
                                                    <th scope="col" class="px-0 text-muted">Amount Paid</th>
                                                    <th scope="col" class="px-0 text-muted">Total Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $totalAmount = 0;
                                                @endphp
                                                @foreach ($paymentDetails as $paymentData)
                                                    @php
                                                        $totalAmount += $paymentData->amount;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $paymentData->payment_id }}</td>
                                                        <td>{{ ucfirst($paymentData->status) }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($paymentData->created_at)->format('M d, Y H:i A') }}
                                                        </td>
                                                        <td>{{ number_format(\App\Services\CurrencyConverter::convertUsdToTsh($paymentData->amount), 2) }}

                                                        </td>
                                                        <td>{{ number_format(\App\Services\CurrencyConverter::convertUsdToTsh($paymentData->amount), 2) }}

                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="5"><strong>Total (TSH)</strong></td>
                                                        <td><strong>{{ number_format(\App\Services\CurrencyConverter::convertUsdToTsh($totalAmount), 2) }}</strong>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
