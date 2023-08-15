<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'stock',
        'has_series',
        'technology_id',
        'state_id',
    ];

    /**
     * Define la relación "belongsTo" con el modelo Technology.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function technology()
    {
        return $this->belongsTo(Technology::class);
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

    /**
     * Define la relación inversa "hasMany" con el modelo InventoryDetail.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventory_details()
    {
        return $this->hasMany(InventoryDetail::class);
    }

}
