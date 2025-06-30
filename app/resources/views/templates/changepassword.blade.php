@extends('layouts.landing')

@section('content')
    <center>
        <div class="page-wrapper col-8" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
            data-sidebar-position="fixed" data-header-position="fixed">
            <div
                class="position-relative overflow-hidden text-bg-light min-vh-100 d-flex align-items-center justify-content-center">
                <div class="d-flex align-items-center justify-content-center w-100">
                    <div class="row justify-content-center w-100">
                        <div class="col-md-8 col-lg-6 col-xxl-3">
                            <div class="card mb-0">
                                <div class="card-body">

                                    <x-flash-messages />
                                    <p class="text-center">Waste Management and Recycling Exchange System
                                    </p>
                                    <form action="{{ route('reset.password') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <input type="hidden" name="email" id="" value="{{ \Illuminate\Support\Facades\Crypt::encrypt($email) }}">
                                            <input type="hidden" name="token" id="" value="{{ $token }}">
                                            <label for="exampleInputEmail1" class="d-flex"><strong>New
                                                    Password</strong></label>
                                            <input type="password" class="form-control" id="exampleInputEmail1"
                                                name="password" placeholder="Enter new password">
                                        </div>

                                        <div class="mb-3">
                                            <label for="exampleInputEmail1" class="d-flex"><strong>Confirm New
                                                    Password</strong></label>
                                            <input type="password" class="form-control" id="exampleInputEmail1"
                                                name="password_reset" placeholder="Re-enter password">
                                        </div>

                                        <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Rest
                                            Password</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </center>
@endsection
