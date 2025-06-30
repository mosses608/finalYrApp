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
                                    {{-- <a href="/" class="text-nowrap logo-img text-center d-block py-3 w-100">
                                        <img src="./assets/images/logos/logo.svg" alt="">
                                    </a> --}}
                                    <x-flash-messages />
                                    <p class="text-center">Waste Management and Recycling Exchange System
                                    </p>
                                    <form action="{{ route('authenticate.user') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="exampleInputEmail1" class="d-flex"><strong>Username</strong></label>
                                            <input type="text" class="form-control" id="exampleInputEmail1" name="username" placeholder="Email or phone number as username">
                                        </div>
                                        <div class="mb-4">
                                            <label for="exampleInputPassword1" class="d-flex"><strong>Password</strong></label>
                                            <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Enter password correctly!" autocomplete="off">
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mb-4">
                                            <div class="form-check">
                                                <input class="form-check-input primary" type="checkbox" value=""
                                                    id="flexCheckChecked" checked>
                                                <label class="form-check-label text-dark" for="flexCheckChecked">
                                                    Remeber Me
                                                </label>
                                            </div>
                                            <a href="{{ route('forgot.password') }}" class="text-primary fw-bold">Forgot Password ?</a>
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Sign
                                            In</button>
                                        <div class="d-flex align-items-center justify-content-center">
                                            <p class="fs-4 mb-0 fw-bold">Don't have an account?</p>
                                            <a class="text-primary fw-bold ms-2" href="{{ route('register') }}">Create an
                                                account</a>
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
