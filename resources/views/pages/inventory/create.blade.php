@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div class="mb-2">
                            <h5 class="mb-0">Nuevo Registro</h5>
                        </div>
                    </div>
                </div>

                <form class="m-4">
                    <!-- DATE -->
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6-col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="date">Fecha de Ingreso</label>
                                <input type="date" class="form-control" id="date" name="date" placeholder="Fecha de Ingreso" required>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6-col-sm-12 col-xs-12"></div>
                    </div>
                    <!-- EVENT & TECNOLOGY -->
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6-col-sm-12 col-xs-12">
                            <!-- CREATE SELECT AND LOAD OPTIONS WITH VARIABLE PHP $events:id, name -->
                            <div class="form-group">
                                <label for="event">Evento</label>
                                <select class="form-control" id="event" name="event" required>
                                    <option value="" selected disabled>Seleccione un evento</option>
                                    @foreach ($events as $event)
                                        <option value="{{ $event->id }}">{{ $event->name }}</option>
                                    @endforeach
                                </select>
                             </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6-col-sm-12 col-xs-12">
                            <!-- CREATE SELECT AND LOAD OPTIONS WITH VARIABLE PHP $technologies :id, name -->
                            <div class="form-group">
                                <label for="technology">Tecnología</label>
                                <select class="form-control" id="technology" name="technology" required>
                                    <option value="" selected disabled>Seleccione una tecnología</option>
                                    @foreach ($technologies as $technology)
                                        <option value="{{ $technology->id }}">{{ $technology->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- ROUTE & TECHNICAL -->
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6-col-sm-12 col-xs-12">
                            <!-- CREATE SELECT AND LOAD OPTIONS WITH VARIABLE PHP $routes :id, name -->
                            <div class="form-group">
                                <label for="route">Ruta</label>
                                <select class="form-control" id="route" name="route" required>
                                    <option value="" selected disabled>Seleccione una ruta</option>
                                    @foreach ($routes as $route)
                                        <option value="{{ $route->id }}">{{ $route->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6-col-sm-12 col-xs-12">
                            <!-- CREATE SELECT AND LOAD OPTIONS WITH VARIABLE PHP $technicals :id, name -->
                            <div class="form-group">
                                <label for="technical">Técnico</label>
                                <select class="form-control" id="technical" name="technical" required>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- DETAILS IN TABLE -->
                    <hr>
                    <h6>Detalle</h6>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-4 mb-2">
                            <select class="form-select" id="materialSelect">
                            </select>
                            </div>
                            <div class="col-md-2">
                            <button class="btn btn-primary mb-2" id="btnAgregar" type="button" >Agregar</button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table" id="tablaMateriales">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Código</th>
                                    <th>Descripción</th>
                                    <th>Cantidad</th>
                                    <th>Serial</th>
                                </tr>
                                </thead>
                                <tbody>
                                <!-- Aquí se agregarán las filas dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr class="mt-2 mb-2">
                    <!-- btn guardar y cancelar, el cancelar regresar a la ruta inventory, position center -->
                    <div class="row mt-5">
                        <div class="col-12" style="text-align: center;">
                            <a href="{{ route('inventory') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="button" class="btn btn-primary" id="btnSaveInventory">Guardar</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<!-- pasar materiales al javascript -->
<script>
    var materiales = @json($materials);
    var routes = @json($routes);
    var technicals = @json($technicals);
</script>

<script src="{{ asset('assets/js/jquery-3.7.0.js') }}"></script>

<script src="{{ asset('js/plugins/toastr.min.js') }}"></script>
<script src="{{ asset('js/plugins/seetalert2.all.min.js') }}"></script>
<script src="{{ asset('js/datatables-config.js') }}"></script>

<script src="{{ asset('js/pages/details_inventory.js') }}"></script>

@endsection
