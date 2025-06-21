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
                                    {{-- <a href="#" class="text-nowrap logo-img text-center d-block py-3 w-100">
                                    <img src="./assets/images/logos/logo.svg" alt="">
                                </a> --}}
                                    <p class="text-center">Waste Management and Recycling Exchange System</p>
                                    <form action="{{ route('user.register') }}" method="POST">
                                        <x-flash-messages />
                                        @csrf
                                        <div class="mb-3">
                                            <label for="exampleInputtext1" class="d-flex"><strong>Name</strong></label>
                                            <input type="text" class="form-control" id="exampleInputtext1"
                                                aria-describedby="textHelp" placeholder="Enter full names" name="name"
                                                value="{{ old('name') }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="exampleInputEmail1" class="d-flex"><strong>Email</strong></label>
                                            <input type="email" class="form-control" id="exampleInputEmail1"
                                                placeholder="Email address" name="email" value="{{ old('email') }}"
                                                required>
                                            <span class="d-flex fs-2 p-2" style="color: red;">
                                                @if (session()->has('error_msg'))
                                                    {{ session('error') }}
                                                @endif
                                            </span>
                                        </div>
                                        <div class="mb-4">
                                            <label for="exampleInputPassword1"
                                                class="d-flex"><strong>Password</strong></label>
                                            <input type="password" class="form-control" id="exampleInputPassword1"
                                                placeholder="Password" name="password" required autocomplete="off">
                                            <span class="d-flex fs-2 p-2" style="color: red;">
                                                @if (session()->has('error_msg'))
                                                    {{ session('error') }}
                                                @endif
                                            </span>
                                        </div>
                                        <div class="mb-4">
                                            <label for="exampleInputPassword1" class="d-flex"><strong>Confirm
                                                    Password</strong></label>
                                            <input type="password" class="form-control" id="exampleInputPassword1"
                                                placeholder="Re-enter password" name="password_confirm" required autocomplete="off">
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Sign
                                            Up</button>
                                        <div class="d-flex align-items-center justify-content-center">
                                            <p class="fs-4 mb-0 fw-bold">Already have an Account?</p>
                                            <a class="text-primary fw-bold ms-2" href="{{ route('login') }}">Sign
                                                In</a>
                                        </div>
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
