@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4 shadow" style="border: none;">
                <div class="card-header pb-0" style="background: #fff; border: none;">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Cambio de Contraseña</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body ">

                <div class="row">
                    <div class="col-xl-2 col-lg-2 div-md-1 div-sm-1 div-xs-1">
                    </div>
                    <div class="col-xl-8 col-lg-8 div-md-10 div-sm-10 div-xs-10">
                        <form id="changePasswordAuth">
                            <!-- input id hidden -->
                            <input type="hidden" id="user_id_modal" name="user_id_modal" value="">
                            <!-- div password -->
                            <div class="row" id="passDiv">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-2">
                                    <label for="password" class="form-label">Contraseña</label>
                                    <input type="password" class="form-control" id="passwordProfile" minlength="6" name="password_modal" autocomplete="password_modal" required>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-2">
                                    <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                                    <input type="password" class="form-control" id="passwordConfirmProfile" minlength="6" name="password_confirmation_modal" autocomplete="password_confirmation_modal" required>
                                </div>
                            </div>
                            <!-- div password -->
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary" id="savePasswordBtn">Guardar</button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- SCRIPTS -->
<script src="{{ asset('assets/js/jquery-3.7.0.js') }}"></script>
<script src="{{ asset('js/plugins/jquery.inputmask.min.js') }}"></script>
<script src="{{ asset('js/plugins/toastr.min.js') }}"></script>
<script src="{{ asset('js/plugins/seetalert2.all.min.js') }}"></script>


<script>
    // Creamos una variable global para almacenar el valor de csrf_token
    var userId = '{{ auth()->user()->id }}';
    window.csrfToken = '{{ csrf_token() }}';
</script>

<script src="{{ asset('js/pages/changepassword.js') }}"></script>

@endsection
