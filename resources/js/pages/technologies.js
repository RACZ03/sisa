
const csrfToken = window.csrfToken;
let validCode = true;
$(document).ready(function() {

    $('#technologies-table').DataTable({
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
        url: '/technologies/validate-unique-field',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        data: {
            field: field,
            value: value,
        },
        success: function(response) {
            if (response.exists) {
                toastr.error('El Código de la tecnología ya existe.');
                validCode = false;

                elementInput.css('border-color', 'red');
            } else {
                elementInput.css('border-color', 'green');
                validCode = true;
            }
        },
        error: function() {
            toastr.error('Ha ocurrido un error al validar el codigo de tecnología.');
            elementInput.css('border-color', 'red');
        }
    });
};

function onCreate() {
    $('#newTechnologyModalLabel').html('Nuevo Registro');
    $('#newTechnologyModal').modal('show');

    cleanModal();
}

function onEdit(button) {

    $('#newTechnologyModalLabel').html('Editar Registro');
    cleanModal();
    // Obtener la fila que contiene los datos del usuario
    const row = button.closest('tr');

    // Obtener los valores de los atributos data- correspondientes
    const technologytId = row.dataset.technologyId;
    const technologyCode = row.dataset.technologyCode;
    const technologyName = row.dataset.technologyName;
    const technologyDescription = row.dataset.technologyDescription;

    // Llenar el modal con los valores del usuario
    document.getElementById('technology_id').value = technologytId;
    document.getElementById('code').value = technologyCode;
    document.getElementById('name').value = technologyName;
    document.getElementById('description').value = technologyDescription;


    // Abrir el modal de edición
    $('#newTechnologyModal').modal('show');
}

function onDelete(button) {
    const row = button.closest('tr');

    // Obtener los valores de los atributos data- correspondientes
    const technologyId = row.dataset.technologyId;
    const technologyName = row.dataset.technologyName;

    // mostrar alert
    Swal.fire({
        title: '¿Estás seguro?',
        text: `¿Estás seguro que deseas eliminar la tecnología ${technologyName}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Realizar petición AJAX para eliminar al usuario
            $.ajax({
                type: 'DELETE',
                url: '/technologies/'+technologyId,
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
                    toastr.error('Ha ocurrido un error al eliminar la tecnología.');
                }
            });
        }
    });
}


// ACTION SAVE TECHNOLOGY

document.getElementById('saveTechnologyBtn').addEventListener('click', function () {

    event.preventDefault();

    const id = $('#technology_id').val();
    const code = $('#code').val();
    const name = $('#name').val();
    const description = $('#description').val();

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


    if ( !validCode ) {
        toastr.error('El código de la tecnología ya existe.');
        return;
    }

    let url = '';
    let method = '';
    let body = {};

    if ( id ) {
        url = '/technologies/' + id;
        method = 'PUT';
    } else {
        url = '/technologies/store';
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
            description: description,
        },
        success: function(response) {
            // hide modal
            $('#newTechnologyModal').modal('hide');
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
                toastr.error('Ha ocurrido un error en la petición.')
            }
        }
    });
})


function cleanModal() {
    $('#code').val('');
    $('#name').val('');
}



