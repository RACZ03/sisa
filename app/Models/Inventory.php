<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'date',
        'event_id',
        'technology_id',
        'route_id',
        'user_id',
        'creator_user_id',
        'state_id',
    ];

    /**
     * Define la relación "belongsTo" con el modelo Event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

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
     * Define la relación "belongsTo" con el modelo Route.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    /**
     * Define la relación "belongsTo" con el modelo User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define la relación "belongsTo" con el modelo User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator_user()
    {
        return $this->belongsTo(User::class);
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
