@extends('layouts.app')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<style>
    #map {
        height: 500px;
    }
</style>
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
                                        aria-controls="nav-home" aria-selected="true">My Pickup Requests</button>
                                    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-new-pick-up" type="button" role="tab"
                                        aria-controls="nav-profile" aria-selected="false">Create New Request</button>
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
                                                            Pick-Up Date
                                                        </th>
                                                        <th scope="col" class="px-0 text-muted">
                                                            Pick-Up Time
                                                        </th>
                                                        <th>
                                                            Frequency
                                                        </th>
                                                        <th>
                                                            Location
                                                        </th>
                                                        <th>
                                                            Status
                                                        </th>
                                                        <th>
                                                            Payment
                                                        </th>
                                                        <th>
                                                            Action
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($myPickUpRequests as $mypicks)
                                                        <tr>
                                                            <td>
                                                                {{ $loop->iteration }}
                                                            </td>
                                                            <td>
                                                                {{ \Carbon\Carbon::parse($mypicks->pickup_date)->format('M d, Y') }}
                                                            </td>
                                                            <td>
                                                                {{ \Carbon\Carbon::parse($mypicks->preferred_time)->format('h:i A') }}
                                                            </td>
                                                            <td class="text-center">
                                                                <strong>{{ strtoupper($mypicks->frequency) }}</strong>
                                                            </td>
                                                            <td class="text-center">
                                                                {{ $mypicks->location }}
                                                            </td>

                                                            <td class="text-center">
                                                                @if ($mypicks->status == 'pending')
                                                                    <span class="spinner-border spinner-border-sm"
                                                                        role="status" aria-hidden="true"></span>
                                                                    {{ $mypicks->status . ' ... ' }}
                                                                @elseif($mypicks->status == 'accepted')
                                                                    <i class="ti ti-circle-check text-success"></i>
                                                                    {{ $mypicks->status }}
                                                                @else
                                                                    <i class="ti ti-circle-check text-success"></i>
                                                                    {{ $mypicks->status }}
                                                                @endif
                                                            </td>
                                                            @php
                                                                $encryptedId = Crypt::encrypt($mypicks->id);
                                                            @endphp
                                                            <td class="text-center"><strong>
                                                                    @if ($mypicks->status == 'completed')
                                                                        <span class="btn btn-primary btn-sm"><i
                                                                                class="ti ti-checklist text-success"></i>
                                                                            {{ __('Paid ...') }} </span>
                                                                    @else
                                                                        {{ __('....') }}
                                                                    @endif
                                                                </strong></td>
                                                            <td class="text-center">
                                                                <a href="{{ route('my.request.details', $encryptedId) }}"
                                                                    class="btn btn-primary btn-sm"><i
                                                                        class="ti ti-eye"></i></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @if (count($myPickUpRequests) == 0)
                                            <span class="p-2 mt-2 mb-2 fs-3">No pick-up request for me here!</span>
                                        @endif
                                    </div>
                                    <div class="tab-pane fade" id="nav-new-pick-up" role="tabpanel"
                                        aria-labelledby="nav-profile-tab">
                                        <form action="{{ route('store.schedules') }}" method="POST">
                                            @csrf
                                            <div class="col-12 p-2">
                                                <div class="col-12 p-2">
                                                    <div class="row">
                                                        <!-- Frequency -->
                                                        <div class="col-md-4 mb-3">
                                                            <label for="frequency" class="form-label">Frequency</label>
                                                            <select name="frequency" id="frequency" class="form-select">
                                                                <option value="" selected disabled>select pickup
                                                                    frequency</option>
                                                                <option value="once">Once</option>
                                                                <option value="daily">Daily</option>
                                                                <option value="weekly">Weekly</option>
                                                                <option value="monthly">Monthly</option>
                                                            </select>
                                                        </div>

                                                        @php
                                                            $today = \Carbon\Carbon::now()->format('Y-m-d');
                                                        @endphp

                                                        <!-- Pickup Date -->
                                                        <div class="col-md-4 mb-3">
                                                            <label for="pickup_date" class="form-label">Pickup Date</label>
                                                            <input type="date" name="pickup_date"
                                                                min="{{ $today }}" id="pickup_date"
                                                                class="form-control"
                                                                value="{{ old('pickup_date', \Carbon\Carbon::now()->format('Y-m-d')) }}"
                                                                required>
                                                        </div>

                                                        <!-- Preferred Time -->
                                                        <div class="col-md-4 mb-3">
                                                            <label for="preferred_time" class="form-label">Preferred
                                                                Time</label>
                                                            <input type="time" name="preferred_time" id="preferred_time"
                                                                class="form-control">
                                                        </div>

                                                        <!-- Location -->
                                                        <div class="col-md-4 mb-3">
                                                            <label for="location" class="form-label">Location
                                                            </label>
                                                            <input type="text" name="location" id="location"
                                                                class="form-control" placeholder="Tell us pickup location"
                                                                value="{{ $address }}">
                                                        </div>
                                                        {{-- <div class="col-md-4 mb-3">
                                                            <label for="" class="form-label"></label>
                                                            <label for="location" class="form-label">Check here to use my
                                                                location</label><br>
                                                            <center>
                                                                <input type="checkbox" id="location" class=""
                                                                    name="mylocation" value="{{ Crypt::encrypt(1) }}">
                                                            </center>
                                                        </div> --}}
                                                    </div>
                                                    <!-- Submit Button -->
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                        </form>
                                        <hr>
                                        <p class="text-primary">My Location {{ $address }}</p>
                                        <div id="map"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let address = @json($address);

                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            const lat = data[0].lat;
                            const lon = data[0].lon;

                            const map = L.map('map').setView([lat, lon], 15);

                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '&copy; All Rights Reserved'
                            }).addTo(map);

                            L.marker([lat, lon]).addTo(map)
                                .bindPopup(`<b>${address}</b>`)
                                .openPopup();
                        } else {
                            alert('Location not found.');
                        }
                    })
                    .catch(error => console.error('Geocoding error:', error));
            });
        </script>
    </div>
@endsection
