@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4 shadow" style="border: none;">
                <div class="card-header pb-0" style="background: #fff; border: none;">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Usuarios</h5>
                        </div>
                        <a href="#" class="btn bg-gradient-primary btn-sm mb-0" type="button" onclick="onCreateUser()">
                            +&nbsp; Nuevo
                        </a>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2 m-2">
                    <div class="table-responsive p-0 mt-4">
                        <table id="users-table" class="table align-items-center mb-0" style="border-bottom: 2px solid rgb(198, 190, 190) !important;">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        #
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Nombre
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Teléfono
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Correo
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Rol
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
                                @foreach ($users as $index => $user)
                                    <tr data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}" data-user-phone="{{ $user->phone }}" data-user-email="{{ $user->email }}" data-user-role="{{ $user->role->id}}">
                                        <td class="ps-4">
                                            <p class="text-secondary text-xs font-weight-bold mb-0"> {{ $index + 1 }} </p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-secondary text-xs font-weight-bold mb-0"> {{ $user->name }} </p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-secondary text-xs font-weight-bold mb-0"> {{ $user->phone }} </p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-secondary text-xs font-weight-bold mb-0"> {{ $user->email }} </p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-secondary text-xs font-weight-bold mb-0"> {{ $user->role->name }} </p>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-secondary text-xs font-weight-bold">
                                                {{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="#" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Editar Usuario" onclick="onEditUser(this)">
                                                <i class="fas fa-user-edit text-secondary"></i>
                                            </a>
                                            <a href="#" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Eliminar Usuario" onclick="onDelete(this)">
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
<div class="modal fade" id="newUserModal" tabindex="-1" aria-labelledby="newUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newUserModalLabel">Nuevo Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form  id="userForm">
                    <!-- input id hidden -->
                    <input type="hidden" id="user_id" name="user_id" value="">
                    <div class="mb-2">
                        <label for="role" class="form-label">Rol</label>
                        <select class="form-select" id="role" name="role" required>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label for="name" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-2">
                        <label for="phone" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="(505) 0000 0000" required>
                    </div>
                    <div class="mb-2">
                        <label for="email" class="form-label">Correo</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="row" id="passDiv">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-2">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" minlength="6" name="password" autocomplete="password" required>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-2">
                            <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                            <input type="password" class="form-control" id="password_confirmation" minlength="6" name="password_confirmation" autocomplete="password_confirmation" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="saveUserBtn">Guardar</button>
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

<script src="{{ asset('js/pages/users.js') }}"></script>

@endsection
