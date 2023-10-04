
const csrfToken = window.csrfToken;
let validEmail = true;
let validPhone = true;

$(document).ready(function() {

    $('#users-table').DataTable({
        ...DATA_TABLE_CONFIG,
    });

    const passwordInput = $('#password');
    const passwordInputModal = $('#password_modal');
    const confirmPasswordInput = $('#password_confirmation');
    const confirmPasswordInputModal = $('#password_confirmation_modal');
    const phoneInput = $('#phone');
    const emailInput = $('#email');
    const nameInput = $('#name');

    // Función para aplicar la máscara al campo de teléfono
     function applyPhoneMask(input) {
        const phoneNumber = input.value.replace(/\D/g, '').substring(0, 11);
        const areaCode = phoneNumber.substring(0, 3);
        const firstPart = phoneNumber.substring(3, 7);
        const secondPart = phoneNumber.substring(7, 11);

        if (phoneNumber.length > 3) {
            input.value = `(${areaCode}) ${firstPart} ${secondPart}`;
        } else if (phoneNumber.length > 0) {
            input.value = `(${areaCode}`;
        }
    }

    // Evento para aplicar la máscara al campo de teléfono cuando se ingresa el número
    document.getElementById('phone').addEventListener('input', function () {
        applyPhoneMask(this);
    });

    // Evento para aplicar la máscara al campo de teléfono cuando se pega el número
    document.getElementById('phone').addEventListener('paste', function () {
        setTimeout(function () {
            applyPhoneMask(document.getElementById('phone'));
        }, 0);
    });

    // validations pass
    confirmPasswordInput.on('blur', function () {
        const passwordValue = passwordInput.val();
        const confirmPasswordValue = confirmPasswordInput.val();
        const minLength = 6;

        // Verificar que ambas contraseñas tengan al menos 6 caracteres
        if (passwordValue.length < minLength || confirmPasswordValue.length < minLength) {
            toastr.error('La contraseña debe tener al menos 6 caracteres.');
            confirmPasswordInput.css('border-color', 'red');
            return;
        }

        // Verificar si las contraseñas son iguales
        if (passwordValue === confirmPasswordValue) {
            confirmPasswordInput.css('border-color', 'green');
            passwordInput.css('border-color', 'green');
        } else {
            confirmPasswordInput.css('border-color', 'red');
            passwordInput.css('border-color', 'red');
        }
    });

    confirmPasswordInputModal.on('blur', function () {
        const passwordValue = passwordInputModal.val();
        const confirmPasswordValue = confirmPasswordInputModal.val();
        const minLength = 6;

        // Verificar que ambas contraseñas tengan al menos 6 caracteres
        if (passwordValue.length < minLength || confirmPasswordValue.length < minLength) {
            toastr.error('La contraseña debe tener al menos 6 caracteres.');
            confirmPasswordInputModal.css('border-color', 'red');
            return;
        }

        // Verificar si las contraseñas son iguales
        if (passwordValue === confirmPasswordValue) {
            confirmPasswordInputModal.css('border-color', 'green');
            passwordInputModal.css('border-color', 'green');
        } else {
            confirmPasswordInputModal.css('border-color', 'red');
            passwordInputModal.css('border-color', 'red');
        }
    });

    // validations phone exists
    phoneInput.on('blur', function () {
        // get value
        const phoneInputValue = phoneInput.val();
        const id = $('#user_id').val();
        validateField('phone', phoneInputValue, id, phoneInput);
    });

    // validations email exists
    emailInput.on('blur', function () {
        // get value
        const emailInputValue = emailInput.val();
        const id = $('#user_id').val();
        validateField('email', emailInputValue, id, emailInput);
    });

    // validations name exists
    nameInput.on('blur', function () {
        // get value
        const nameInputValue = nameInput.val();
        if ( nameInputValue !== '' ) {
            // borde default bootstrap;
            nameInput.css('border-color', '#ced4da');
        }
    });

    nameInput.on('blur', function () {
        convertToUpperCase(nameInput);
    });

});

