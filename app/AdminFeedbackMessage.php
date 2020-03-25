<?php

namespace App;

use App\AdminFeedback;
use Illuminate\Database\Eloquent\Model;

class AdminFeedbackMessage extends Model
{
    protected $fillable = [
        'admin_feedback_id', 'user_id', 'admin_id', 'user_read', 'admin_read', 'message', 
    ];

    protected $table = 'admin_feedback_messages';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id', 'id');
    }

    public static function add($fields, $sender)
    {
        $feedback = new static;
        $feedback->fill($fields);
        $feedback->user_id  = $sender == 'user' ? auth()->user()->id : 0;
        $feedback->admin_id = $sender == 'admin' ? auth()->user()->id : 0;
        $feedback->save();

        if($sender == 'admin'){
            $admin_feedback = AdminFeedback::find($fields['admin_feedback_id']);
            $admin_feedback->is_read = 0;
            $admin_feedback->save();
        }

        return $feedback;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();
    }
}
