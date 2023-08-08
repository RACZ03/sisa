@extends('layouts.user_type.auth')

@section('content')
<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4 shadow" style="border: none;">
                <div class="card-header pb-0" style="background: #fff; border: none;">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Rutas</h5>
                        </div>
                        <a href="#" class="btn bg-gradient-primary btn-sm mb-0" type="button" onclick="onCreate()">
                            +&nbsp; Nuevo
                        </a>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2 m-2">
                    <div class="table-responsive p-0 mt-4">
                        <table id="route-table" class="table align-items-center mb-0" style="border-bottom: 2px solid rgb(198, 190, 190) !important;">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        #
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Código
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Nombre
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Descripción
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Tecnico
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Fecha Creación
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Fecha Actualización
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($routes as $index => $route)
                                    <tr data-route-id="{{ $route->id }}" data-route-code="{{ $route->code }}" data-route-name="{{ $route->name }} "  data-route-description="{{ $route->description }}" data-route-user="{{$route->user->id}}">
                                        <td class="ps-4">
                                            <p class="text-secondary text-xs font-weight-bold mb-0"> {{ $index + 1 }} </p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-secondary text-xs font-weight-bold mb-0"> {{ $route->code }} </p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-secondary text-xs font-weight-bold mb-0"> {{ $route->name }} </p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-secondary text-xs font-weight-bold mb-0"> {{ $route->description }} </p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-secondary text-xs font-weight-bold mb-0"> {{ $route->user->name }} </p>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-secondary text-xs font-weight-bold">
                                                {{ \Carbon\Carbon::parse($route->created_at)->format('d/m/Y') }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-secondary text-xs font-weight-bold">
                                                {{ \Carbon\Carbon::parse($route->updated_at)->format('d/m/Y') }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="#" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Editar Rutas" onclick="onEdit(this)">
                                                <i class="fas fa-user-edit text-secondary"></i>
                                            </a>
                                            <a href="#" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Eliminar Rutas" onclick="onDelete(this)">
                                                <i class="cursor-pointer fas fa-trash text-secondary"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- FORM ADD NEW ELEMENTS -->
<div class="modal fade" id="newRouteModal" tabindex="-1" aria-labelledby="newRouteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newRouteModalLabel">Nuevo Registro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form  id="routeForm">
                    <!-- input id hidden -->
                    <input type="hidden" id="route_id" name="route_id" value="">
                    <div class="mb-2">
                        <label for="name" class="form-label">Código</label>
                        <input type="text" class="form-control" id="code" name="code" required oninput="convertToUpperCase(this)">
                    </div>
                    <div class="mb-2">
                        <label for="name" class="form-label">Ruta</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="mb-2">
                        <label for="name" class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="description" required></textarea>
                    </div>
                    <div class="mb-2">
                        <label for="route" class="form-label">Tecnico</label>
                        <select class="form-select" id="user" name="user" required>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="saveRouteBtn">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- SCRIPTS -->
<script src="{{ asset('assets/js/jquery-3.7.0.js') }}"></script>
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/plugins/jquery.inputmask.min.js') }}"></script>
<script src="{{ asset('js/datatables-config.js') }}"></script>
<script src="{{ asset('js/plugins/toastr.min.js') }}"></script>
<script src="{{ asset('js/plugins/seetalert2.all.min.js') }}"></script>


<script>
    // Creamos una variable global para almacenar el valor de csrf_token
    window.csrfToken = '{{ csrf_token() }}';
</script>

<script src="{{ asset('js/pages/route.js') }}"></script>

@endsection
