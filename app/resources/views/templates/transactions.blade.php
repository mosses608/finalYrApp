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
                                <h6 class="text-muted mb-0">Transactions</h6>
                            </div>
                        </div>
                        <h3 class="fw-bold float-end fs-5">{{ number_format($allTransactionsCounter) }}</h3>
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
                        <h3 class="fw-bold float-end fs-5">{{ number_format($pendingTransactionsCounter) }}</h3>
                    </div>
                </div>
            </div>

            <!-- Total Pickups Counter -->
            <div class="col-md-3 mb-0">
                <div class="card border-0 shadow-lg rounded-3 py-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-ban text-warning fs-10 text-primary me-2"></i>
                                <h6 class="text-muted mb-0">Cancelled</h6>
                            </div>
                        </div>
                        <h3 class="fw-bold float-end fs-5">{{ number_format($canclledTransactionsCounter) }}</h3>
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
                        <h3 class="fw-bold float-end fs-5">{{ number_format($complteTransactionsCounter) }}</h3>
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
                                        aria-controls="nav-home" aria-selected="true">Transactions</button>
                                    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-accepted-requests" type="button" role="tab"
                                        aria-controls="nav-profile" aria-selected="false">Cancelled Transactions</button>
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
                                                            Payment ID
                                                        </th>
                                                        <th scope="col" class="px-0 text-muted">
                                                            Payer ID
                                                        </th>
                                                        <th scope="col" class="px-0 text-muted">
                                                            Payer Email
                                                        </th>
                                                        <th>
                                                            Currency
                                                        </th>
                                                        <th>
                                                            Amount
                                                        </th>
                                                        <th>
                                                            Due Date
                                                        </th>
                                                        <th>
                                                            Status
                                                        </th>
                                                        <th>
                                                            Mode
                                                        </th>
                                                    </tr>
                                                </thead>
                                                @php
                                                    $n = 1;
                                                @endphp
                                                <tbody>
                                                    @foreach ($transactions as $transaction)
                                                        <tr>
                                                            <td>{{ $n++ }}</td>
                                                            <td>{{ $transaction->payment_id }}</td>
                                                            <td>{{ $transaction->payer_id }}</td>
                                                            <td>{{ $transaction->payer_email }}</td>
                                                            <td>{{ $transaction->currency }}</td>
                                                            <td>{{ number_format($transaction->amount, 2) }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y') }}
                                                            </td>
                                                            <td>
                                                                <button class="btn btn-success btn-sm">
                                                                    <i class="ti ti-cash"></i> Paid
                                                                </button>
                                                            </td>
                                                            <td>{{ $transaction->mode }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

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
                                                            Payment ID
                                                        </th>
                                                        <th scope="col" class="px-0 text-muted">
                                                            Payer ID
                                                        </th>
                                                        <th scope="col" class="px-0 text-muted">
                                                            Payer Email
                                                        </th>
                                                        <th>
                                                            Currency
                                                        </th>
                                                        <th>
                                                            Amount
                                                        </th>
                                                        <th>
                                                            Due Date
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($cancelledTransaction as $ctransaction)
                                                        <tr>
                                                            <td>{{ $n++ }}</td>
                                                            <td>{{ $ctransaction->payment_id }}</td>
                                                            <td>{{ $ctransaction->payer_id }}</td>
                                                            <td>{{ $ctransaction->payer_email }}</td>
                                                            <td>{{ $ctransaction->currency }}</td>
                                                            <td>{{ number_format($ctransaction->amount, 2) }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($ctransaction->updated_at)->format('M d, Y') }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

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
