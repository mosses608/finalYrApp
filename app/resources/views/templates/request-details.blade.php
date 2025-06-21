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
                                            style="color: #0000FF;">Pick-Up
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
                                                        <td>{{ \Carbon\Carbon::parse($myPickUpRequest->pickup_date)->format('M d, Y') }}
                                                            |
                                                            <strong>{{ \Carbon\Carbon::parse($myPickUpRequest->preferred_time)->format('H:i A') }}</strong>
                                                        </td>
                                                        <td>{{ strtoupper($myPickUpRequest->frequency) }}</td>
                                                        <td>{{ $myPickUpRequest->location }}</td>
                                                        <td>
                                                            @if ($myPickUpRequest->status == 'pending')
                                                                <span class="spinner-border spinner-border-sm"
                                                                    role="status" aria-hidden="true"></span>
                                                                {{ $myPickUpRequest->status . ' ... ' }}
                                                            @elseif($myPickUpRequest->status == 'accepted')
                                                                <i class="ti ti-circle-check text-success"></i>
                                                                {{ $myPickUpRequest->status }}
                                                            @else
                                                                <i class="ti ti-checklist text-success"></i>
                                                                {{ $myPickUpRequest->status }}
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            @if ($myPickUpRequest->status == 'completed')
                                                                <span class="btn btn-primary btn-sm"><i
                                                                        class="ti ti-checklist text-success"></i>
                                                                    {{ __('Paid ...') }} </span>
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
                                            @if ($myPickUpRequest->status == 'accepted')
                                                <tr>
                                                    <td colspan="6">
                                                        <span class="p-2 fs-3" style="color: #0000FF;">
                                                            No payment details related to this request!
                                                        </span>
                                                        <div class="row">
                                                            <div class="col-3 mt-2 mb-2 p-10 float-start">
                                                                <button class="btn btn-primary" data-bs-toggle="modal"
                                                                    data-bs-target="#exampleModal">
                                                                    <img src="https://www.paypalobjects.com/webstatic/icon/pp258.png"
                                                                        alt="PayPal" width="24">
                                                                    Pay Now ($ 8.79)
                                                                </button>
                                                            </div>
                                                            <div class="col-3 mt-2 mb-2 p-10 float-end">
                                                                <button class="btn btn-primary" data-bs-toggle="modal"
                                                                    data-bs-target="#walletModal">
                                                                    <i class="ti ti-wallet fs-4 p-1"></i>
                                                                    Pay Using My Wallet
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @elseif ($myPickUpRequest->status == 'completed')
                                                @php
                                                    $totalAmount = 0;
                                                @endphp
                                                @foreach ($paymentDetails as $paymentData)
                                                    @php
                                                    if($paymentData->currency == 'USD'){
                                                        $totalAmount += \App\Services\CurrencyConverter::convertUsdToTsh($paymentData->amount);
                                                    }else{
                                                        $totalAmount += $paymentData->amount;
                                                    }
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $paymentData->payment_id }}</td>
                                                        <td>{{ ucfirst($paymentData->status) }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($paymentData->created_at)->format('M d, Y H:i A') }}
                                                        </td>
                                                        <td>
                                                            @if ($paymentData->currency == 'USD')
                                                                {{ number_format(\App\Services\CurrencyConverter::convertUsdToTsh($paymentData->amount), 2) }}
                                                            @else
                                                                {{ number_format($paymentData->amount, 2) }}
                                                            @endif

                                                        </td>
                                                        <td>
                                                            @if ($paymentData->currency == 'USD')
                                                                {{ number_format(\App\Services\CurrencyConverter::convertUsdToTsh($paymentData->amount), 2) }}
                                                            @else
                                                                {{ number_format($paymentData->amount, 2) }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="6">
                                                        <span class="p-2 mt-3 mb-3" style="color: #0000FF;">Your request has
                                                            not been accepted yet!</span>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>

                                        @if ($myPickUpRequest->status == 'completed')
                                            <tfoot>
                                                <tr>
                                                    <td colspan="5"><strong>Total (TSH)</strong></td>
                                                    <td><strong>{{ number_format($totalAmount, 2) }}</strong>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Complete Transaction</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('pay.ckechout') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="col-12">
                            <div class="row">
                                @php
                                    $encryptedId = Crypt::encrypt($myPickUpId);
                                @endphp
                                <input type="hidden" name="requestId" id="" value="{{ $encryptedId }}">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label fs-3">Email</label>
                                    <input type="email" class="form-control fs-3" id="email" name="email"
                                        value="{{ Auth::user()->username }}" readonly>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="amount" class="form-label fs-3">Amount</label>
                                    <input type="numeric" class="form-control fs-3" id="amount" name="amount"
                                        placeholder="Amount">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary d-flex"><img
                                src="https://www.paypalobjects.com/webstatic/icon/pp258.png" alt="PayPal"
                                width="24">
                            Pay with Paypal</button>
                    </div>
                    {{-- <div class="modal-footer"> --}}
                    {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> --}}
                    {{-- <button type="submit" class="btn btn-primary d-flex">Submit</button> --}}
                    {{-- </div> --}}
                </form>
            </div>
        </div>
    </div>


    {{-- WALLET PAYMENT --}}
    <!-- Modal -->
    <div class="modal fade" id="walletModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Wallet Balance: <strong
                            style="color: #0000FF;">({{ $balance->currency . ' ' . number_format($balance->balance, 2) }})</strong>
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('wallet.pay') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="col-12">
                            <div class="row">
                                @php
                                    $encryptedId = Crypt::encrypt($myPickUpId);
                                @endphp
                                <input type="hidden" name="requestId" id="" value="{{ $encryptedId }}">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label fs-3">Email</label>
                                    <input type="email" class="form-control fs-3" id="email" name="email"
                                        value="{{ Auth::user()->username }}" readonly>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="amount" class="form-label fs-3">Amount</label>
                                    <input type="numeric" class="form-control fs-3" id="amount" name="amount"
                                        value="{{ \App\Services\CurrencyConverter::convertUsdToTsh(6.53) }}">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary d-flex">
                            <i class="ti ti-wallet p-1"></i>
                            Pay Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
