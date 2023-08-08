<?php

namespace App\Exports;

use App\Models\Inventory;
use App\Models\InventoryDetail;
use App\Exports\InventoryExport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;

class InventoryExport implements FromView, WithTitle, WithStyles, ShouldAutoSize
{
    private $inventoryId;
    private $inventoryDetails; // Nueva propiedad

    public function __construct($inventoryId)
    {
        $this->inventoryId = $inventoryId;
        $this->inventoryDetails = InventoryDetail::where('inventory_id', $this->inventoryId)->get(); // Obtener los detalles del inventario
    }

    public function view(): View
    {
        $inventory = Inventory::findOrFail($this->inventoryId);

        return view('exports.InventoryExports', [
            'inventory' => $inventory,
            'inventory_details' => $this->inventoryDetails, // Pasar los detalles del inventario a la vista
        ]);
    }

    public function title(): string
    {
        return 'Detalle de Inventario';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:H1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A6:H6')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1:H' . ($this->inventoryDetails->count() + 8))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        // Aplicar color de fondo a la fila de Materiales
        $sheet->getStyle('A7:H7')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFC6E0B4');
    }
}

