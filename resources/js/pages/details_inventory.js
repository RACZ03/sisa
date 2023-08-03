listTemp = [];

$(document).ready(function () {

    // dehabilitar el select de materiales
    $('#materialSelect').prop('disabled', true);
    // dehabilitar el select de tecnicos
    $('#technical').prop('disabled', true);

    // Evento al hacer clic en el botón "Agregar Material"
    $('#btnAgregar').on('click', function () {
        // Obtener el ID del material seleccionado
        const materialId = $('#materialSelect').val();

        // Validar si se ha seleccionado un material
        if (materialId) {
            let material = materiales.find(m => m.id == materialId);

            // validar si la listTemp esta vacia
            if (listTemp.length == 0) {
                listTemp.push(material);
            } else {
                // validar si el material ya esta en la lista
                let index = listTemp.findIndex(m => m.id == materialId);
                if (index == -1) {
                    listTemp.push(material);
                } else {
                    toastr.error('El material ya se encuentra en la lista.');
                    // select the first option
                    $('#materialSelect option:first').prop('selected', true);
                    return;
                }
            }

            // Obtener la cantidad ingresada
            let cantidad = parseInt($('.cantidad').val(), 10);

            // Agregar la fila en la tabla con el material y cantidad
            const fila = `
            <tr>
                <td>
                    <button type="button" class="btn btn-danger btn-sm btnEliminar">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
                <td>${material.code}</td>
                <td>${material.name}</td>
                <td><input type="number" class="form-control cantidad" style="max-width: 100px; min-width:100px;" value="${cantidad}" min="1"></td>
                ${ material.has_series ? '<td class="series-td"></td>' : ''}
            </tr>
            `;
            $('#tablaMateriales tbody').append(fila);
            $('#materialSelect option:first').prop('selected', true);
        } else {
            toastr.error('Debe seleccionar un material.');
        }

    });

    // detectar evento selected input tecnologia
    $('#technology').on('change', function () {
        // Obtener el ID de la tecnologia seleccionada
        const technologyId = $(this).val();
        // Habilitar el select de materiales
        $('#materialSelect').prop('disabled', false);
        // cargar los materiales de la tecnologia seleccionada
        let materials = materiales.filter(m => m.technology_id == technologyId);
        // cargar los materiales en el select
        $('#materialSelect').empty();
        $('#materialSelect').append('<option value="" selected disabled>Seleccione un material</option>');
        materials.forEach(material => {
            $('#materialSelect').append(`<option value="${material.id}">${material.name}</option>`);
        });

        // clean table
        $('#tablaMateriales tbody').empty();
        // clean listTemp
        listTemp = [];
    });

    // detectar evento selected input ruta
    $('#route').on('change', function () {
        // Obtener el ID de la ruta seleccionada
        const routeId = $(this).val();
        // Habilitar el select de tecnicos
        // $('#technical').prop('disabled', false);
        // buscar ruta en la lista routes
        let route = routes.find(r => r.id == routeId);
        console.log(route);
        // obtener technical_id
        // cargar los tecnicos en el select de tecnicos
        $('#technical').empty();
        // filtrar tecnico por tecnico_id
        let technical = technicals.filter(t => t.id == route.user_id);
        $('#technical').append(`<option value="${technical[0].id}">${technical[0].name}</option>`);
    });


    // Evento vtnEliminar
    $('#tablaMateriales').on('click', '.btnEliminar', function () {
        // Eliminar la fila de la tabla
        $(this).closest('tr').remove();

        // Obtener el ID del material
        let materialId = $(this).closest('tr').find('td:first').text();

        // Eliminar el material de la lista
        let index = listTemp.findIndex(m => m.id == materialId);
        listTemp.splice(index, 1);
    });

    // detectar onblud en el input de cantidad
    $('#tablaMateriales').on('blur', '.cantidad', function () {
        // Obtener la cantidad ingresada
        let cantidad = parseInt($(this).val(), 10);

        // validar si no es un numero mostrar una alerta
        if (isNaN(cantidad)) {
            $(this).val();
            return;
        }

        // obtener el codigo del material de la segunda columna
        let materialCode = $(this).closest('tr').find('td:nth-child(2)').text();
        // obtener el material
        let material = materiales.find(m => m.code == materialCode);
        material.cantidad = cantidad;
        if (cantidad > material.stock) {
            toastr.error('La cantidad ingresada supera el stock disponible.');
            $(this).val(material.stock);
        }
        // agregar input de series segun la cantidad ingresada y si has_series es true or 1
        if (material.has_series) {
            let series = '';
            // necesito que sea en la mima linea que se agreguen los inputs
            for (let i = 0; i < cantidad; i++) {
                series += `<input type="text" class="form-control series" style="min-width: 150px; margin-right: 5px;" placeholder="Serie ${i + 1}">`;
            }
            $(this).closest('tr').find('.series-td').html(`<div class="d-flex">${series}</div>`);
        }
    });


    // Evento al hacer clic en el botón "Guardar" btnSaveInventory
    $('#btnSaveInventory').on('click', function () {

        // obtener date, event, technology, route, technical
        let date = $('#date').val();
        let event = $('#event').val();
        let technology = $('#technology').val();
        let route = $('#route').val();
        let technical = $('#technical').val();

        // validar date no este vacio and border red or green
        if (date == '') {
            $('#date').addClass('border-danger');
            toastr.error('Debe seleccionar una fecha.');
            return;
        } else {
            $('#date').removeClass('border-danger');
            $('#date').addClass('border-success');
        }

        // validar event no este vacio and border red or green
        if (event == '' || event == null) {
            $('#event').addClass('border-danger');
            toastr.error('Debe seleccionar un evento.');
            return;
        } else {
            $('#event').removeClass('border-danger');
            $('#event').addClass('border-success');
        }

        // valida technology no este vacio and border red or green
        if (technology == '' || technology == null) {
            $('#technology').addClass('border-danger');
            toastr.error('Debe seleccionar una tecnología.');
            return;
        } else {
            $('#technology').removeClass('border-danger');
            $('#technology').addClass('border-success');
        }

        // valida route no este vacio and border red or green
        if (route == '' || route == null) {
            $('#route').addClass('border-danger');
            toastr.error('Debe seleccionar una ruta.');
            return;
        } else {
            $('#route').removeClass('border-danger');
            $('#route').addClass('border-success');
        }

        // valida technical no este vacio and border red or green
        if (technical == '' || technical == null) {
            $('#technical').addClass('border-danger');
            toastr.error('Debe seleccionar un técnico.');
            return;
        } else {
            $('#technical').removeClass('border-danger');
            $('#technical').addClass('border-success');
        }

        // validate if existe 1 element in listTemp
        if (listTemp.length == 0) {
            toastr.error('Debe agregar al menos un material al detalle del inventario.');
            return;
        }

        // validar si alguna elemento de la lista tiene cantidad 0 e indicar cual es
        let material = listTemp.find(m => m.cantidad == 0);
        if (material) {
            toastr.error(`El material ${material.name} tiene cantidad 0.`);
            return;
        }

        listTemp.forEach(material => {
            // validar si has_series es true or 1
            if (material.has_series) {
                // obtener la cantidad
                let cantidad = material.cantidad;
                // obtener las series ingresadas segun la cantidad y segun el material
                let series = $(`#tablaMateriales tr:contains(${material.code})`).find('.series');
                // validar si la cantidad de series ingresadas es igual a la cantidad del material
                if (series.length != cantidad) {
                    toastr.error(`Debe ingresar ${cantidad} series para el material ${material.name}.`);
                    return;
                }
                // validar si alguna serie esta vacia
                for (let i = 0; i < series.length; i++) {
                    if (series[i].value == '') {
                        toastr.error(`Debe ingresar la serie ${i + 1} para el material ${material.name}.`);
                        // border red
                        $(series[i]).addClass('border-danger');
                        return;
                    } else {
                        // border green
                        $(series[i]).removeClass('border-danger');
                        $(series[i]).addClass('border-success');
                    }
                }
                // agregar las series al material
                material.series = [];
                for (let i = 0; i < series.length; i++) {
                    material.series.push(series[i].value);
                }
            }
        });

    });
});
