@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div class="mb-2">
                            <h5 class="mb-0">
                                {{ strtoupper($userAuth->name) }}
                            </h5>
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
                            <!-- CREATE SELECT AND LOAD OPTIONS WITH VARIABLE PHP $events:id, name -->
                            <div class="form-group">
                                <label for="event">Evento</label>
                                <input type="text" class="form-control" id="event" name="event" placeholder="Evento" disabled value="{{ $inventory->event->name }}">
                             </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6-col-sm-12 col-xs-12">
                            <!-- CREATE SELECT AND LOAD OPTIONS WITH VARIABLE PHP $technologies :id, name -->
                            <div class="form-group">
                                <label for="technology">Tecnología</label>
                                <input type="text" class="form-control" id="event" name="event" placeholder="Evento" disabled value="{{ $inventory->technology->name }}">
                            </div>
                        </div>
                    </div>
                    <!-- ROUTE & TECHNICAL -->
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6-col-sm-12 col-xs-12">
                            <!-- CREATE SELECT AND LOAD OPTIONS WITH VARIABLE PHP $routes :id, name -->
                            <div class="form-group">
                                <label for="route">Ruta</label>
                                <input type="text" class="form-control" id="event" name="event" placeholder="Evento" disabled value="{{ $inventory->route->name }}">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6-col-sm-12 col-xs-12">
                            <!-- CREATE SELECT AND LOAD OPTIONS WITH VARIABLE PHP $technicals :id, name -->
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
                                    <th>Descripción</th>
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
                                            <td>{{ $detail->material->description }}</td>
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
                    <div class="row mt-5">
                        <div class="col-12" style="text-align: center;">
                            <a href="{{ route('inventory') }}" class="btn btn-secondary">Regresar</a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
    var details = {{ json_encode($inventory_details) }};
</script>

<script src="{{ asset('assets/js/jquery-3.7.0.js') }}"></script>

<script src="{{ asset('js/pages/show_details.js') }}"></script>

@endsection
