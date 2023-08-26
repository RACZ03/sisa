
const csrfToken = window.csrfToken;
let validCode = true;

$(document).ready(function() {

    $('#materials-table').DataTable({
        ...DATA_TABLE_CONFIG,
    });

    const codeInput = $('#codeMaterial');
    const name = $('#nameMaterial');

    // validations code exists
    codeInput.on('blur', function () {
        // get value
        const codeInputValue = codeInput.val();
        const id = $('#material_id').val();
        validateField('code', codeInputValue, id, codeInput);
    });

    // validation code and name blur convert to uppercase
    codeInput.on('blur', function () {
        convertToUpperCase(codeInput);
    });

    name.on('blur', function () {
        convertToUpperCase(name);
    });

});

function convertToUpperCase(input) {
    input.value = input.value?.toUpperCase();
}

function validateField(field, value, id, elementInput) {

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
            id: id
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

    cleanModal();
}

function onEdit(button) {

    $('#newMaterialModalLabel').html('Editar Registro');
    cleanModal();
    // Obtener la fila que contiene los datos del material a editar
    const row = button.closest('tr');

    // propiedades son: technology_id, code, name, description, stock, has_series
    const materialId = row.dataset.materialId;
    const code = row.dataset.materialCode;
    const name = row.dataset.materialName;
    const has_series = row.dataset.materialHasSeries;

    // Llenar el formulario con los datos del material
    $('#material_id').val(materialId);
    $('#codeMaterial').val(code);
    $('#nameMaterial').val(name);

    // check has_series
    if (has_series === '1' || has_series === 1) {
        $('#has_seriesMaterial').prop('checked', true);
    } else {
        $('#has_seriesMaterial').prop('checked', false);
    }

    // select technology
    $('#technologyMaterial').val(row.dataset.materialTechnology);

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
    const code = $('#codeMaterial').val();
    const name = $('#nameMaterial').val();
    const technology_id = $('#technologyMaterial').val();

    // obtener si has_series esta seleccionado
    const has_series = $('#has_seriesMaterial').is(':checked') ? 1 : 0;

    // validar technology_id no este vacio
    if ( technology_id <= 0 || technology_id === '' ) {
        toastr.error('Debe seleccionar una tecnología.');
        // border red
        $('#technologyMaterial').css('border-color', 'red');
        return;
    } else {
        $('#technologyMaterial').css('border-color', 'green');
    }
    // validar code no este vacio
    if ( code === '' ) {
        toastr.error('El código no puede estar vacío.');
        // border red
        $('#codeMaterial').css('border-color', 'red');
        return;
    } else {
        $('#codeMaterial').css('border-color', 'green');
    }
    // validar campo code no sea menor a 3 caracteres
    if ( code.length < 3 ) {
        toastr.error('El código debe tener al menos 3 caracteres.');
        // border red
        $('#codeMaterial').css('border-color', 'red');
        return;
    } else {
        $('#codeMaterial').css('border-color', 'green');
    }
    // validar name no este vacio
    if ( name === '' ) {
        toastr.error('El nombre no puede estar vacío.');
        // border red
        $('#nameMaterial').css('border-color', 'red');
        return;
    } else {
        $('#nameMaterial').css('border-color', 'green');
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
        has_series: has_series
    };

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
    $('#codeMaterial').val('');
    $('#nameMaterial').val('');
    $('#has_seriesMaterial').val('');
    $('#technologyMaterial').val('');
    // border color default
    $('#codeMaterial').css('border-color', '#ced4da');
    $('#nameMaterial').css('border-color', '#ced4da');
    $('#has_seriesMaterial').css('border-color', '#ced4da');
    $('#technologyMaterial').css('border-color', '#ced4da');

}



