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
                                    @if (session()->has('maskedEmail'))
                                        <div class="alert alert-success alert-dismissible fade show p-2" role="alert"
                                            id="alert">
                                            <span class="text-success me-1">Your OTP has been sent to an email <em
                                                    style="color: blue;">{{ session('maskedEmail') }}</em><i
                                                    class="fas fa-check"></i></span>
                                        </div>
                                    @endif

                                    <x-flash-messages />

                                    <p class="text-center">Waste Management and Recycling Exchange System
                                    </p>
                                    <form action="{{ route('verify.myEmail') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="exampleInputEmail1" class="d-flex"><strong>OTP</strong></label>
                                            <input type="number" class="form-control" id="exampleInputEmail1"
                                                placeholder="Enter OTP" min="1000" max="9999" name="otp"
                                                oninput="this.value = this.value.slice(0, 4);">
                                            <input type="hidden" name="email" id=""
                                                value="{{ session('email') }}">
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Verfy
                                            Email</button>
                                        <div class="d-flex align-items-center justify-content-center">
                                            <a class="text-primary fw-bold ms-2" href="{{ route('login') }}">Back to
                                                login</a>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                document.getElementById("alert").style.display = 'none';
            }, 4000);
        })
    </script>
@endsection
