<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Group;

class GroupMembershipController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Group $group, Request $request)
    {
        $request->validate([
            'user_id'       => 'required|exists:users,id',
        ]);

        $user           = User::find($request->user_id);

        if(!in_array($user->user_type, ['user']))
            return abort(409, "Admin cannot be added to group.");

        $membership     = $group->users()->where('user_id', $request->user_id)->first();

        if(!empty($membership))
        {
            if($membership->membership_status == "revoked")
            {
                return $group->users()->attach([$request->user_id => ['membership_status' => "pending"]]);
            }

            return abort(409, "User is already member of this group.");
        }

        return $group->users()->attach([$request->user_id => ['membership_status' => "pending"]]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Group $group, Request $request)
    {
        $request->validate([
            'user_id'           => 'required|exists:users,id',
            'membership_status' => 'in:active,revoked',
        ]);

        $user           = User::find($request->user_id);

        if(!in_array($user->user_type, ['user']))
            return abort(409, "Admin cannot be added to group.");

        $membership     = $group->users()->where('user_id', $request->user_id)->first();

        if(!empty($membership))
        {
            return $group->users()->attach([$request->user_id => ['membership_status' => $request->membership_status]]);

            // $membership->membership_status = $request->membership_status;
            // return $membership->save();
        }

        return abort(404, "User is not member of this group.");
    }
}
