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
                                        aria-controls="nav-home" aria-selected="true">Users List</button>
                                    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-accepted-requests" type="button" role="tab"
                                        aria-controls="nav-profile" aria-selected="false">Create New User</button>
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
                                                            Names
                                                        </th>
                                                        <th scope="col" class="px-0 text-muted">
                                                            Role
                                                        </th>
                                                        <th scope="col" class="px-0 text-muted">
                                                            Email
                                                        </th>
                                                        <th>
                                                            Phone
                                                        </th>
                                                        <th>
                                                            Gender
                                                        </th>
                                                        <th>
                                                            Date Registered
                                                        </th>
                                                        <th>
                                                            Status
                                                        </th>
                                                        <th>
                                                            Action
                                                        </th>
                                                    </tr>
                                                </thead>
                                                @php
                                                    $n = 1;
                                                @endphp
                                                <tbody>
                                                    @foreach ($staffs as $staff)
                                                        <tr>
                                                            <td>{{ $n++ }}</td>
                                                            <td>{{ $staff->names }}</td>
                                                            <td>
                                                                @if ($staff->roleId == 1)
                                                                    <sup class="text-primary"><i class="ti ti-settings"></i></sup>
                                                                @endif
                                                                {{ $staff->roleName }}
                                                            </td>
                                                            <td>{{ $staff->email }}</td>
                                                            <td>{{ $staff->phone }}</td>
                                                            <td>{{ $staff->gender }}</td>
                                                            <td>{{ $staff->regDate }}</td>
                                                            <td>
                                                                @if ($staff->status == 1)
                                                                    <span class="text-success">
                                                                        <i class="ti ti-circle-check"></i> Active
                                                                    </span>
                                                                @else
                                                                    <span class="text-danger">
                                                                        <i class="ti ti-circle-x"></i> Inactive
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td class="text-nowrap text-center"><a
                                                                    class="btn btn-primary btn-sm" href="#"><i
                                                                        class="ti ti-eye"></i></a></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="nav-accepted-requests" role="tabpanel"
                                        aria-labelledby="nav-profile-tab">
                                        <form action="{{ route('store.staff') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                <div class="col-4">
                                                    <div class="mb-3">
                                                        <label for="exampleFormControlInput1"
                                                            class="form-label">Names</label>
                                                        <input type="text" class="form-control"
                                                            aria-label="Sizing example input" name="names"
                                                            aria-describedby="inputGroup-sizing-default"
                                                            placeholder="full names">
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="mb-3">
                                                        <label for="exampleFormControlInput1"
                                                            class="form-label">Email</label>
                                                        <input type="email" class="form-control"
                                                            aria-label="Sizing example input" name="email"
                                                            aria-describedby="inputGroup-sizing-default"
                                                            placeholder="email address">
                                                        @error('email')
                                                            <div class="text-danger">{{ session('error_ms') }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="mb-3">
                                                        <label for="exampleFormControlInput1"
                                                            class="form-label">Phone</label>
                                                        <input type="tel" class="form-control"
                                                            aria-label="Sizing example input" name="phone_number"
                                                            aria-describedby="inputGroup-sizing-default" maxlength="10"
                                                            placeholder="phone number e.g 07xxxxxxxx">
                                                        @error('phone_number')
                                                            <div class="text-danger">{{ session('error_ms') }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="mb-3">
                                                        <label for="exampleFormControlInput1" class="form-label">User
                                                            role</label>
                                                        <select class="form-control" aria-label="Sizing example input"
                                                            aria-describedby="inputGroup-sizing-default" name="role">
                                                            <option value="" selected disabled>--select role--
                                                            </option>
                                                            @foreach ($roles as $role)
                                                                <option value="{{ $role->id }}">{{ $role->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="mb-3">
                                                        <label for="exampleFormControlInput1"
                                                            class="form-label">Gender</label>
                                                        <select class="form-control" aria-label="Sizing example input"
                                                            name="gender" aria-describedby="inputGroup-sizing-default">
                                                            <option value="" selected disabled>--select gender--
                                                            </option>
                                                            <option value="M">male</option>
                                                            <option value="F">female</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="mb-3">
                                                        <label for="exampleFormControlInput1" class="form-label">Birth
                                                            Date</label>
                                                        <input type="date" class="form-control"
                                                            aria-label="Sizing example input" name="date_of_birth"
                                                            aria-describedby="inputGroup-sizing-default"
                                                            value="{{ old('date_of_birth', \Carbon\Carbon::now()->format('Y-m-d')) }}">
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="mb-3">
                                                        <label for="exampleFormControlInput1"
                                                            class="form-label">Photo</label>
                                                        <input type="file" class="form-control"
                                                            aria-label="Sizing example input" name="photo"
                                                            accept="image/*" aria-describedby="inputGroup-sizing-default">
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="mb-3">
                                                        <label for="exampleFormControlInput1"
                                                            class="form-label">Password</label>
                                                        <input type="password" class="form-control"
                                                            aria-label="Sizing example input" name="password"
                                                            aria-describedby="inputGroup-sizing-default"
                                                            placeholder="password" autocomplete="off">
                                                        @error('password')
                                                            <div class="text-danger mt-1">{{ session('error_msg') }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="mb-3">
                                                        <label for="exampleFormControlInput1" class="form-label">Confirm
                                                            Password</label>
                                                        <input type="password" class="form-control"
                                                            aria-label="Sizing example input" name="password_confirm"
                                                            aria-describedby="inputGroup-sizing-default"
                                                            placeholder="password confirm" autocomplete="off">
                                                        @error('password_confirm')
                                                            <div class="text-danger mt-1">{{ session('error_msg') }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <button type="submit" class="btn btn-primary"><i
                                                            class="ti ti-send"></i> Submit</button>
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
