@extends('layouts.app')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
@section('content')
    <div class="container-fluid">
        <x-flash-messages />
        <div class="row">
            <!--  Row 1 -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        {{-- <div class="card-body"> --}}
                            <p class="text-primary">Completed Pickup Locations</p>
                            <div id="map" style="height: 600px;"></div>
                        {{-- </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const locations = @json($locations);

        document.addEventListener('DOMContentLoaded', () => {
            const map = L.map('map').setView([-6.8, 39.28], 7); // Default center over Tanzania

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // Loop through locations and geocode
            locations.forEach(location => {
                fetch(
                        `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(location)}`
                        )
                    .then(res => res.json())
                    .then(data => {
                        if (data.length > 0) {
                            const lat = data[0].lat;
                            const lon = data[0].lon;

                            L.marker([lat, lon])
                                .addTo(map)
                                .bindPopup(`<b>${location}</b>`);
                        }
                    })
                    .catch(err => console.error('Geocode error:', err));
            });
        });
    </script>
@endsection
