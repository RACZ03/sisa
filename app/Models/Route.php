<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'user_id',
        'state_id',
    ];

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
     * Define la relación "belongsTo" con el modelo User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define la relación inversa "hasMany" con el modelo Route.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function routes()
    {
        return $this->hasMany(Route::class);
    }


}