function openSpinner() {
    $("#overlay").fadeIn(300);　
}

function offSpinner() {
    $("#overlay").fadeOut(300);
}

function convertToUpperCase(input) {
    input.value = input.value?.toUpperCase();
}

function validateField(field, value, id, elementInput) {

    if ( value === '' ) {
        return
    }
    if ( field === 'phone' ) {
        // remove espacio y parentesis
        value = value.replace(/\s/g, '').replace(/\(/g, '').replace(/\)/g, '');
    } else {
        // remove espacio
        value = value.replace(/\s/g, '');
    }
    // Realizar petición AJAX para validar la unicidad del número de teléfono
    $.ajax({
        type: 'POST',
        url: '/users/validate-unique-field',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        data: {
            field: field,
            value: value,
            id: id
        },
        success: function(response) {
            if (response.exists) {
                if ( field === 'email' ) {
                    toastr.error('El correo electrónico ya existe.');
                    validEmail = false;
                } else {
                    toastr.error('El número de teléfono ya existe.');
                    validPhone = false;
                }

                elementInput.css('border-color', 'red');
            } else {
                elementInput.css('border-color', 'green');
                validEmail = true;
                validPhone = true;
            }
        },
        error: function() {
            toastr.error('Ha ocurrido un error al validar el número de teléfono.');
            elementInput.css('border-color', 'red');
        }
    });
};

function onCreateUser() {
    $('#newUserModalLabel').html('Nuevo Usuario');
    $('#newUserModal').modal('show');
    $('#passDiv').show();

    cleanModal();
}

function onEditUser(button) {

    $('#newUserModalLabel').html('Editar Usuario');
    cleanModal();
    // Obtener la fila que contiene los datos del usuario
    const row = button.closest('tr');

    // Obtener los valores de los atributos data- correspondientes
    const userId = row.dataset.userId;
    const userName = row.dataset.userName;
    const userPhone = row.dataset.userPhone;
    const userEmail = row.dataset.userEmail;
    const role = row.dataset.userRole;
    // console.log(role);

    // select role
    $('#role').val(role);

    // enmascarar el telefono
    const phoneNumber = userPhone.replace(/\D/g, '').substring(0, 11);
    const areaCode = phoneNumber.substring(0, 3);
    const firstPart = phoneNumber.substring(3, 7);
    const secondPart = phoneNumber.substring(7, 11);


    // Llenar el modal con los valores del usuario
    document.getElementById('user_id').value = userId;
    document.getElementById('name').value = userName;
    document.getElementById('phone').value = `(${areaCode}) ${firstPart} ${secondPart}`;
    document.getElementById('email').value = userEmail;

    // ocultar password and confirm password
    $('#passDiv').hide();

    // Abrir el modal de edición
    $('#newUserModal').modal('show');
}

