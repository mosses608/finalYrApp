@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <x-flash-messages />
        <div class="row mb-3">
            {{-- <div class="card"> --}}
            <div class="col-6">
                <i class="ti ti-wallet" style="color: #f0c330; font-size: 60px;"></i> <sup
                    style="color: #0000FF;"><strong>Balance:
                        {{ $balance->currency . ' ' . number_format($balance->balance, 2) }}</strong></sup>
            </div>
            <div class="col-6">
                <button class="btn btn-secondary p-2 btn-sm float-end" data-bs-toggle="modal"
                    data-bs-target="#rechargeWalletModal">Re-Charge Wallet</button>
            </div>
            {{-- </div> --}}
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="fs-4">Wallet Transactions</h4>
                        <div class="table-responsive mt-0">
                            <table class="table mb-0 text-nowrap varient-table align-middle fs-3">
                                <thead>
                                    <tr>
                                        <th scope="col" class="px-0 text-muted">S/N</th>
                                        <th scope="col" class="px-0 text-muted">Transaction Reference ID</th>
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
                                    @foreach ($walletTransactions as $wallet)
                                        @php
                                            if ($wallet->currency == 'USD') {
                                                $totalAmount += \App\Services\CurrencyConverter::convertUsdToTsh(
                                                    $wallet->amount,
                                                );
                                            } else {
                                                $totalAmount += $wallet->amount;
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $wallet->payment_id }}</td>
                                            <td>{{ ucfirst($wallet->status) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($wallet->created_at)->format('M d, Y H:i A') }}
                                            </td>
                                            <td>
                                                @if ($wallet->currency == 'USD')
                                                    {{ number_format(\App\Services\CurrencyConverter::convertUsdToTsh($paymentData->amount), 2) }}
                                                @else
                                                    {{ number_format($wallet->amount, 2) }}
                                                @endif

                                            </td>
                                            <td>
                                                @if ($wallet->currency == 'USD')
                                                    {{ number_format(\App\Services\CurrencyConverter::convertUsdToTsh($paymentData->amount), 2) }}
                                                @else
                                                    {{ number_format($wallet->amount, 2) }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <td colspan="5"><strong>Total (TSH)</strong></td>
                                        <td><strong>{{ number_format($totalAmount, 2) }}</strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                {{-- <center class="mt-0 mb-0 p-5">
                    <button class="btn btn-primary btn-sm">Re-charge Wallet</button>
                </center> --}}

                <div class="card">
                    <div class="card-body">
                        <h4 class="fs-4">Wallet Re-charge Transactions</h4>
                        <div class="table-responsive mt-0">
                            <table class="table mb-0 text-nowrap varient-table align-middle fs-3">
                                <thead>
                                    <tr>
                                        <th scope="col" class="px-0 text-muted">S/N</th>
                                        <th scope="col" class="px-0 text-muted">Transaction Reference ID</th>
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
                                    @foreach ($walletRechargeTransactions as $recharge)
                                        @php
                                            if ($recharge->currency == 'USD') {
                                                $totalAmount += \App\Services\CurrencyConverter::convertUsdToTsh(
                                                    $recharge->amount,
                                                );
                                            } else {
                                                $totalAmount += $recharge->amount;
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $recharge->payment_id }}</td>
                                            <td>{{ ucfirst($recharge->status) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($recharge->created_at)->format('M d, Y H:i A') }}
                                            </td>
                                            <td>
                                                @if ($recharge->currency == 'USD')
                                                    {{ number_format(\App\Services\CurrencyConverter::convertUsdToTsh($recharge->amount), 2) }}
                                                @else
                                                    {{ number_format($recharge->amount, 2) }}
                                                @endif

                                            </td>
                                            <td>
                                                @if ($recharge->currency == 'USD')
                                                    {{ number_format(\App\Services\CurrencyConverter::convertUsdToTsh($recharge->amount), 2) }}
                                                @else
                                                    {{ number_format($recharge->amount, 2) }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                                @if (count($walletRechargeTransactions) > 0)
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
                        @if (count($walletRechargeTransactions) == 0)
                            <span class="mb-3 mt-3 p-2">No transactions found!</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="rechargeWalletModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Wallet Balance: <strong
                            style="color: #0000FF;">({{ $balance->currency . ' ' . number_format($balance->balance, 2) }})</strong>
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('recharge.wallet') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label fs-3">Email</label>
                                    <input type="email" class="form-control fs-3" id="email" name="email"
                                        value="{{ Auth::user()->username }}" readonly>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="amount" class="form-label fs-3">Amount</label>
                                    <input type="numeric" class="form-control fs-3" id="amount" name="amount"
                                        placeholder="Amount is USD">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary d-flex">
                            <img src="https://www.paypalobjects.com/webstatic/icon/pp258.png" alt="PayPal" width="24">
                            Re-Charge</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
