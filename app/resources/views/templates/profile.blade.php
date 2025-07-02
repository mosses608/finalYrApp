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
                            <div class="card-body">
                                <div class="row">
                                    <div class="text-center mb-4">
                                        <img src="{{ asset('/assets/images/profile/user-1.jpg') }}" alt="Profile Picture"
                                            width="100" height="100" class="rounded-circle shadow">
                                        <h4 class="mt-3">{{ $resident->name }}</h4>
                                        <p class="text-muted">{{ $resident->email ?? 'No email provided' }}</p>
                                    </div>
                                    <hr>
                                    <div class="col-4">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">
                                                <strong>Phone:</strong> {{ $resident->phone ?? 'Not available' }}
                                            </li>
                                            <li class="list-group-item">
                                                <strong>Address:</strong> {{ $resident->address ?? 'Not available' }}
                                            </li>
                                            <li class="list-group-item">
                                                <strong>Registered At:</strong>
                                                {{ \Carbon\Carbon::parse($resident->created_at)->format('M d, Y') }}
                                            </li>
                                            <li class="list-group-item">
                                                <strong>Updated At:</strong>
                                                {{ \Carbon\Carbon::parse($resident->updated_at)->format('M d, Y') }}
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-8">
                                        <form action="{{ route('update.profile') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="row">
                                                @php
                                                    $encryptedId = \Illuminate\Support\Facades\Crypt::encrypt($resident->id);
                                                @endphp
                                                <input type="hidden" name="user_id" id="" value="{{ $encryptedId }}">
                                                <div class="col-6">
                                                    <label for="" class="form-label">Names
                                                        </label>
                                                    <input type="text" name="name" id="location"
                                                        class="form-control" value="{{ $resident->name }}">
                                                </div>
                                                <div class="col-6">
                                                    <label for="" class="form-label">Phone
                                                        </label>
                                                    <input type="text" name="phone" id="location" maxlength="10"
                                                        class="form-control" value="{{ $resident->phone ?? '07xxxxxxxx' }}">
                                                </div>
                                                <div class="col-6 mt-3">
                                                    <label for="" class="form-label">Address
                                                        </label>
                                                    <input type="text" name="address" id="address"
                                                        class="form-control" value="{{ $resident->address ?? 'xxxxxxxx' }}">
                                                </div>
                                                <div class="col-6 mt-3">
                                                    <label for="" class="form-label">Email
                                                        </label>
                                                    <input type="email" name="email" id="email"
                                                        class="form-control" value="{{ $resident->email ?? 'xxxxxxxx@gmail.com' }}">
                                                </div>
                                                <div class="col-12 mt-3">
                                                    <label for="" class="form-label">Password
                                                        </label>
                                                    <input type="password" name="password" id="password"
                                                        class="form-control" placeholder="enter new password password" disabled>
                                                </div>
                                                <div class="col-6 mt-3">
                                                   <button type="submit" class="btn btn-primary">Update Profile</button>
                                                </div>
                                            </div>
                                        </form>
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
