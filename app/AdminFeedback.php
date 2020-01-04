<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminFeedback extends Model
{
    protected $fillable = [
        'title', 'description', 'user_id'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function add($fields)
    {
        $feedback = new static;
        $feedback->fill($fields);
        $feedback->user_id = auth()->user()->id;
        $feedback->save();

        return $feedback;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();
    }
}
