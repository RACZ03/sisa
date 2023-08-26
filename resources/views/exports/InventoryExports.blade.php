<table class="table table-bordered">
    <tr>
        <td colspan="7" style="font-weight: bold; text-align: center;">INVENTARIO</td>
    </tr>
    <tr>
        <td style="font-weight: bold; text-align: center;">FECHA</td>
        <td colspan="6">{{ $inventory->date }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold; text-align: center;">ACTIVIDAD</td>
        <td colspan="2">{{ $inventory->event->name }}</td>
        <td style="font-weight: bold; text-align: center;">TÉCNICO</td>
        <td colspan="3"> {{ $inventory->user->name }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold; text-align: center;">TECNOLOGÍA:</td>
        <td colspan="2">{{ $inventory->technology->name }}</td>
        <td style="font-weight: bold; text-align: center;">RUTA</td>
        <td colspan="3">{{ $inventory->route->name }}</td>
    </tr>
    <tr><td colspan="7"></td></tr>
    <tr class="table-primary">
        <td colspan="7" style="font-weight: bold; text-align: center;">DETALLE DE INVENTARIO</td>
    </tr>
    <tr>
        <th>#</th>
        <th>CODIGO MATERIAL</th>
        <th>NOMBRE</th>
        <th>STOCK ANTERIOR</th>
        <th>CANIDAD</th>
        <th>STOCK NUEVO</th>
        <th>SERIE</th>
    </tr>
    @foreach ($inventory_details as $index => $detalle)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $detalle->material->code }}</td>
            <td>{{ $detalle->material->name }}</td>
            <td>{{ $detalle->old_stock }}</td>
            <td>{{ $detalle->count }}</td>
            <td>{{ $detalle->new_stock }}</td>
            <td>{{ $detalle->series }}</td>
        </tr>
    @endforeach
</table>
