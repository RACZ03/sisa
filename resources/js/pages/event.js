
const csrfToken = window.csrfToken;
let validEvent = true;
$(document).ready(function() {

    $('#event-table').DataTable({
        ...DATA_TABLE_CONFIG,
    });

    const codeInput = $('#code');

    // validations code exists
    codeInput.on('blur', function () {
        // get value
        const codeInputValue = codeInput.val();
        validateField('code', codeInputValue, codeInput);
    });


});

function convertToUpperCase(input) {
    input.value = input.value.toUpperCase();
}

function validateField(field, value, elementInput) {

    if ( value === '' ) {
        return
    }

    value = value.replace(/\s/g, '');

    // Realizar petición AJAX para validar la unicidad del número de teléfono
    $.ajax({
        type: 'POST',
        url: '/events/validate-unique-field',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        data: {
            field: field,
            value: value,
        },
        success: function(response) {
            if (response.exists) {
                toastr.error('Código de evento ya existe.');
                validEvent = false;

                elementInput.css('border-color', 'red');
            } else {
                elementInput.css('border-color', 'green');
                validEvent = true;
            }
        },
        error: function() {
            toastr.error('Ha ocurrido un error al validar el codigo de evento.');
            elementInput.css('border-color', 'red');
        }
    });
};

function onCreate() {
    $('#newEventModalLabel').html('Nuevo Registro');
    $('#newEventModal').modal('show');

    cleanModal();
}

function onEdit(button) {

    $('#newEventModalLabel').html('Editar Registro');
    cleanModal();
    // Obtener la fila que contiene los datos del usuario
    const row = button.closest('tr');

    // Obtener los valores de los atributos data- correspondientes
    const eventId = row.dataset.eventId;
    const eventCode = row.dataset.eventCode;
    const eventName = row.dataset.eventName;

    // Llenar el modal con los valores del usuario
    document.getElementById('event_id').value = eventId;
    document.getElementById('code').value = eventCode;
    document.getElementById('name').value = eventName;


    // Abrir el modal de edición
    $('#newEventModal').modal('show');
}

function onDelete(button) {
    const row = button.closest('tr');

    // Obtener los valores de los atributos data- correspondientes
    const eventId = row.dataset.eventId;
    const eventName = row.dataset.eventName;

    // mostrar alert
    Swal.fire({
        title: '¿Estás seguro?',
        text: `¿Estás seguro que deseas eliminar el evento ${eventName}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Realizar petición AJAX para eliminar al usuario
            $.ajax({
                type: 'DELETE',
                url: '/events/'+eventId,
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
                    toastr.error('Ha ocurrido un error al eliminar el evento.');
                }
            });
        }
    });
}


// ACTION SAVE USER

document.getElementById('saveBtn').addEventListener('click', function () {

    event.preventDefault();

    const id = $('#event_id').val();
    const code = $('#code').val();
    const name = $('#name').val();

    // validar que el campo de código no esté vacío
    if (code === '') {
        toastr.error('El campo de código no puede estar vacío.');
        $('#code').css('border-color', 'red');
        return;
    } else {
        // borde default bootstrap;
        $('#code').css('border-color', '#ced4da');
    }

    // Validar que el campo de nombre no esté vacío
    if (name === '') {
        toastr.error('El campo de nombre no puede estar vacío.');
        $('#name').css('border-color', 'red');
        return;
    } else {
        // borde default bootstrap;
        $('#name').css('border-color', '#ced4da');
    }


    if ( !validEvent ) {
        toastr.error('El código de evento ya existe.');
        return;
    }

    let url = '';
    let method = '';
    let body = {};

    if ( id ) {
        url = '/events/' + id;
        method = 'PUT';
    } else {
        url = '/events/store';
        method = 'POST';
    }

    $.ajax({
        type: method,
        url: url,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        data: {
            code: code,
            name: name,
        },
        success: function(response) {
            // hide modal
            $('#newEventModal').modal('hide');
            if ( response.status == 200 ) {
                toastr.success(response.message);
                cleanModal();
                setTimeout(function () {
                    window.location.reload();
                }, 1000);
            } else {
                toastr.error(response.message);
            }
        },
        error: function(e) {
            if ( e?.responseJSON?.message ) {
                toastr.error(e.responseJSON.message);
            } else if ( e?.message ) {
                toastr.error(e.message);
            } else {
                toastr.error('Ha ocurrido un error al guardar el usuario.')
            }
        }
    });
})


function cleanModal() {
    $('#code').val('');
    $('#name').val('');
}



