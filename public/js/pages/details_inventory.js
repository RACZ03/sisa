
listTemp = [];

$(document).ready(function () {

    console.log('Hi')
    // dehabilitar el select de materiales
    $('#materialSelect').prop('disabled', true);
    // dehabilitar el select de tecnicos
    $('#route').prop('disabled', true);



    // selected date now in input date
    $('#date').val(moment().format('YYYY-MM-DD'));

    // Evento al hacer clic en el botón "Agregar Material"
    $('#btnAgregar').on('click', function () {
        // Obtener el ID del material seleccionado
        const materialId = $('#materialSelect').val();

        // Validar si se ha seleccionado un material
        if (materialId) {
            let material = materiales.find(m => m.id == materialId);

            // validar si la listTemp esta vacia
            if (listTemp.length == 0) {
                listTemp.push({...material, series: [], cantidad: 0});
            } else {
                // validar si el material ya esta en la lista
                let index = listTemp.findIndex(m => m.id == materialId);
                if (index == -1) {
                    listTemp.push({...material, series: [], cantidad: 0});
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
                <td><input type="number" class="form-control cantidad" id="cantidad-${ listTemp?.length || 0 }" style="max-width: 100px; min-width:100px;" value="${cantidad}" min="1"></td>
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

        // clean table
        $('#tablaMateriales tbody').empty();
        // console.log($(this).val());
        // Obtener el ID de la tecnologia seleccionada
        const technologyId = $(this).val();
        // Habilitar el select de materiales
        $('#materialSelect').prop('disabled', false);
        // cargar los materiales de la tecnologia seleccionada
        let materials = materiales.filter(m => m.technology_id == technologyId);
        // cargar los materiales en el select


        // clean listTemp
        listTemp = [];

        // cargar los materiales en la tabla con la cantidad en 0
        materials.forEach(material => {
            const fila = `
            <tr>
                <td>${material.code}</td>
                <td>${material.name}</td>
                <td><input type="number" class="form-control cantidad" id="cantidad-${ listTemp?.length || 0 }" style="max-width: 100px; min-width:100px;" min="1"></td>
                ${ material.has_series ? '<td class="series-td"></td>' : ''}
            </tr>
            `;
            $('#tablaMateriales tbody').append(fila);
            listTemp.push({...material, series: [], cantidad: 0});
        });
    });

    // detectar evento selected input tecnico
    $('#technical').on('change', function () {
        // Obtener el ID del tecnico seleccionado
        const technicalId = $(this).val();
        // Habilitar el select de rutas
        $('#route').prop('disabled', false);
        // cargar las rutas del tecnico seleccionado
        let route = routes.find(r => r.user_id == technicalId);
        // cargar las rutas en el select
        $('#route').empty();
        $('#route').append(`<option value="${route.id}">${route.name}</option>`);
    });

    $('#event').on('change', function () {
        // recorrer tables validations

        let event = $(this).val();

        if ( event == 2 ) {
            listTemp.forEach((material, index) => {

                let m = materiales.find(x => x.id = material?.id );
                let cantidad = $('#cantidad-' + index).val();

                if ( m?.stock < cantidad ) {
                    toastr.error(`La cantidad supera el stock del material: ${material.name}.`);
                    $('#cantidad-' + index).addClass('border-danger');
                } else {
                    $('#cantidad-' + index).removeClass('border-danger');
                    $('#cantidad-' + index).addClass('border-success');
                }

            });
        }

    });

    // detectar onblud en el input de cantidad
    $('#tablaMateriales').on('blur', '.cantidad', function () {
        // Obtener la cantidad ingresada
        let cantidad = parseInt($(this).val(), 10);
        let event = $('#event').val();

        // validar si no es un numero mostrar una alerta
        if (isNaN(cantidad)) {
            $(this).val();
            return;
        }

        // obtener el codigo del material de la segunda columna
        let materialCode = $(this).closest('tr').find('td:nth-child(1)').text();
        // obtener el material
        let material = materiales.find(m => m.code == materialCode);

        if ( event == 2 ) {

            if ( material.stock < cantidad ) {
                toastr.error(`La cantidad supera el stock del material: ${material.name}.`);
                $(this).addClass('border-danger');
                $(this).val();
            } else {
                $(this).removeClass('border-danger');
                $(this).addClass('border-success');
            }

        } else {
            $(this).removeClass('border-danger');
            $(this).addClass('border-success');
        }


        // agregar input de series segun la cantidad ingresada y si has_series es true or 1
        if (material.has_series) {
            let series = '';
            // necesito que sea en la mima linea que se agreguen los inputs
            for (let i = 0; i < cantidad; i++) {
                // get position from table
                let position = $(this).closest('tr').index();
                // add series and asigned id to input for position in array and for row position in table
                series += `<input type="text" class="form-control series" id="series-${position}-${i}" style="max-width: 150px; min-width:100px; margin-left: 10px;" placeholder="Serie">`;

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

        let bandExists = false;

        listTemp.forEach((material, index) => {

            // validar si has_series es true or 1
            if (material.has_series) {
                // obtener la cantidad
                // obtener cantidad del input
                let cantidad = $('#cantidad-' + index).val();

                if ( event == 2 ) {
                    // buscar el material
                    let m = materiales.find(x => x.id = material.id);

                    if ( m?.stock < cantidad ) {
                        toastr.error(`La cantidad supera el stock del material: ${material.name}.`);
                        $('#cantidad-' + index).addClass('border-danger');
                        bandExists = true;
                        return;
                    } else {
                        $('#cantidad-' + index).removeClass('border-danger');
                        $('#cantidad-' + index).addClass('border-success');
                    }
                }


                material.cantidad = cantidad !== null ? cantidad : 0;

                material.series = [];

                // obtener las series existentes en la posicion del index y segun la cantidad
                for (let i = 0; i < cantidad; i++) {
                    // fin input series by series-${position}-${i}
                    let series = $(`#series-${index}-${i}`);
                    // validar si la serie esta vacia
                    if (series.val() == '') {
                        toastr.error(`Debe ingresar la serie ${i + 1} para el material ${material.name}.`);
                        // border red
                        $(series).addClass('border-danger');
                        bandExists = true;
                        return;
                    } else {
                        // border green
                        $(series).removeClass('border-danger');
                        $(series).addClass('border-success');
                    }
                    // buscar si la serie ya existe en la lista material.series
                    // let serie = material?.series?.find(s => s == series.val());
                    // si serie no existe, agregarla
                    // if (!serie) {
                        let value = series.val();
                        if ( value == '' || value == null || value == undefined ) {
                            return
                        }
                        material.series.push(series.val());
                    // }
                }

            }
        });

        if ( bandExists ) {
            return;
        }


        let seenSeries = new Set();
        let repeatedSeries = [];

        listTemp.forEach((material, position) => {
            if (material?.has_series && material.cantidad > 0) {

                material.series.forEach((series, index) => {

                    // remove serie with value is undefined
                    if ( series == undefined ) {
                        material.series.splice(index, 1);
                        return;
                    }
                    // actualizar valor de series
                    let serie = $(`#series-${position}-${index}`).val();
                    material.series[index] = serie;
                });
                // buscar series repetidas
                material.series.forEach((series, index) => {
                    if (seenSeries.has(series)) {
                        repeatedSeries.push(series);
                    } else {
                        seenSeries.add(series);
                    }
                });
            }
        });


        // buscar en la lista esta series y marcar con rojo las series repetidas
        let band = false;
        if (repeatedSeries.length > 0) {
            listTemp.forEach((material, position) => {
                if (material?.has_series  && material.cantidad > 0) {

                    material?.series.forEach((series, index) => {
                        let serie = $(`#series-${position}-${index}`).val();
                        material.series[index] = serie;

                        if (repeatedSeries !== undefined && repeatedSeries !== null) {
                            if (repeatedSeries.includes(material.series[index])) {
                                $(`#series-${position}-${index}`).addClass('border-danger');
                                band = true;
                            } else {
                                $(`#series-${position}-${index}`).removeClass('border-danger');
                                $(`#series-${position}-${index}`).addClass('border-success');
                            }
                        }
                    });
                }
            });
        } else {
            listTemp.forEach((material, position) => {
                if (material?.has_series  && material.cantidad > 0) {

                    material?.series.forEach((series, index) => {
                        let serie = $(`#series-${position}-${index}`).val();
                        material.series[index] = serie;

                        $(`#series-${position}-${index}`).removeClass('border-danger');
                        $(`#series-${position}-${index}`).addClass('border-success');
                    });
                }
            });
        }

        if (band) {
            return
        }

        let detalleInventario = [];
        // create objet detalle inventario
        listTemp.forEach((material, index) => {
            if ( material?.has_series ) {
                material.series.forEach(serie => {
                    let detalle = {
                        code: material?.code,
                        material_id: material?.id,
                        count: 1,
                        series: serie
                    }
                    detalleInventario.push(detalle);
                });
            } else {
                let cantidad = $('#cantidad-' + index).val();
                let detalle = {
                    code: material?.code,
                    material_id: material?.id,
                    count: cantidad,
                    series: null
                }
                detalleInventario.push(detalle);
            }
        });

        // create objet
        let inventario = {
            date: date,
            event_id: event,
            technology_id: technology,
            route_id: route,
            technical_id: technical,
            detalle: detalleInventario
        };
        // send ajax
        save(inventario);
    });

    // detectar evento paste posterior a un blur
    $('#tablaMateriales').on('paste blur change', '.series', function (e) {
        e.preventDefault(); // Evita que el texto pegado se agregue al input automáticamente

        // Guarda una referencia al input actual
        let $currentInput = $(this);

        // Obtiene el texto pegado del evento
        let pastedText = (e.originalEvent || e).clipboardData.getData('text');

        // Pega el texto en el input actual
        $currentInput.val(pastedText);

        // Obtiene la cantidad ingresada en el input cantidad del mismo row de la serie
        let cantidad = $currentInput.closest('tr').find('.cantidad').val();

        // Calcula la posición y el índice del input actual
        let position = $currentInput.closest('tr').index();
        let index = $currentInput.index();

        // Si hay más inputs de series en la fila, cambia el enfoque al siguiente
        if (index < cantidad - 1) {
            $(`#series-${position}-${index + 1}`).focus();
        }
    });


});


function save(inventario) {
    $.ajax({
        type: 'POST',
        url: '/inventory/store',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        data: inventario,
        success: function(response) {
            // hide modal
            if ( response.status == 200 ) {
                toastr.success(response.message);
                setTimeout(function () {
                    window.location.href = '/inventory';
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
                toastr.error('Ha ocurrido un error al guardar el inventario.')
            }
        }
    });

}
