<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    /**
     * Returns Users Relationship.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'group_users')
                        ->withPivot('membership_status');
    }
}
