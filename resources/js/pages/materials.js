
const csrfToken = window.csrfToken;
let validCode = true;

$(document).ready(function() {

    $('#materials-table').DataTable({
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
        url: '/materials/validate-unique-field',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        data: {
            field: field,
            value: value,
        },
        success: function(response) {
            if (response.exists) {
                toastr.error('El Código del material ya existe.');
                validCode = false;

                elementInput.css('border-color', 'red');
            } else {
                elementInput.css('border-color', 'green');
                validCode = true;
            }
        },
        error: function() {
            toastr.error('Ha ocurrido un error al validar el codigo del material.');
            elementInput.css('border-color', 'red');
        }
    });
};

function onCreate() {
    $('#newMaterialModalLabel').html('Nuevo Registro');
    $('#newMaterialModal').modal('show');
    $('#passDiv').show();

    cleanModal();
}

function onEditUser(button) {

    $('#newMaterialModalLabel').html('Editar Registro');
    cleanModal();
    // Obtener la fila que contiene los datos del material a editar
    const row = button.closest('tr');

    // propiedades son: technology_id, code, name, description, stock, has_series
    const materialId = row.dataset.materialId;
    const code = row.dataset.code;
    const name = row.dataset.name;
    const description = row.dataset.description;
    const stock = row.dataset.stock;
    const has_series = row.dataset.has_series;

    // Llenar el formulario con los datos del material
    $('#material_id').val(materialId);
    $('#code').val(code);
    $('#name').val(name);
    $('#description').val(description);
    $('#stock').val(stock);ç

    // check has_series
    if (has_series === '1') {
        $('#has_series').prop('checked', true);
    } else {
        $('#has_series').prop('checked', false);
    }

    // select technology
    $('#technology').val(row.dataset.technologyId);

    // Abrir el modal de edición
    $('#newMaterialModal').modal('show');
}

function onDelete(button) {
    const row = button.closest('tr');

    // Obtener los valores de los atributos data- correspondientes
    const materialId = row.dataset.materialId;
    const name = row.dataset.name;
    const code = row.dataset.code;

    // mostrar alert
    Swal.fire({
        title: '¿Estás seguro?',
        text: `¿Estás seguro que deseas eliminar el material ${code} - ${name}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Realizar petición AJAX para eliminar el material
            $.ajax({
                type: 'DELETE',
                url: '/materials/'+materialId,
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
                    toastr.error('Ha ocurrido un error al eliminar el material.');
                }
            });
        }
    });
}


// ACTION SAVE USER

document.getElementById('saveMaterialBtn').addEventListener('click', function () {

    event.preventDefault();

    const id = $('#material_id').val();
    const code = $('#code').val();
    const name = $('#name').val();
    const description = $('#description').val();
    const stock = $('#stock').val();
    const technology_id = $('#technology').val();

    // obtener si has_series esta seleccionado
    const has_series = $('#has_series').is(':checked') ? 1 : 0;

    // validar technology_id no este vacio
    if ( technology_id <= 0 || technology_id === '' ) {
        toastr.error('Debe seleccionar una tecnología.');
        // border red
        $('#technology').css('border-color', 'red');
        return;
    } else {
        $('#technology').css('border-color', 'green');
    }
    // validar code no este vacio
    if ( code === '' ) {
        toastr.error('El código no puede estar vacío.');
        // border red
        $('#code').css('border-color', 'red');
        return;
    } else {
        $('#code').css('border-color', 'green');
    }
    // validar campo code no sea menor a 3 caracteres
    if ( code.length < 3 ) {
        toastr.error('El código debe tener al menos 3 caracteres.');
        // border red
        $('#code').css('border-color', 'red');
        return;
    } else {
        $('#code').css('border-color', 'green');
    }
    // validar name no este vacio
    if ( name === '' ) {
        toastr.error('El nombre no puede estar vacío.');
        // border red
        $('#name').css('border-color', 'red');
        return;
    } else {
        $('#name').css('border-color', 'green');
    }

    // validar stock sea mayor a 0
    if ( stock <= 0 ) {
        toastr.error('El stock debe ser mayor a 0.');
        // border red
        $('#stock').css('border-color', 'red');
        return;
    } else {
        $('#stock').css('border-color', 'green');
    }


    let url = '';
    let method = '';

    if ( id ) {
        url = '/materials/' + id;
        method = 'PUT';
    } else {
        url = '/materials/store';
        method = 'POST';
    }

    let body = {
        technology_id: technology_id,
        code: code,
        name: name,
        description: description,
        stock: stock,
        has_series: has_series
    };
    console.log(body);
    $.ajax({
        type: method,
        url: url,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        data: body,
        success: function(response) {
            // hide modal
            $('#newMaterialModal').modal('hide');
            if ( response.status == 200 ) {
                toastr.success(response.message);
                cleanModal();
                setTimeout(function () {
                    window.location.href = '/materials';
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
                toastr.error('Ha ocurrido un error al guardar el material.')
            }
        }
    });
})


function cleanModal() {
    $('#material_id').val('');
    $('#code').val('');
    $('#name').val('');
    $('#description').val('');
    $('#stock').val('');
    $('#has_series').val('');
    $('#technology').val('');
    // border color default
    $('#code').css('border-color', '#ced4da');
    $('#name').css('border-color', '#ced4da');
    $('#description').css('border-color', '#ced4da');
    $('#stock').css('border-color', '#ced4da');
    $('#has_series').css('border-color', '#ced4da');
    $('#technology').css('border-color', '#ced4da');

}



