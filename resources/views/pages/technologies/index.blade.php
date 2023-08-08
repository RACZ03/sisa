@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4 shadow" style="border: none;">
                <div class="card-header pb-0" style="background: #fff; border: none;">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Tecnologías</h5>
                        </div>
                        <a href="#" class="btn bg-gradient-primary btn-sm mb-0" type="button" onclick="onCreate()">
                            +&nbsp; Nuevo
                        </a>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2 m-2">
                    <div class="table-responsive p-0 mt-4">
                        <table id="technologies-table" class="table align-items-center mb-0" style="border-bottom: 2px solid rgb(198, 190, 190) !important;">
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
                                @foreach ($technologies as $index => $technology)
                                    <tr data-technology-id="{{ $technology->id }}" data-technology-code="{{ $technology->code }}" data-technology-name="{{ $technology->name }}"  data-technology-description="{{ $technology->description }}">
                                        <td class="ps-4">
                                            <p class="text-secondary text-xs font-weight-bold mb-0"> {{ $index + 1 }} </p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-secondary text-xs font-weight-bold mb-0"> {{ $technology->code }} </p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-secondary text-xs font-weight-bold mb-0"> {{ $technology->name }} </p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-secondary text-xs font-weight-bold mb-0"> {{ $technology->description }} </p>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-secondary text-xs font-weight-bold">
                                                {{ \Carbon\Carbon::parse($technology->created_at)->format('d/m/Y') }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-secondary text-xs font-weight-bold">
                                                {{ \Carbon\Carbon::parse($technology->updated_at)->format('d/m/Y') }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="#" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Editar Tecnologóa" onclick="onEdit(this)">
                                                <i class="fas fa-user-edit text-secondary"></i>
                                            </a>
                                            <a href="#" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Eliminar Tecnologóa" onclick="onDelete(this)">
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
<div class="modal fade" id="newTechnologyModal" tabindex="-1" aria-labelledby="newTechnologyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newTechnologyModalLabel">Nuevo Registro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form  id="technologyForm">
                    <!-- input id hidden -->
                    <input type="hidden" id="technology_id" name="technology_id" value="">

                    <div class="mb-2">
                        <label for="name" class="form-label">Código</label>
                        <input type="text" class="form-control" id="code" name="code" required oninput="convertToUpperCase(this)">
                    </div>

                    <div class="mb-2">
                        <label for="name" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="mb-2">
                        <label for="name" class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="description" required></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="saveTechnologyBtn">Guardar</button>
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

<script src="{{ asset('js/pages/technologies.js') }}"></script>

@endsection
