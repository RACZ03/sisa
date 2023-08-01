<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
    ];

    /**
     * Define la relación inversa "hasMany" con el modelo User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Define la relación inversa "hasMany" con el modelo Material.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function material()
    {
        return $this->hasMany(Material::class);
    }

    /**
     * Define la relación inversa "hasMany" con el modelo Route.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function route()
    {
        return $this->hasMany(Route::class);
    }

    /**
     * Define la relación inversa "hasMany" con el modelo Technology.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function technology()
    {
        return $this->hasMany(Technology::class);
    }

    /**
     * Define la relación inversa "hasMany" con el modelo Inventory.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
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
