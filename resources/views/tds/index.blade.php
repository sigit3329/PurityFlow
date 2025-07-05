@extends('layout.app')
@section('container')

<div class="container">
    <h1 class="mt-5 mb-4 text-primary text-center">TDS Dashboard</h1>
    <div class="row gx-lg-4">

        <!-- Layout 1: Data TDS Realtime -->
        <div class="col-lg-6 mb-4">
            <div class="dashboard-item bg-white shadow-sm rounded h-100">
                <h2 class="text-primary text-center p-3">Data TDS Realtime</h2>
                <div class="tds-value-container text-center mt-5">
                    @if ($latestTdsData)
                        <h1 class="tds-value">{{ $latestTdsData->tds_value }} ppm </h1>
                    @else
                        <h1 class="tds-value">No data available</h1>
                    @endif
                </div>
            </div>
        </div>

        <!-- Layout 2: Total Galon -->
        <div class="col-lg-6 mb-4">
            <div class="dashboard-item bg-white shadow-sm rounded h-100">
                <h2 class="text-primary text-center p-3">Total Galon</h2>
                <h4 class="text-center mt-5">Total Galon yang sudah digunakan:</h4>
                <h1 class="text-center">{{ $totalGalon }} / 250 galon</h1>
                <div class="progress mt-3">
                    <div id="progressBar" class="progress-bar bg-primary" role="progressbar" style="width: {{ $totalGalon / 250 * 100 }}%" aria-valuenow="{{ $totalGalon }}" aria-valuemin="0" aria-valuemax="250"></div>
                </div>
            </div>
        </div>

        <!-- Layout 3: Grafik TDS -->
        <div class="col-lg-6 mb-4">
            <div class="dashboard-item bg-white shadow-sm rounded h-100">
                <h2 class="text-primary text-center p-3">Grafik TDS</h2>
                <div class="btn-group d-flex justify-content-center" role="group">
                    <button class="btn btn-outline-primary" onclick="filterData('daily')">Harian</button>
                    <button class="btn btn-outline-primary" onclick="filterData('weekly')">Mingguan</button>
                    <button class="btn btn-outline-primary" onclick="filterData('monthly')">Bulanan</button>
                </div>
                <canvas id="tdsChart" width="400" height="300"></canvas>
            </div>
        </div>

        <!-- Layout 4: Data Kualitas Air -->
        <div class="col-lg-6 mb-4">
            <div class="dashboard-item bg-white shadow-sm rounded h-100">
                <h2 class="text-primary text-center p-3">Data Kualitas Air</h2>
                <div class="tds-value-container text-center mt-5">
                    <h3>Kualitas air saat ini:</h3>
                    @if ($latestTdsData)
                        @php
                            $quality = $latestTdsData->quality;
                            $qualityClass = '';

                            switch($quality) {
                                case 'Terbaik':
                                    $qualityClass = 'text-terbaik';
                                    break;
                                case 'Baik':
                                    $qualityClass = 'text-baik';
                                    break;
                                case 'Cukup':
                                    $qualityClass = 'text-cukup';
                                    break;
                                case 'Buruk':
                                    $qualityClass = 'text-buruk';
                                    break;
                                case 'Warning':
                                    $qualityClass = 'text-warning';
                                    break;
                            }
                        @endphp
                        <h3 class="tds-value {{ $qualityClass }}"> {{ $latestTdsData->quality }}</h3>
                    @else
                        <h1 class="tds-value">No data available</h1>
                    @endif
                </div>
                <table class="table table-bordered table-hover mt-5">
                    <thead class="thead-dark">
                        <tr>
                            <th>Range TDS Value</th>
                            <th>Kualitas Air</th>
                            <th>Warna</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>0 - 50</td>
                            <td>Terbaik</td>
                            <td class="background-terbaik"></td>
                        </tr>
                        <tr>
                            <td>51 - 150</td>
                            <td>Terbaik</td>
                            <td class="background-terbaik"></td>
                        </tr>
                        <tr>
                            <td>151 - 250</td>
                            <td>Baik</td>
                            <td class="background-baik"></td>
                        </tr>
                        <tr>
                            <td>251 - 300</td>
                            <td>Cukup</td>
                            <td class="background-cukup"></td>
                        </tr>
                        <tr>
                            <td>301 - 500</td>
                            <td>Buruk</td>
                            <td class="background-buruk"></td>
                        </tr>
                        <tr>
                            <td>Above 500</td>
                            <td>Warning</td>
                            <td class="background-warning"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Script untuk Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let originalLabels = @json($labels);
    let originalData = @json($chartData);

    const data = {
        labels: originalLabels,
        datasets: [{
            label: 'TDS Value',
            backgroundColor: 'blue',
            borderColor: 'blue',
            data: originalData,
            fill: false,
        }]
    };

    const config = {
        type: 'line',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Grafik Data TDS Bulanan/Mingguan/Harian' // Judul awal
                }
            }
        },
    };

    var myChart = new Chart(
        document.getElementById('tdsChart'),
        config
    );

    function filterData(range) {
        let filteredLabels = [];
        let filteredData = [];

        if (range === 'daily') {
            filteredLabels = originalLabels;
            filteredData = originalData;
            myChart.options.plugins.title.text = 'Grafik Data TDS Harian'; // Update judul
        } else if (range === 'weekly') {
            for (let i = 0; i < originalLabels.length; i += 7) {
                filteredLabels.push(originalLabels[i]);
                filteredData.push(originalData[i]);
            }
            myChart.options.plugins.title.text = 'Grafik Data TDS Mingguan'; // Update judul
        } else if (range === 'monthly') {
            for (let i = 0; i < originalLabels.length; i += 30) {
                filteredLabels.push(originalLabels[i]);
                filteredData.push(originalData[i]);
            }
            myChart.options.plugins.title.text = 'Grafik Data TDS Bulanan'; // Update judul
        }

        myChart.data.labels = filteredLabels;
        myChart.data.datasets[0].data = filteredData;
        myChart.update();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const progressBar = document.getElementById('progressBar');
        const totalGalon = {{ $totalGalon }};
        const maxGalon = 250;

        // Hitung persentase progress
        const percentage = (totalGalon / maxGalon) * 100;

        // Set width dan aria-valuenow pada progress bar
        progressBar.style.width = `${percentage}%`;
        progressBar.setAttribute('aria-valuenow', totalGalon);

        // Ubah warna progress bar jika mendekati 250 galon
        if (totalGalon >= 200) { // Misalnya ubah warna saat mendekati 200 galon
            progressBar.classList.remove('bg-primary'); // Hapus kelas bg-primary
            progressBar.classList.add('bg-danger');    // Tambahkan kelas bg-danger
        }
    });
</script>

@endsection
