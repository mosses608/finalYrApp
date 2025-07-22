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
                                <p class="text-primary">Current Analytics For Waste Generation</p>
                            </div>
                            <form action="{{ route('prediction.reports') }}" method="GET" class="row">
                                @csrf
                                <div class="col-5">
                                    <input type="month" class="form-control" name="from"
                                        max="{{ \Carbon\Carbon::today()->format('Y-m') }}"
                                        value="{{ old('from', \Carbon\Carbon::now()->format('Y-m')) }}">
                                </div>

                                <div class="col-5">
                                    <input type="month" class="form-control" name="to"
                                        value="{{ old('to', \Carbon\Carbon::now()->format('Y-m')) }}">
                                </div>

                                <div class="col-2">
                                    <button type="submit" class="btn btn-primary float-end">Search</button>
                                </div>
                            </form>
                            <div class="row">
                                <div class="col-12 mt-3">
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
                                                    <th>Produced Weight (kg)</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            @php
                                                $n = 1;
                                            @endphp
                                            <tbody>
                                                @foreach ($wasteProductions as $waste)
                                                    @php
                                                        $weight = $waste->total_weight ?? 0;

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
                                                        <td>{{ $waste->month }}</td>
                                                        <td>{{ $waste->title }}</td>
                                                        <td>{{ $waste->material_name }}</td>
                                                        <td>{{ $waste->total_weight }}</td>
                                                        <td class="{{ $color }}">
                                                            <strong>{{ $status }}</strong>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-12 mt-3">
                                    <canvas id="wasteChartId" height="50" width="100"></canvas>
                                </div>
                                <script>
                                    const wasteLineChartCanvas = document.getElementById('wasteChartId').getContext('2d');
                                    const wasteLineChartLabels = {!! json_encode($monthsxyz) !!};
                                    const wasteLineChartDatasets = {!! json_encode($datasetsxyz) !!};

                                    const wasteLineChart = new Chart(wasteLineChartCanvas, {
                                        type: 'line',
                                        data: {
                                            labels: wasteLineChartLabels,
                                            datasets: wasteLineChartDatasets
                                        },
                                        options: {
                                            responsive: true,
                                            plugins: {
                                                title: {
                                                    display: true,
                                                    text: 'Waste Production (by Material Type)'
                                                },
                                                tooltip: {
                                                    mode: 'index',
                                                    intersect: false,
                                                }
                                            },
                                            interaction: {
                                                mode: 'nearest',
                                                axis: 'x',
                                                intersect: false
                                            },
                                            scales: {
                                                y: {
                                                    beginAtZero: true,
                                                    title: {
                                                        display: true,
                                                        text: 'Total Weight (kg)'
                                                    }
                                                },
                                                x: {
                                                    title: {
                                                        display: true,
                                                        text: 'Month'
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
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-header">
                                <p class="text-primary">Predictions & Analytics after 5 months (Linear Regression Model)</p>
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

                                                            $material = $materialTypes->firstWhere(
                                                                'id',
                                                                $row['material_type'],
                                                            );

                                                        @endphp
                                                        <tr>
                                                            <td>{{ $n++ }}</td>
                                                            <td>{{ $row['month'] ?? 'not specified' }}</td>
                                                            <td>{{ $row['title'] ?? 'not specified' }}</td>
                                                            <th>{{ $material->name ?? 'not specified' }}</th>
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
                                                <a href="{{ route('report.pdf') }}" class="btn btn-primary float-end"><i
                                                        class="ti ti-download"></i>
                                                    Download</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row card p-3 mt-5">
                                    <p class="text-primary">📈 Waste Generation Forecast (Next 5 Months)</p>
                                    <canvas id="predictionChart" height="400"></canvas>
                                    <script>
                                        const rawDataForWaste = @json($predictions);

                                        const barMonths = [...new Set(rawDataForWaste.map(item => item.month))];
                                        const barTitles = [...new Set(rawDataForWaste.map(item => item.title))];

                                        const barDatasets = barTitles.map(title => {
                                            const data = barMonths.map(month => {
                                                const found = rawDataForWaste.find(item => item.month === month && item.title === title);
                                                return found ? parseFloat(found.total_weight) : 0;
                                            });

                                            return {
                                                label: title,
                                                data: data,
                                                backgroundColor: '#' + Math.floor(Math.random() * 16777215).toString(16), // Random color
                                                borderWidth: 1
                                            };
                                        });

                                        // Step 3: Create the chart
                                        const ctxBar = document.getElementById('predictionChart').getContext('2d');
                                        new Chart(ctxBar, {
                                            type: 'bar',
                                            data: {
                                                labels: barMonths,
                                                datasets: barDatasets
                                            },
                                            options: {
                                                responsive: true,
                                                plugins: {
                                                    title: {
                                                        display: true,
                                                        text: 'Monthly Waste Production by Type'
                                                    }
                                                },
                                                scales: {
                                                    y: {
                                                        beginAtZero: true,
                                                        title: {
                                                            display: true,
                                                            text: 'Total Weight (kg)'
                                                        }
                                                    },
                                                    x: {
                                                        title: {
                                                            display: true,
                                                            text: 'Month'
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
                                        const wasteLineChartData = @json($predictions);

                                        const wasteLineMonths = [...new Set(wasteLineChartData.map(item => item.month))];
                                        const wasteLineTitles = [...new Set(wasteLineChartData.map(item => item.title))];

                                        const wasteLineDatasets = wasteLineTitles.map(materialTitle => {
                                            const datasetData = wasteLineMonths.map(month => {
                                                const match = wasteLineChartData.find(item => item.month === month && item.title ===
                                                    materialTitle);
                                                return match ? parseFloat(match.total_weight) : 0;
                                            });

                                            return {
                                                label: materialTitle,
                                                data: datasetData,
                                                fill: false,
                                                borderColor: '#' + Math.floor(Math.random() * 16777215).toString(16),
                                                tension: 0.3
                                            };
                                        });

                                        const ctxWasteLine = document.getElementById('predictionLineChart').getContext('2d');
                                        new Chart(ctxWasteLine, {
                                            type: 'line',
                                            data: {
                                                labels: wasteLineMonths,
                                                datasets: wasteLineDatasets
                                            },
                                            options: {
                                                responsive: true,
                                                plugins: {
                                                    title: {
                                                        display: true,
                                                        text: 'Predicted Monthly Waste Production by Material Type'
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
                                                            text: 'Month'
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

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-header">
                                <p class="text-primary">Predictions & Analytics after 5 months (Random Forest
                                    Model)</p>
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
                                                    @foreach ($randomForestData as $row)
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
                                                            $material = $materialTypes->firstWhere(
                                                                'id',
                                                                $row['material_type'],
                                                            );
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $n++ }}</td>
                                                            <td>{{ $row['month'] ?? 'not specified' }}</td>
                                                            <td>{{ $row['title'] ?? 'not specified' }}</td>
                                                            <th>{{ $material->name ?? 'not specified' }}</th>
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
                                                <a href="{{ route('report.pdf') }}" class="btn btn-primary float-end"><i
                                                        class="ti ti-download"></i>
                                                    Download</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row card p-3 mt-5">
                                    <p class="text-primary">📈 Waste Generation Forecast (Next 5 Months) - Random Forest
                                        Model</p>
                                    <canvas id="rfPredictionChart" height="400"></canvas>
                                    <script>
                                        const rfLineDataSet = @json($randomForestData);

                                        const rfLineMonths = rfLineDataSet.map(item => item.month);
                                        const rfLineWeights = rfLineDataSet.map(item => item.total_weight);

                                        const rfLineCtx = document.getElementById('rfPredictionChart').getContext('2d');

                                        const rfLineChartInstance = new Chart(rfLineCtx, {
                                            type: 'line',
                                            data: {
                                                labels: rfLineMonths,
                                                datasets: [{
                                                    label: 'Predicted Waste (Line - Random Forest)',
                                                    data: rfLineWeights,
                                                    fill: false,
                                                    borderColor: 'rgba(255, 159, 64, 1)',
                                                    backgroundColor: 'rgba(255, 159, 64, 0.2)',
                                                    tension: 0.3,
                                                    pointRadius: 5,
                                                    pointHoverRadius: 7,
                                                    borderWidth: 2,
                                                }]
                                            },
                                            options: {
                                                responsive: true,
                                                plugins: {
                                                    legend: {
                                                        display: true,
                                                        position: 'top'
                                                    },
                                                    tooltip: {
                                                        enabled: true
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
                                                            text: 'Month'
                                                        }
                                                    }
                                                }
                                            }
                                        });
                                    </script>


                                </div>
                                <div class="row card p-3 mt-5">
                                    <p class="text-primary">📈 Bar Chart</p>
                                    <canvas id="rfPredictionBarChart" height="400"></canvas>

                                    <script>
                                        const rfBarDataSet = @json($randomForestData);

                                        const rfBarMonths = rfBarDataSet.map(entry => entry.month);
                                        const rfBarWeights = rfBarDataSet.map(entry => entry.total_weight); // FIXED key

                                        const rfBarCanvas = document.getElementById('rfPredictionBarChart').getContext('2d');

                                        const rfBarChartInstance = new Chart(rfBarCanvas, {
                                            type: 'bar',
                                            data: {
                                                labels: rfBarMonths,
                                                datasets: [{
                                                    label: 'Predicted Waste (Bar - Random Forest)',
                                                    data: rfBarWeights,
                                                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                                                    borderColor: 'rgba(54, 162, 235, 1)',
                                                    borderWidth: 1,
                                                    borderRadius: 5,
                                                }]
                                            },
                                            options: {
                                                responsive: true,
                                                plugins: {
                                                    legend: {
                                                        display: true,
                                                        position: 'top'
                                                    },
                                                    tooltip: {
                                                        enabled: true
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
                                                            text: 'Month'
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
