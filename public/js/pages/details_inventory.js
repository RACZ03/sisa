
listTemp = [];

$(document).ready(function () {

    // dehabilitar el select de materiales
    $('#materialSelect').prop('disabled', true);
    // dehabilitar el select de tecnicos
    $('#technical').prop('disabled', true);

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
        console.log($(this).val());
        // Obtener el ID de la tecnologia seleccionada
        const technologyId = $(this).val();
        // Habilitar el select de materiales
        $('#materialSelect').prop('disabled', false);
        // cargar los materiales de la tecnologia seleccionada
        let materials = materiales.filter(m => m.technology_id == technologyId);
        // cargar los materiales en el select
        // $('#materialSelect').empty();
        // $('#materialSelect').append('<option value="" selected disabled>Seleccione un material</option>');
        // materials.forEach(material => {
        //     $('#materialSelect').append(`<option value="${material.id}">${material.name}</option>`);
        // });


        // clean listTemp
        listTemp = [];

        // cargar los materiales en la tabla con la cantidad en 0
        materials.forEach(material => {
            const fila = `
            <tr>
                <td>
                    <button type="button" class="btn btn-danger btn-sm btnEliminar">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
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
    // $('#tablaMateriales').on('click', '.btnEliminar', function () {
    //     // Eliminar la fila de la tabla
    //     $(this).closest('tr').remove();

    //     // Obtener el ID del material
    //     let materialId = $(this).closest('tr').find('td:first').text();

    //     // Eliminar el material de la lista
    //     let index = listTemp.findIndex(m => m.id == materialId);
    //     listTemp.splice(index, 1);
    // });

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
        let materialTem = listTemp.find(m => m.code == materialCode);
        materialTem.cantidad = cantidad;
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
                // get position from table
                let position = $(this).closest('tr').index();
                // add series and asigned id to input for position in array and for row position in table
                series += `<input type="text" class="form-control series" id="series-${position}-${i}" style="max-width: 100px; min-width:100px;" placeholder="Serie">`;

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

            listTemp.forEach((material, index) => {
                if (material.cantidad == 0) {
                    $(`#cantidad-${index}`).addClass('border-danger');
                }
            });

            return;
        }

        listTemp.forEach((material, index) => {

            if( material.cantidad == undefined || material.cantidad == null || material.cantidad == '' ) {
                toastr.error(`Debe ingresar la cantidad para el material ${material.name}.`);
                // border red input cantidad cantidad-${ listTemp?.length || 0 }
                $(`#cantidad-${index}`).addClass('border-danger');
                return;
            } else {
                // border green input cantidad cantidad-${ listTemp?.length || 0 }
                $(`#cantidad-${index}`).removeClass('border-danger');
                $(`#cantidad-${index}`).addClass('border-success');
            }

            // validar si has_series es true or 1
            if (material.has_series) {
                // obtener la cantidad
                let cantidad = material?.cantidad || 0;

                // validar si la cantidad es igual a 0 or null o ''
                if (cantidad == 0 || cantidad == null || cantidad == '') {
                    toastr.error(`Debe ingresar la cantidad para el material ${material.name}.`);
                    // border red input cantidad cantidad-${ listTemp?.length || 0 }
                    $(`#cantidad-${index+1}`).addClass('border-danger');
                    return;
                } else {
                    // border green input cantidad cantidad-${ listTemp?.length || 0 }
                    $(`#cantidad-${index+1}`).removeClass('border-danger');
                    $(`#cantidad-${index+1}`).addClass('border-success');
                }


                // obtener las series existentes en la posicion del index y segun la cantidad
                for (let i = 0; i < cantidad; i++) {
                    // fin input series by series-${position}-${i}
                    let series = $(`#series-${index}-${i}`);
                    // validar si la serie esta vacia
                    if (series.val() == '') {
                        toastr.error(`Debe ingresar la serie ${i + 1} para el material ${material.name}.`);
                        // border red
                        $(series).addClass('border-danger');
                        return;
                    } else {
                        // border green
                        $(series).removeClass('border-danger');
                        $(series).addClass('border-success');
                    }
                    // buscar si la serie ya existe en la lista material.series
                    let serie = material?.series?.find(s => s == series.val());
                    // si serie no existe, agregarla
                    if (!serie) {
                        let value = series.val();
                        if ( value == '' || value == null || value == undefined ) {
                            return
                        }
                        material.series.push(series.val());
                    }
                }

            } else {
                // validar si la cantidad es menor a 0 o vacio
                if (material.cantidad < 0 || material.cantidad == '' || material.cantidad == null) {
                    toastr.error(`La cantidad del material ${material.name} debe ser mayor a 0.`);
                    return;
                }
                // validar si la cantidad es mayor al stock
                if (material.cantidad > material.stock) {
                    toastr.error(`La cantidad del material ${material.name} supera el stock disponible.`);
                    return;
                }

            }
        });


        let seenSeries = new Set();
        let repeatedSeries = [];

        listTemp.forEach((material, position) => {
            if (material?.has_series) {
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
                if (material?.has_series) {

                    material?.series.forEach((series, index) => {
                        let serie = $(`#series-${position}-${index}`).val();
                        material.series[index] = serie;

                        if (repeatedSeries.includes(material.series[index])) {
                            $(`#series-${position}-${index}`).addClass('border-danger');
                            band = true;
                        } else {
                            $(`#series-${position}-${index}`).removeClass('border-danger');
                            $(`#series-${position}-${index}`).addClass('border-success');
                        }
                    });
                }
            });
        } else {
            listTemp.forEach((material, position) => {
                if (material?.has_series) {

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
        listTemp.forEach(material => {
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
                let detalle = {
                    code: material?.code,
                    material_id: material?.id,
                    count: material?.cantidad,
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
