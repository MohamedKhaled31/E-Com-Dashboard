@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">

    <div class="row">

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Purchases (Month)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($totalPurchaseThisMonth, 2) }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-shopping-basket fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Sales (Month)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($totalSalesThisMonth, 2) }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-dollar-sign fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Stock Value</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($totalStockValue, 2) }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-boxes fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Sales Target (${{ number_format($salesTarget, 0) }})</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ number_format($targetPercentage, 1) }}%</div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-warning" role="progressbar"
                                             style="width: {{ $progressPercentage }}%"
                                             aria-valuenow="{{ $progressPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto"><i class="fas fa-bullseye fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Daily Sales Chart (This Month)</h6></div>
                <div class="card-body">
                    <div class="chart-area"><canvas id="dailySalesChart"></canvas></div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Sales Share by Category</h6></div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2"><canvas id="categoryPieChart"></canvas></div>
                    <div class="mt-4 text-center small" id="js-pie-legend"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
<script>

    var ctxArea = document.getElementById("dailySalesChart");
    new Chart(ctxArea, {
        type: 'line',
        data: {
            labels: @json($chartDaysLabels),
            datasets: [{
                label: "Sales Amount",
                lineTension: 0.3,
                backgroundColor: "rgba(78, 115, 223, 0.05)",
                borderColor: "rgba(78, 115, 223, 1)",
                pointRadius: 3,
                pointBackgroundColor: "rgba(78, 115, 223, 1)",
                data: @json($chartDaysValues),
            }],
        },
        options: { maintainAspectRatio: false }
    });

    var ctxPie = document.getElementById("categoryPieChart");
    var labelsData = @json($categoryLabels);
    var totalsData = @json($categoryTotals);
    var colors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'];

    new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: labelsData.length > 0 ? labelsData : ["No Sales"],
            datasets: [{
                data: totalsData.length > 0 ? totalsData : [100],
                backgroundColor: colors.slice(0, labelsData.length > 0 ? labelsData.length : 1),
            }],
        },
        options: { maintainAspectRatio: false, cutoutPercentage: 80, legend: { display: false } }
    });

    var legend = document.getElementById("js-pie-legend");
    if(labelsData.length > 0) {
        labelsData.forEach(function(label, index) {
            legend.innerHTML += `<span class="mr-2"><i class="fas fa-circle" style="color:${colors[index]}"></i> ${label}</span>`;
        });
    } else {
        legend.innerHTML = `<span>No data recorded for categories.</span>`;
    }
</script>
@endsection
