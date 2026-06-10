<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointAudit extends Model
{
    protected $fillable = ['actor_id', 'target_user_id', 'action_type', 'amount', 'point_type', 'notes'];

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }
}
