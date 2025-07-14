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
                            <p class="text-primary"></p>
                            <div id="map" style="height: 600px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const truckLat = {{ $locations->latitude ?? 'null' }};
        const truckLon = {{ $locations->longitude ?? 'null' }};

        // Destinations passed from Laravel controller as array of place names
        const destinations = @json($locations);

        // Initialize the map centered in Tanzania approx coordinates
        let map = L.map('map').setView([-6.8, 39.28], 7);

        // Add OpenStreetMap tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Get user's current location
        if (truckLat !== null && truckLon !== null) {

            // Mark truck location
            const truckMarker = L.marker([truckLat, truckLon])
                .addTo(map)
                .bindPopup('Truck Location: Dar es Salaam Kigamboni')
                .openPopup();

            // Get user location and draw route
            navigator.geolocation.getCurrentPosition(position => {
                const userLat = position.coords.latitude;
                const userLon = position.coords.longitude;

                const userMarker = L.marker([userLat, userLon])
                    .addTo(map)
                    .bindPopup('Your Location');

                // Show route from user to truck
                L.Routing.control({
                    waypoints: [
                        L.latLng(userLat, userLon),
                        L.latLng(truckLat, truckLon)
                    ],
                    routeWhileDragging: false,
                    addWaypoints: false,
                    draggableWaypoints: false,
                    createMarker: () => null
                }).addTo(map);
            }, error => {
                alert('Could not access your location.');
            });

        } else {
            alert("No truck location found.");
        }
    </script>
@endsection