function onDelete(button) {
    const row = button.closest('tr');

    // Obtener los valores de los atributos data- correspondientes
    const userId = row.dataset.userId;
    const userName = row.dataset.userName;

    // mostrar alert
    Swal.fire({
        title: '¿Estás seguro?',
        text: `¿Estás seguro que deseas eliminar al usuario ${userName}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Realizar petición AJAX para eliminar al usuario
            $.ajax({
                type: 'DELETE',
                url: '/users/'+userId,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    // Mostrar un mensaje de éxito
                    toastr.success(response.message);

                    // Eliminar la fila de la tabla
                    window.location.reload();
                },
                error: function() {
                    toastr.error('Ha ocurrido un error al eliminar el usuario.');
                }
            });
        }
    });
}


// ACTION SAVE USER

document.getElementById('saveUserBtn').addEventListener('click', function () {

    event.preventDefault();

    const id = $('#user_id').val();
    const name = $('#name').val();
    const phoneInput = $('#phone');
    const passwordInput = $('#password');
    const confirmPasswordInput = $('#password_confirmation');
    const emailInput = $('#email');
    const roleInput = $('#role');

    // Validar que el campo de nombre no esté vacío
    if (name === '') {
        toastr.error('El campo de nombre no puede estar vacío.');
        $('#name').css('border-color', 'red');
        return;
    } else {
        // borde default bootstrap;
        $('#name').css('border-color', '#ced4da');
    }


    // obtener el valor del phoneInput
    let phoneInputValue = phoneInput.val();
    // validar que el valor tenga esta estructa (###) #### #### y la misma cantidad de caracteres
    // if (phoneInputValue.length < 15 || phoneInputValue.length > 15) {
    //     toastr.error('El número de teléfono debe tener el formato (###) #### ####.');
    //     phoneInput.css('border-color', 'red');
    //     return;
    // } else {
    //     phoneInput.css('border-color', 'green');
    // }

    // validar que la contraseña y confirmacion de contraseña su longitud sea mayor a 6 caracteres y que sean iguales
    const passwordValue = passwordInput.val();
    const confirmPasswordValue = confirmPasswordInput.val();
    const minLength = 6;

    if ( id === '' || id === null || id === undefined || id == 0 ) {
        // Verificar que ambas contraseñas tengan al menos 6 caracteres
        if (passwordValue.length < minLength || confirmPasswordValue.length < minLength) {
            toastr.error('La contraseña debe tener al menos 6 caracteres.');
            confirmPasswordInput.css('border-color', 'red');
            return;
        } else {
            confirmPasswordInput.css('border-color', 'green');
            passwordInput.css('border-color', 'green');
        }

        // Verificar si las contraseñas son iguales
        if (passwordValue !== confirmPasswordValue) {
            toastr.error('Las contraseñas no coinciden.');
            confirmPasswordInput.css('border-color', 'red');
            passwordInput.css('border-color', 'red');
            return;
        } else {
            confirmPasswordInput.css('border-color', 'green');
            passwordInput.css('border-color', 'green');
        }
    }

    // remove spaces and parentheses
    const phone = phoneInputValue.replace(/\s/g, '').replace(/\(/g, '').replace(/\)/g, '');

    if ( !validEmail ) {
        toastr.error('El correo electrónico ya existe.');
        return;
    }

    if ( !validPhone ) {
        toastr.error('El número de teléfono ya existe.');
        return;
    }

    let url = '';
    let method = '';

    if ( id ) {
        url = '/users/' + id;
        method = 'PUT';
    } else {
        url = '/users/store';
        method = 'POST';
    }

    // show spinner
    openSpinner();

    $.ajax({
        type: method,
        url: url,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        data: {
            name: name,
            phone: phone,
            password: passwordValue,
            password_confirmation: confirmPasswordValue,
            email: emailInput.val(),
            role: roleInput.val(),
        },
        success: function(response) {
            // hide modal
            $('#createUserModal').modal('hide');
            // hide spinner
            offSpinner();
            if ( response.status == 200 ) {
                toastr.success(response.message);
                cleanModal();
                setTimeout(function () {
                    window.location.href = '/users';
                }, 1000);
            } else {
                toastr.error(response.message);
            }
        },
        error: function(e) {
            // hide spinner
            offSpinner();
            if ( e?.responseJSON?.message ) {
                toastr.error(e.responseJSON.message);
            } else if ( e?.message ) {
                toastr.error(e.message);
            } else {
                toastr.error('Ha ocurrido un error al guardar el usuario.')
            }
        }
    });
});

document.getElementById('savePasswordBtn').addEventListener('click', function () {

    // get id, password, password_confirmation
    const id = $('#user_id_modal').val();
    const passwordInput = $('#password_modal');
    const confirmPasswordInput = $('#password_confirmation_modal');

    // validar que no esten vacios los campos
    if (passwordInput.val() === '' || confirmPasswordInput.val() === '') {
        toastr.error('Los campos de contraseña no pueden estar vacíos.');
        passwordInput.css('border-color', 'red');
        confirmPasswordInput.css('border-color', 'red');
        return;
    } else {
        passwordInput.css('border-color', '#ced4da');
        confirmPasswordInput.css('border-color', '#ced4da');
    }

    // validar que la contraseña y confirmacion de contraseña su longitud sea mayor a 6 caracteres y que sean iguales
    if (passwordInput.val().length < 6 || confirmPasswordInput.val().length < 6) {
        toastr.error('La contraseña debe tener al menos 6 caracteres.');
        passwordInput.css('border-color', 'red');
        confirmPasswordInput.css('border-color', 'red');
        return;
    } else {
        passwordInput.css('border-color', '#ced4da');
        confirmPasswordInput.css('border-color', '#ced4da');
    }

    // Verificar si las contraseñas son iguales
    if (passwordInput.val() !== confirmPasswordInput.val()) {
        toastr.error('Las contraseñas no coinciden.');
        passwordInput.css('border-color', 'red');
        confirmPasswordInput.css('border-color', 'red');
        return;
    } else {
        passwordInput.css('border-color', '#ced4da');
        confirmPasswordInput.css('border-color', '#ced4da');
    }

    // show spinner
    openSpinner();

    $.ajax({
        type: 'POST',
        url: '/users/change-password/' + id,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        data: {
            password: passwordInput.val(),
            password_confirmation: confirmPasswordInput.val(),
        },
        success: function(response) {
            // hide modal
            $('#changePasswordModal').modal('hide');
            // hide spinner
            offSpinner();
            if ( response.status == 200 ) {
                toastr.success(response.message);
                cleanModal();
                setTimeout(function () {
                    window.location.href = '/users';
                }, 1000);
            } else {
                toastr.error(response.message);
            }
        }, error: function(e) {
            // hide spinner
            offSpinner();
            if ( e?.responseJSON?.message ) {
                toastr.error(e.responseJSON.message);
            } else if ( e?.message ) {
                toastr.error(e.message);
            } else {
                toastr.error('Ha ocurrido un error al guardar el usuario.')
            }
        }
    });


});

function onChangePassword(button) {

    let row = button.closest('tr');
    let userId = row.dataset.userId;

    // clear input password_modal and password_confirmation_modal
    $('#password_modal').val('');
    $('#password_confirmation_modal').val('');
    // asignar el id del usuario al input hidden
    $('#user_id_modal').val(userId);

    $('#changePasswordModal').modal('show');
}


function cleanModal() {
    $('#name').val('');
    $('#phone').val('');
    $('#password').val('');
    $('#password_confirmation').val('');
    $('#email').val('');
    $('#role').val('');
    $('#name').css('border-color', '#ced4da');
    $('#phone').css('border-color', '#ced4da');
    $('#password').css('border-color', '#ced4da');
    $('#password_confirmation').css('border-color', '#ced4da');
    $('#email').css('border-color', '#ced4da');
    $('#role').css('border-color', '#ced4da');
}



function onChangeState(button) {
    const row = button.closest('tr');

    // Obtener los valores de los atributos data- correspondientes
    const user = row.dataset.userId;
    const code = row.dataset.userState;
    const userName = row.dataset.userName;

    var newStatus = code === 'ACTIVE' ? 'INACTIVE' : 'ACTIVE';
    var message = '';
    if (newStatus === 'ACTIVE') {
        message = `¿Estás seguro que deseas "Activar" el usuario ${userName}?`;
    } else {
        message = `¿Estás seguro que deseas "Inactivar" el usuario ${userName}?`;
    }

    // show spinner
    openSpinner();

    // sweet alert
    Swal.fire({
        // title: '¿Estás seguro?',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si',
        cancelButtonText: 'No',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/users/change-status/${user}`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function (response) {
                    // hide spinner
                    offSpinner();
                    // Mostrar notificación Toastr con el mensaje de respuesta
                    toastr.success(response.message);
                    // Recargar la página
                    location.reload();
                },
                error: function (xhr, status, error) {
                    // hide spinner
                    offSpinner();
                    // En caso de error, mostrar notificación Toastr con el mensaje de error
                    toastr.error('Ha ocurrido un error al cambiar el estado del registro.');
                },
            });
        }
    }, function (dismiss) {
        if (dismiss === 'cancel') {
            toastr.warning('No se ha realizado ningún cambio.');
        }
    });

}
