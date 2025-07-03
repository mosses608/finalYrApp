@extends('layouts.app')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<!-- Leaflet Routing Machine CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.min.js"></script>

@section('content')
    <div class="container-fluid">
        <x-flash-messages />
        <div class="row">
            <!--  Row 1 -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-primary">Completed Pickup Locations | {{ count($locations) }} PickUp Areas</p>
                            <div id="map" style="height: 600px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="map" style="height: 600px;"></div>

    <script>
        const destinations = @json($locations);

        let map = L.map('map').setView([-6.8, 39.28], 7);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; All Rights Reserved'
        }).addTo(map);

        navigator.geolocation.getCurrentPosition(position => {
            const userLat = position.coords.latitude;
            const userLon = position.coords.longitude;

            const userLocation = L.marker([userLat, userLon])
                .addTo(map)
                .bindPopup('Dar es salaam Msasani Tanesco Street')
                .openPopup();

            destinations.forEach((place, index) => {
                fetch(
                        `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(place)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.length > 0) {
                            const destLat = data[0].lat;
                            const destLon = data[0].lon;

                            const marker = L.marker([destLat, destLon])
                                .addTo(map)
                                .bindPopup(place);

                            L.Routing.control({
                                waypoints: [
                                    L.latLng(userLat, userLon),
                                    L.latLng(destLat, destLon)
                                ],
                                createMarker: function(i, waypoint, n) {
                                    return null;
                                },
                                routeWhileDragging: false,
                                addWaypoints: false,
                                draggableWaypoints: false
                            }).addTo(map);
                        }
                    });
            });

        }, error => {
            alert('Location access denied or unavailable.');
        });
    </script>
@endsection
