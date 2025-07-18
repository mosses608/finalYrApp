@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <x-flash-messages />
        <div class="row mb-3">
            <div class="container py-1">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold fs-5">♻️ Recycling Exchange</h2>
                    <button href="#" class="btn btn-success rounded-pill px-4" data-bs-toggle="modal"
                        data-bs-target="#listItem">
                        + List Recyclable Item
                    </button>
                </div>

                <div class="row">
                    @forelse($recyclables as $recyclable)
                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm rounded-4 h-100">
                                <img src="{{ asset('storage/' . $recyclable->image) }}" class="card-img-top"
                                    alt="Recyclable" style="height: 250px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title"></h5>
                                    <p class="text-muted mb-1">Material: {{ $recyclable->materialName }}</p>
                                    <p class="text-muted mb-1">Category: {{ $recyclable->materialCategory }}</p>
                                    <p class="text-muted mb-1">Weight: {{ number_format($recyclable->weight) }}kg</p>
                                    <p class="text-muted mb-1">Listed by: {{ $recyclable->listedBy }}</p>
                                    <p class="fw-bold text-success">Price:  {{ number_format($recyclable->price, 2) }}
                                        
                                        TZS </p>
                                        @php
                                            $encryptedId = \Illuminate\Support\Facades\Crypt::encrypt($recyclable->id);
                                        @endphp
                                    <a href="{{ route('view.contracts', $encryptedId) }}" class="btn btn-outline-primary btn-sm mt-2">View Details</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">No recyclables listed yet. Be the first to list one!</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="listItem" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="fs-5 p-2" style="color: #0000FF;">Post Recyclables</h4>
                    <button type="button" class="btn-close fs-3" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('recyclable.post') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="title" class="form-label">Item Title</label>
                                    <input type="text" class="form-control" name="title" id="title"
                                        placeholder="e.g. Plastic Bottles" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="material_type" class="form-label">Material Type</label>
                                    <select name="material_type" id="material_type" class="form-select" required>
                                        <option value="" selected disabled>-- Select Material Type --</option>
                                        @foreach ($recyclebleExchangeCategory as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="weight" class="form-label">Weight (KG)</label>
                                    <input type="number" step="0.01" class="form-control" name="weight" id="weight"
                                        required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label">Price</label>
                                    <input type="number" step="0.01" class="form-control" name="price" id="price"
                                        required>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="image" class="form-label">Upload Image</label>
                                    <input type="file" class="form-control" name="image" id="image"
                                        accept="image/*" required>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="description" class="form-label">Description (optional)</label>
                                    <textarea name="description" id="description" class="form-control" rows="4"
                                        placeholder="e.g. Cleaned and sorted bottles..."></textarea>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-success rounded-pill px-4">
                                        📤 Submit Item
                                    </button>
                                </div>

                            </div>
                        </div>
                        {{-- <button type="submit" class="btn btn-primary d-flex">Submit --}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
