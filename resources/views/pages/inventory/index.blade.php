@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4 shadow" style="border: none;">
                <div class="card-header pb-0" style="background: #fff; border: none;">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Inventario</h5>
                        </div>
                        <a href="{{ route('inventory.create') }}" class="btn bg-gradient-primary btn-sm mb-0" type="button">+&nbsp; Nuevo</a>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table id="inventories-table" class="table align-items-center mb-0" style="border-bottom: 2px solid rgb(198, 190, 190) !important;">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        #
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Código
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Fecha de Ingreso
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Evento
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Tecnología
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Ruta
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Técnico
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Creado por
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Estado
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inventories as $index => $inventory)
                                    <tr data-inventory-id="{{ $inventory->id }}" data-inventory-state="{{ $inventory->state->code }}" data-inventory-code="{{ $inventory->code }}">
                                        <td class="ps-4">
                                            <p class="text-secondary text-xs font-weight-bold mb-0"> {{ $index + 1 }} </p>
                                        </td>
                                        <td class="ps-4">
                                            <p class="text-secondary text-xs font-weight-bold mb-0"> {{ $inventory->code }} </p>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-secondary text-xs font-weight-bold">
                                                {{ \Carbon\Carbon::parse($inventory->date)->format('d/m/Y') }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-secondary text-xs font-weight-bold">
                                                {{ $inventory->event->name }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-secondary text-xs font-weight-bold">
                                                {{ $inventory->technology->name }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-secondary text-xs font-weight-bold">
                                                {{ $inventory->route->name }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-secondary text-xs font-weight-bold">
                                                {{ $inventory->user->name }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-secondary text-xs font-weight-bold">
                                                {{ $inventory->creator_user->name }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-secondary text-xs font-weight-bold">
                                                <span class="badge {{ $inventory->state->code == 'ACTIVE' ? 'bg-gradient-success' : 'bg-gradient-danger' }} rounded-pill">{{ $inventory->state->name }}</span>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('inventory.show', $inventory->id) }}" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Ver Detalle" onclick="onEdit(this)">
                                                <i class="fas fa-eye text-secondary"></i>
                                            </a>
                                            <!-- Action change status -->
                                            <a href="#" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Cambiar Estado" onclick="onChangeStatus(this)">
                                                <i class="fas fa-exchange-alt text-secondary"></i>
                                            </a>
                                            <a href="{{ route('inventory.exports', $inventory->id) }}" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Exportar Excel" onclick="onEdit(this)">
                                                <i class="fas fa-file-excel text-secondary"></i>
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

<!-- pass crtoken -->
<script>
    const csrfToken = @JSON(csrf_token());
</script>

<script src="{{ asset('assets/js/jquery-3.7.0.js') }}"></script>
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/plugins/jquery.inputmask.min.js') }}"></script>
<script src="{{ asset('js/datatables-config.js') }}"></script>
<script src="{{ asset('js/plugins/toastr.min.js') }}"></script>
<script src="{{ asset('js/plugins/seetalert2.all.min.js') }}"></script>

<script src="{{ asset('js/pages/inventories.js') }}"></script>

@endsection
