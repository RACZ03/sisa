


$(document).ready(function() {

    $('#inventories-table').DataTable({
        ...DATA_TABLE_CONFIG,
    });


});

function onChangeStatus(button) {
    const row = button.closest('tr');

    // Obtener los valores de los atributos data- correspondientes
    const inventory = row.dataset.inventoryId;
    const inventoryCode = row.dataset.inventoryCode;
    const code = row.dataset.inventoryState;

    var newStatus = code === 'ACTIVE' ? 'CANCELLED' : 'ACTIVE';
    var message = '';
    if (newStatus === 'ACTIVE') {
        message = `¿Estás seguro que deseas "Activar" el inventario ${inventoryCode}?`;
    } else {
        message = `¿Estás seguro que deseas "Anular" el inventario ${inventoryCode}?`;
    }

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
                url: `/inventory/change-status/${inventory}`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function (response) {
                    // Mostrar notificación Toastr con el mensaje de respuesta
                    toastr.success(response.message);
                    // Recargar la página
                    location.reload();
                },
                error: function (xhr, status, error) {
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
