@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                        <div class="mb-2 mb-md-0"> <!-- Utilizamos mb-2 para agregar margen inferior en dimensiones mayores o iguales a 900 -->
                            <h5 class="mb-0">
                                {{ strtoupper($userAuth->name) }}
                            </h5>
                        </div>
                        <div class="d-flex justify-content-md-end mt-2 mt-md-0"> <!-- Utilizamos mt-2 para agregar margen superior en dimensiones mayores o iguales a 900 -->
                            <form action="{{ route('inventory.exports', ['id' => $inventory->id]) }}" method="get" class="mr-2"> <!-- Agregamos la clase mr-2 para separar los botones -->
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-file-excel"></i> Exportar
                                </button>
                            </form>
                            <a href="{{ route('inventory') }}" class="btn btn-secondary" style="margin-left: 10px !important;">Regresar</a>
                        </div>
                    </div>
                </div>


                <form class="m-4">
                    <!-- DATE -->
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6-col-sm-12 col-xs-12">
                            <div class="form-group">
                                <h4>Fecha de Ingreso: {{ \Carbon\Carbon::parse($inventory->date)->format('d/m/Y') }} </h4>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6-col-sm-12 col-xs-12">
                            <!-- creado por -->
                            <div class="form-group">
                                <h4>Creado por: {{ strtoupper($inventory->creator_user->name) }} </h4>
                            </div>
                        </div>
                    </div>
                    <!-- EVENT & TECNOLOGY -->
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6-col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="event">Evento</label>
                                <input type="text" class="form-control" id="event" name="event" placeholder="Evento" disabled value="{{ $inventory->event->name }}">
                             </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6-col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="route">Ruta</label>
                                <input type="text" class="form-control" id="event" name="event" placeholder="Evento" disabled value="{{ $inventory->route->name }}">
                            </div>

                        </div>
                    </div>
                    <!-- ROUTE & TECHNICAL -->
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6-col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="technology">Tecnología</label>
                                <input type="text" class="form-control" id="event" name="event" placeholder="Evento" disabled value="{{ $inventory->technology->name }}">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6-col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="technical">Técnico</label>
                                <input type="text" class="form-control" id="event" name="event" placeholder="Evento" disabled value="{{ $inventory->user->name }}">
                            </div>
                        </div>
                    </div>
                    <!-- DETAILS IN TABLE -->
                    <hr>
                    <h6 class="text-center">Detalle</h6>
                    <div class="container">
                        <div class="table-responsive">
                            <table class="table" id="tablaMateriales">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Código</th>
                                    <th>Stock Anterior</th>
                                    <th>Cantidad</th>
                                    <th>Nuevo Stock</th>
                                    <th>Serial</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($inventory_details as $index => $detail)
                                        <tr>
                                            <td>{{ $index+1 }}</td>
                                            <td>{{ $detail->material->name }}</td>
                                            <td>{{ $detail->old_stock }}</td>
                                            <td>{{ $detail->count }}</td>
                                            <td>{{ $detail->new_stock }}</td>
                                            <td><?= $detail->series ?></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr class="mt-2 mb-2">
                    <!-- btn guardar y cancelar, el cancelar regresar a la ruta inventory, position center -->


                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/jquery-3.7.0.js') }}"></script>


@endsection
