@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <x-flash-messages />
        <div class="row mb-3">
            <div class="container py-0">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold fs-5">♻️ Recycling Exchange Contract</h2>
                </div>

                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="card shadow-sm rounded-4 p-3">
                            <h4 class="mb-3">Contract Details</h4>
                            <p><strong>Material:</strong> {{ $recyclable->title }}</p>
                            {{-- <p><strong>Category:</strong> {{ $recyclable->material_type }}</p> --}}
                            <p><strong>Weight:</strong> {{ $recyclable->weight }} kg</p>
                            <p><strong>Buyer:</strong> {{ $userData->name ?? '' }}</p>
                            <p><strong>Seller:</strong> {{ $sellerData->name ?? '' }}</p>
                            <p><strong>Price:</strong> ${{ number_format($contract->price_usd, 2) }}</p>
                            <p><strong>Status:</strong> <span class="badge bg-info">{{ $contract->status }}</span></p>

                            @php
                                $block = json_decode($contract->blockchain_data);
                            @endphp

                            <hr>
                            <h6 class="text-primary">Blockchain Data</h6>
                            <p><strong>Block Hash:</strong> {{ $block->hash }}</p>
                            <p><strong>Timestamp:</strong> {{ $block->timestamp }}</p>
                            <hr>
                            <div class="row">
                                @php
                                    $ecnryptedRecId = Crypt::encrypt($contract->id);
                                @endphp
                                @if ($contract->status === 'Pending')
                                    <form method="POST" action="{{ route('contract.approve') }}">
                                        <label class="col-12">
                                            <input type="checkbox" name="approve" value="1" required>
                                            I hereby acknowledge and give my full consent to enter into this recycling
                                            exchange
                                            contract, agreeing to all the terms and conditions stated.
                                        </label>
                                        <div class="col-12">
                                            @csrf
                                            <input type="hidden" name="id" id=""
                                                value="{{ $ecnryptedRecId }}">
                                            <input type="hidden" name="recyclable_id" id=""
                                                value="{{ Crypt::encrypt($recyclable->id) }}">
                                            <input type="hidden" name="price" id=""
                                                value="{{ Crypt::encrypt($contract->price_usd) }}">
                                            <button type="submit" class="btn btn-success mt-3">Approve Contract</button>
                                        </div>

                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
