

var $jq = jQuery.noConflict();
  $jq(document).ready(function() {
    // initialize select2
    $jq('.select2').select2({
        width: '100%'
    });

    // initialize daterangerpicker
    $jq('#date_range').daterangepicker({
        timePicker: true,
        timePickerIncrement: 30,
        locale: {
            format: 'DD/MM/YYYY'
        },
        endDate: moment(),
        maxDate: moment()
    });

    // initialize datatable
    $jq('#reports-table').DataTable({
        ...DATA_TABLE_CONFIG,
        // Mostrar los botones de exportación en el elemento DOM de DataTable
        dom: '<"d-flex justify-content-between" fB>t<"d-flex justify-content-end" p>',
        // Añadir el botón de exportar a Excel a la lista de botones
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Exportar a Excel',
                title: 'Reporte de Movimientos' + ' - ' + moment().format('DD/MM/YYYY'),
                className: 'btn btn-success',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                }
            },
            {
                extend: 'pdfHtml5',
                text: 'Exportar a PDF',
                title: 'Reporte de Movimientos' + ' - ' + moment().format('DD/MM/YYYY'),
                className: 'btn btn-danger',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                },
                orientation: 'landscape'
            }
        ]
    });


});

function openSpinner() {
    $("#overlay").fadeIn(300);　
}

function offSpinner() {
    $("#overlay").fadeOut(300);
}

function buscarInformacion() {
    // obtener valores de los filtros
    // get dateInit and dateEnd values from dateRangePicker input field format DD/MM/YYYY
    const dateRange = $jq('#date_range').val();
    const dateInit = dateRange.split(' - ')[0];
    const dateEnd = dateRange.split(' - ')[1];

    // get selected values from select2 input field
    const technologies = $jq('#technology').val();
    const technical = $jq('#technical').val();
    const event = $jq('#event').val();


    let body = {
        dateInit: moment(dateInit, 'DD/MM/YYYY').format('YYYY-MM-DD'),
        dateEnd: moment(dateEnd, 'DD/MM/YYYY').format('YYYY-MM-DD'),
        technologies: technologies,
        technical: technical,
        event: event
    };

    // open spinner
    openSpinner();

    $.ajax({
        type: 'POST',
        url: '/reports/findData',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        data: body,
        success: function(response) {
            // close spinner
            offSpinner();
            // message se logro obtener la información
            toastr.success('Se logro obtener la información');
            // cargar la información en la tabla
            loadDataTable(response.data);

        },
        error: function() {
            console.log('error');
            // close spinner
            offSpinner();
            // message no se logro obtener la información
            toastr.error('No se logro obtener la información');
        }
    });

}

function loadDataTable(data) {

    // load data into table
    $jq('#reports-table').DataTable().clear().draw();

    // define table rows
    let rows = [];
    // iterate over data
    data.forEach((item, index) => {
        // create table row
        let row = [
            index + 1,
            item.date,
            item.event,
            item.technology,
            item.detail_code,
            item.detail_material,
            item.detail_old_stock,
            item.detail_count,
            item.detail_new_stock,
            item.user,
            item.detail_series,
            item.state
        ];
        // push row to rows
        rows.push(row);
    });

    // add rows to table
    $jq('#reports-table').DataTable().rows.add(rows).draw();


}
