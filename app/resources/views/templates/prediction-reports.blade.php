@extends('layouts.app')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                                <p class="text-primary">Predictions & Analytics after 5 months</p>
                            </div>
                            <div class="card-body">
                                <div class="tab-content mt-0" id="nav-tabContent">
                                    <div class="tab-pane fade show active" id="nav-pick-up-list" role="tabpanel"
                                        aria-labelledby="nav-profile-tab">
                                        <div class="table-responsive mt-0">
                                            <table class="table mb-0 text-nowrap varient-table align-middle fs-3"
                                                id="table-responsive1">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" class="px-0 text-muted">
                                                            S/N
                                                        </th>
                                                        <th>Month</th>
                                                        <th>Waste Name</th>
                                                        <th>Waste Type</th>
                                                        <th>Predicted Weight (kg)</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                @php
                                                    $n = 1;
                                                @endphp
                                                <tbody>
                                                    @foreach ($predictions as $row)
                                                        @php
                                                            $weight = $row['total_weight'] ?? 0;

                                                            if ($weight >= 100) {
                                                                $status = 'Danger';
                                                                $color = 'text-danger';
                                                            } elseif ($weight >= 50) {
                                                                $status = 'Warning';
                                                                $color = 'text-warning';
                                                            } else {
                                                                $status = 'Normal';
                                                                $color = 'text-success';
                                                            }
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $n++ }}</td>
                                                            <td>{{ $row['month'] ?? 'not specified' }}</td>
                                                            <td>{{ $row['title'] ?? 'not specified' }}</td>
                                                            <th>{{ $row['material_name'] ?? 'not specified' }}</th>
                                                            <td>{{ $row['total_weight'] ?? 'not specified' }}</td>
                                                            <td class="{{ $color }}">
                                                                <strong>{{ $status }}</strong>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row mt-4">
                                                <div class="col-12">
                                                    <a href="{{ route('report.pdf') }}" class="btn btn-primary float-end"><i class="ti ti-download"></i>
                                                        Download</a>
                                                </div>
                                            </div>
                                    </div>
                                </div>

                                <div class="row card p-3 mt-5">
                                    <p class="text-primary">📈 Waste Generation Forecast (Next 5 Months)</p>
                                    <canvas id="predictionChart" height="400"></canvas>
                                    <script>
                                        const predictions = @json($predictions);

                                        const materialWeights = {};

                                        predictions.forEach(entry => {
                                            const material = entry.material_name;
                                            const weight = parseFloat(entry.total_weight) || 0;

                                            if (!materialWeights[material]) {
                                                materialWeights[material] = 0;
                                            }
                                            materialWeights[material] += weight;
                                        });

                                        const labels = Object.keys(materialWeights);
                                        const weights = Object.values(materialWeights);

                                        const ctx = document.getElementById('predictionChart').getContext('2d');
                                        new Chart(ctx, {
                                            type: 'bar',
                                            data: {
                                                labels: labels,
                                                datasets: [{
                                                    label: 'Total Predicted Waste after 5 months',
                                                    data: weights,
                                                    backgroundColor: '#007bff',
                                                    // backgroundColor: labels.map((_, i) => `hsl(${i * 50}, 70%, 50%)`),
                                                    borderColor: '#333',
                                                    borderWidth: 0,
                                                    fill: true
                                                }]
                                            },
                                            options: {
                                                responsive: true,
                                                plugins: {
                                                    title: {
                                                        display: false,
                                                        text: 'Predicted Waste by Material'
                                                    }
                                                },
                                                scales: {
                                                    y: {
                                                        beginAtZero: true,
                                                        title: {
                                                            display: true,
                                                            text: 'Weight (kg)'
                                                        }
                                                    },
                                                    x: {
                                                        title: {
                                                            display: true,
                                                            text: 'Material Type'
                                                        }
                                                    }
                                                }
                                            }
                                        });
                                    </script>
                                </div>
                                <div class="row card p-3 mt-5">
                                    <p class="text-primary">📈 Line Chart</p>
                                    <canvas id="predictionLineChart" height="400"></canvas>
                                    <script>
                                        const predictionLineData = @json($predictionLine);

                                        const lineMaterialWeights = {};

                                        predictionLineData.forEach(entry => {
                                            const material = entry.material_name;
                                            const weight = parseFloat(entry.total_weight) || 0;

                                            if (!lineMaterialWeights[material]) {
                                                lineMaterialWeights[material] = 0;
                                            }
                                            lineMaterialWeights[material] += weight;
                                        });

                                        const lineLabels = Object.keys(lineMaterialWeights);
                                        const lineWeights = Object.values(lineMaterialWeights);

                                        const ctx2 = document.getElementById('predictionLineChart').getContext('2d');
                                        new Chart(ctx2, {
                                            type: 'line',
                                            data: {
                                                labels: lineLabels,
                                                datasets: [{
                                                    label: 'Total Predicted Waste after 5 months',
                                                    data: lineWeights,
                                                    backgroundColor: '#007bff',
                                                    borderColor: '#333',
                                                    // borderWidth: 0,
                                                    fill: false
                                                }]
                                            },
                                            options: {
                                                responsive: true,
                                                plugins: {
                                                    title: {
                                                        display: false,
                                                        text: 'Predicted Waste by Material'
                                                    }
                                                },
                                                scales: {
                                                    y: {
                                                        beginAtZero: true,
                                                        title: {
                                                            display: true,
                                                            text: 'Weight (kg)'
                                                        }
                                                    },
                                                    x: {
                                                        title: {
                                                            display: true,
                                                            text: 'Material Type'
                                                        }
                                                    }
                                                }
                                            }
                                        });
                                    </script>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
