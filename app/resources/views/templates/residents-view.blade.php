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
                                        aria-controls="nav-home" aria-selected="true">Residents List</button>
                                    {{-- <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-accepted-requests" type="button" role="tab"
                                        aria-controls="nav-profile" aria-selected="false">Create New User</button> --}}
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
                                                            Phone
                                                        </th>
                                                        <th scope="col" class="px-0 text-muted">
                                                            Email
                                                        </th>
                                                        <th>
                                                            Address
                                                        </th>
                                                        <th>
                                                            Date Registered
                                                        </th>
                                                        {{-- <th>
                                                            Action
                                                        </th> --}}
                                                    </tr>
                                                </thead>
                                                @php
                                                    $n = 1;
                                                @endphp
                                                <tbody>
                                                    @foreach ($residents as $rs)
                                                        <tr>
                                                            <td>{{ $n++ }}</td>
                                                            <td>{{ $rs->name }}</td>
                                                            <td>{{ $rs->phone ?? '........' }}</td>
                                                            <td>{{ $rs->email }}</td>
                                                            <td>{{ $rs->address ?? '........' }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($rs->created_at)->format('M d, Y') }}</td>
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
