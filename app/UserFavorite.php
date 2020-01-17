<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserFavorite extends Model
{
    protected $fillable = [
        'user_id', 'product_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public static function add($fields)
    {
        $userFavorite = new static;
        $userFavorite->fill($fields);
        $userFavorite->user_id = auth()->user()->id;
        $userFavorite->save();

        return $userFavorite;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();

        return $this;
    }
}
