<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'count',
        'series',
        'inventory_id',
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
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
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
