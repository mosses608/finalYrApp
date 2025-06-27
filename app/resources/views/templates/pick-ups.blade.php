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
                                        aria-controls="nav-home" aria-selected="true">PickUps Schedule List</button>
                                    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-accepted-requests" type="button" role="tab"
                                        aria-controls="nav-profile" aria-selected="false">New PickUp Schedule</button>
                                    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-pick-ups" type="button" role="tab"
                                        aria-controls="nav-profile" aria-selected="false">PickUps / Cars</button>
                                    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-pick-ups-new" type="button" role="tab"
                                        aria-controls="nav-profile" aria-selected="false">New PickUps / Cars</button>
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
                                                            PickUp Name
                                                        </th>
                                                        <th scope="col" class="px-0 text-muted">
                                                            PickUp Day
                                                        </th>
                                                        <th scope="col" class="px-0 text-muted">
                                                            PickUp Time
                                                        </th>
                                                        <th>
                                                            Area
                                                        </th>
                                                        <th>
                                                            Added By
                                                        </th>
                                                    </tr>
                                                </thead>
                                                @php
                                                    $n = 1;
                                                @endphp
                                                <tbody>
                                                    @foreach ($schedules as $schedule)
                                                        <tr>
                                                            <td>{{ $n++ }}</td>
                                                            <td>{{ $schedule->pName }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($schedule->day)->format('M d, Y') }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($schedule->time)->format('H:i A') }}</td>
                                                            <td>{{ $schedule->area }}</td>
                                                            <td>{{ $schedule->names }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="nav-accepted-requests" role="tabpanel"
                                        aria-labelledby="nav-profile-tab">
                                        <form action="{{ route('store.pickup.schedules') }}" method="POST">
                                            @csrf
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="mb-3">
                                                        <label for="exampleFormControlInput1" class="form-label">Pick Up
                                                            Car</label>
                                                        <select type="text" class="form-control"
                                                            aria-label="Sizing example input" name="pick_up_id"
                                                            aria-describedby="inputGroup-sizing-default">
                                                            <option value="" selected disabled>--select pick up--
                                                            </option>
                                                            @foreach ($pickUpsData as $item)
                                                                <option value="{{ $item->id }}">{{ $item->pName }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="mb-3">
                                                        <label for="exampleFormControlInput1" class="form-label">PickUp
                                                            Day</label>
                                                        <input type="date" class="form-control"
                                                            aria-label="Sizing example input" name="pickup_day"
                                                            aria-describedby="inputGroup-sizing-default">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="mb-3">
                                                        <label for="exampleFormControlInput1" class="form-label">PickUp
                                                            Time</label>
                                                        <input type="time" class="form-control"
                                                            aria-label="Sizing example input" name="preferred_time"
                                                            aria-describedby="inputGroup-sizing-default">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="mb-3">
                                                        <label for="exampleFormControlInput1" class="form-label">PickUp
                                                            Area</label>
                                                        <select type="text" class="form-control"
                                                            aria-label="Sizing example input" name="location"
                                                            aria-describedby="inputGroup-sizing-default">
                                                            <option value="" selected disabled>--select street--
                                                            </option>
                                                            @foreach ($pickUpAreas as $area)
                                                                <option value="{{ $area->location }}">{{ $area->location }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="mb-3">
                                                        <label for="exampleFormControlInput1" class="form-label">Staff
                                                            In-charge</label>
                                                        <select class="form-control" aria-label="Sizing example input"
                                                            name="staff_id" aria-describedby="inputGroup-sizing-default">
                                                            <option value="" selected disabled>--staff in-charge--
                                                            </option>
                                                            @foreach ($staffs as $staff)
                                                                <option value="{{ $staff->id }}">{{ $staff->names }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <button type="submit" class="btn btn-primary"><i
                                                            class="ti ti-send"></i> Submit</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>


                                    <div class="tab-pane fade" id="nav-pick-ups" role="tabpanel"
                                        aria-labelledby="nav-profile-tab">
                                        <div class="table-responsive mt-0">
                                            <table class="table mb-0 text-nowrap varient-table align-middle fs-3">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" class="px-0 text-muted">
                                                            S/N
                                                        </th>
                                                        <th scope="col" class="px-0 text-muted">
                                                            PickUp Names
                                                        </th>
                                                        <th scope="col" class="px-0 text-muted">
                                                            Reg Number
                                                        </th>
                                                        <th scope="col" class="px-0 text-muted">
                                                            Added By
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
                                                    @foreach ($pickUpsData as $data)
                                                        <tr>
                                                            <td>{{ $n++ }}</td>
                                                            <td>{{ $data->pName ?? '' }}</td>
                                                            <td>{{ $data->regNo ?? '' }}</td>
                                                            <td>{{ $data->names ?? '' }}</td>
                                                            <td class="text-center">
                                                                <a href="#" class="btn btn-sm"
                                                                    style="background-color: red; color: #FFF;">Delete</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            @if (count($pickUpsData) == 0)
                                                <span class="p-2 mt-3">No data found here!</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="nav-pick-ups-new" role="tabpanel"
                                        aria-labelledby="nav-profile-tab">
                                        <form action="{{ route('store.pickups') }}" method="POST">
                                            @csrf
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="mb-3">
                                                        <label for="exampleFormControlInput1" class="form-label">Pick Up
                                                            Name</label>
                                                        <input type="text" class="form-control"
                                                            aria-label="Sizing example input" name="pick_up_name"
                                                            aria-describedby="inputGroup-sizing-default"
                                                            placeholder="eg canter">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="mb-3">
                                                        <label for="exampleFormControlInput1" class="form-label">Reg
                                                            Number
                                                        </label>
                                                        <input type="text" class="form-control"
                                                            aria-label="Sizing example input" name="reg_number"
                                                            aria-describedby="inputGroup-sizing-default"
                                                            placeholder="eg ETC 789">
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
