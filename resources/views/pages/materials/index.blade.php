@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4 shadow" style="border: none;">
                <div class="card-header pb-0" style="background: #fff; border: none;">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Materiales</h5>
                        </div>
                        <a href="#" class="btn bg-gradient-primary btn-sm mb-0" type="button" onclick="onCreate()">
                            +&nbsp; Nuevo
                        </a>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2 m-2">
                    <div class="table-responsive p-0 mt-4">
                        <table id="materials-table" class="table align-items-center mb-0" style="border-bottom: 2px solid rgb(198, 190, 190) !important;">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        #
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Tecnología
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
                                        Stock
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Posee Serie
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Fecha Creación
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($materials as $index => $material)
                                    <tr data-material-id="{{ $material->id }}" data-material-code="{{ $material->code }}" data-material-name="{{ $material->name }}" data-material-description="{{ $material->description }}" data-material-technology="{{ $material->technology->id }}" data-material-stock="{{ $material->stock }}" data-material-has-series="{{ $material->has_series }}">
                                        <td class="ps-4">
                                            <p class="text-secondary text-xs font-weight-bold mb-0"> {{ $index + 1 }} </p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-secondary text-xs font-weight-bold mb-0"> {{ $material->technology->name }} </p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-secondary text-xs font-weight-bold mb-0"> {{ $material->code }} </p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-secondary text-xs font-weight-bold mb-0"> {{ $material->name }} </p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-secondary text-xs font-weight-bold mb-0"> {{ $material->description }} </p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-secondary text-xs font-weight-bold mb-0"> {{ $material->stock }} </p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-secondary text-xs font-weight-bold mb-0">
                                                @if ($material->has_series == 1)
                                                    <i class="fas fa-check-circle text-success"></i>
                                                @else
                                                    <i class="fas fa-times-circle text-danger"></i>
                                                @endif
                                            </p>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-secondary text-xs font-weight-bold">
                                                {{ \Carbon\Carbon::parse($material->created_at)->format('d/m/Y') }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="#" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Editar Material" onclick="onEdit(this)">
                                                <i class="fas fa-user-edit text-secondary"></i>
                                            </a>
                                            <a href="#" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Eliminar Material" onclick="onDelete(this)">
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
<div class="modal fade" id="newMaterialModal" tabindex="-1" aria-labelledby="newMaterialModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newMaterialModalLabel">Nuevo Registro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form  id="userForm">
                    <!-- input id hidden -->
                    <input type="hidden" id="material_id" name="material_id" value="">
                    <div class="mb-2">
                        <label for="technology" class="form-label">Tecnología</label>
                        <select class="form-select" id="technologyMaterial" name="technology" required>
                            <option value="" selected disabled>Seleccione una tecnología</option>
                            @foreach ($technologies as $technology)
                                <option value="{{ $technology->id }}">{{ $technology->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label for="code" class="form-label">Código</label>
                        <input type="text" class="form-control" id="codeMaterial" name="code" required oninput="convertToUpperCase(this)">
                    </div>
                    <div class="mb-2">
                        <label for="name" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nameMaterial" name="name" required>
                    </div>
                    <div class="mb-2">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="descriptionMaterial"></textarea>
                    </div>
                    <!-- div stock required number -->
                    <div class="mb-2">
                        <label for="stock" class="form-label">Stock</label>
                        <input type="number" class="form-control" id="stockMaterial" name="stock" required>
                    </div>
                    <!-- div has_series required radio -->
                    <div class="form-check mt-2 mb-2">
                        <input class="form-check-input" type="checkbox" id="has_seriesMaterial" name="has_series" value="1">
                        <label class="form-check-label" for="has_series">
                            Tiene series
                        </label>
                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="saveMaterialBtn">Guardar</button>
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

<script src="{{ asset('js/pages/materials.js') }}"></script>

@endsection
