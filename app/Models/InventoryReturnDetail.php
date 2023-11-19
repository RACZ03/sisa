<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryReturnDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'material_id',
        'old_stock',
        'count',
        'new_stock',
        'series',
        'existing_series',
        'inventory_return_id',
        'state_id',
    ];

    /**
     * Define la relación "belongsTo" con el modelo Material.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    /**
     * Define la relación "belongsTo" con el modelo Inventory.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function inventoryReturn()
    {
        return $this->belongsTo(InventoryReturn::class);
    }

    /**
     * Define la relación "belongsTo" con el modelo State.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function state()
    {
        return $this->belongsTo(State::class);
    }



}
