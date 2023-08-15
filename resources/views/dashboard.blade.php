@extends('layouts.user_type.auth')

@section('content')

  <div class="row">
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Usuarios</p>
                <h5 class="font-weight-bolder mb-0">
                    {{ count($users) }}
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
                <a href="{{ url('users') }}" class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                    <i class="fa fa-users text-lg opacity-10"></i>
                </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Materiales</p>
                <h5 class="font-weight-bolder mb-0">
                    {{ count($materials) }}
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
                <a href="{{ url('materials') }}" class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                    <i class="fa fa-list text-lg opacity-10"></i>
                </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Tecnologías</p>
                <h5 class="font-weight-bolder mb-0">
                    {{ count($technologies) }}
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
                <a href="{{ url('technologies') }}" class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                    <i class="fa fa-ethernet text-lg opacity-10"></i>
                </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Rutas</p>
                <h5 class="font-weight-bolder mb-0">
                    {{ count($routes) }}
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
                <a href="{{ url('routes') }}" class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                    <i class="fa fa-truck text-lg opacity-10"></i>
                </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row mt-4 mb-5">
    <div class="col-lg-8 mx-auto">
      <div class="card z-index-2">
        <div class="card-header pb-0">
          <h6>Inventarios</h6>
        </div>
        <div class="card-body p-3">
          <div class="chart">
            <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>


@endsection
@push('dashboard')
  <script>
    window.onload = function() {

        var ctx2 = document.getElementById("chart-line").getContext("2d");

        var gradientStroke1 = ctx2.createLinearGradient(0, 230, 0, 50);

        gradientStroke1.addColorStop(1, 'rgba(203,12,159,0.2)');
        gradientStroke1.addColorStop(0.2, 'rgba(72,72,176,0.0)');
        gradientStroke1.addColorStop(0, 'rgba(203,12,159,0)'); //purple colors

        var gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);

        gradientStroke2.addColorStop(1, 'rgba(20,23,39,0.2)');
        gradientStroke2.addColorStop(0.2, 'rgba(72,72,176,0.0)');
        gradientStroke2.addColorStop(0, 'rgba(20,23,39,0)'); //purple colors

        let chartData = @json($chartData);

        let labels = [];
        let loadData = [];
        let debitData = [];
        for (const [key, value] of Object.entries(chartData)) {
            // convert key to date
            let date = new Date(key);
            // convert date and get name of full month
            let month = date.toLocaleString('default', { month: 'long' });
            labels.push(month);
            loadData.push(value.LOAD);
            debitData.push(value.DEBIT);
        }

        new Chart(ctx2, {
            type: "line",
            data: {
                labels: labels,
                datasets: [
                    {
                        label: "Carga",
                        tension: 0.4,
                        borderWidth: 0,
                        pointRadius: 0,
                        borderColor: "#cb0c9f",
                        borderWidth: 3,
                        backgroundColor: gradientStroke1,
                        fill: true,
                        data: loadData,
                        maxBarThickness: 6,
                    },
                    {
                        label: "Debito",
                        tension: 0.4,
                        borderWidth: 0,
                        pointRadius: 0,
                        borderColor: "#3A416F",
                        borderWidth: 3,
                        backgroundColor: gradientStroke2,
                        fill: true,
                        data: debitData,
                        maxBarThickness: 6,
                    },
                ],
            },
            options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                display: false,
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
            scales: {
                y: {
                grid: {
                    drawBorder: false,
                    display: true,
                    drawOnChartArea: true,
                    drawTicks: false,
                    borderDash: [5, 5]
                },
                ticks: {
                    display: true,
                    padding: 10,
                    color: '#b2b9bf',
                    font: {
                    size: 11,
                    family: "Open Sans",
                    style: 'normal',
                    lineHeight: 2
                    },
                }
                },
                x: {
                grid: {
                    drawBorder: false,
                    display: false,
                    drawOnChartArea: false,
                    drawTicks: false,
                    borderDash: [5, 5]
                },
                ticks: {
                    display: true,
                    color: '#b2b9bf',
                    padding: 20,
                    font: {
                    size: 11,
                    family: "Open Sans",
                    style: 'normal',
                    lineHeight: 2
                    },
                }
                },
            },
            },
        });
    }
  </script>
@endpush

