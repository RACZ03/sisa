@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4 shadow" style="border: none;">
                <div class="card-header pb-0" style="background: #fff; border: none;">
                    <div class="d-flex flex-row justify-content-between mb-2">
                        <div>
                            <h5 class="mb-0">Generar Reporte</h5>
                        </div>
                    </div>
                    <div class="align-self-end">
                        <div class="row">
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12 mb-2">
                                <label for="date_range">Rango de Fecha:</label>
                                <input type="text" id="date_range" class="form-control">

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12 mb-2">
                                <label for="event">Evento:</label>
                                <select id="event"class="form-control select2" multiple data-live-search="true">
                                    @foreach($events as $event)
                                        <option value="{{ $event->id }}">{{ $event->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12 mb-2">
                                <label for="technology">Tecnología:</label>
                                <select id="technology" class="form-control select2" multiple data-live-search="true">
                                    @foreach($technologies as $technology)
                                        <option value="{{ $technology->id }}">{{ $technology->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12 mb-2">
                                <label for="technical">Técnico:</label>
                                <select id="technical" class="form-control select2" multiple data-live-search="true">
                                    <option value="all">Seleccionar Todos</option>
                                    @foreach($technicals as $technical)
                                        <option value="{{ $technical->id }}">{{ $technical->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <button type="button" class="btn btn-primary" style="margin-top: 30px;" onclick="buscarInformacion()">Buscar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card-body px-0 pt-0 pb-2 mt-2">
                    <div class="table-responsive p-0">
                        <table id="reports-table" class="table align-items-center mb-0" style="border-bottom: 2px solid rgb(198, 190, 190) !important;">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        #
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Fecha
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Actividad
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Tecnología
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Codigo Mat.
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Nombre
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Stock Anterior
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Carga
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Débito
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Stock Nuevo
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Técnico
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Serie Cargada
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Serie Debitada
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- pass crtoken -->
<script>
    const csrfToken = @JSON(csrf_token());
</script>


<script src="{{ asset('assets/js/jquery-3.7.0.js') }}"></script>

<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>


<script src="{{ asset('js/datatables-config.js') }}"></script>
<script src="{{ asset('js/plugins/toastr.min.js') }}"></script>
<script src="{{ asset('js/plugins/moment.min.js') }}"></script>

<!-- import select2 -->
<script src="{{ asset('js/plugins/select2.min.js') }}"></script>

<!-- import datepicker -->
<script src="{{ asset('js/plugins/daterangepicker.min.js') }}"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.6.0/jszip.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/pdfmake@0.1.70/build/pdfmake.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/pdfmake/build/vfs_fonts.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">

<script src="{{ asset('js/pages/reports.js') }}"></script>

@endsection
