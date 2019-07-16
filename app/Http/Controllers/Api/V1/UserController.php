<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::onlyUsers()->with('groups')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|max:191',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:4|confirmed',
        ]);

        $user                       = new User;
        $user->name                 = $request->name;
        $user->email                = $request->email;
        $user->email_verified_at    = now();
        $user->password             = bcrypt($request->password);
        $user->user_type            = "user";
        return $user->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        if(!in_array($user->user_type, ['user']))
            abort(409, "Admin user cannot be shown for now.");

        $user->groups;
        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if(!in_array($user->user_type, ['user']))
            abort(409, "Admin user cannot be updated for now.");

        $request->validate([
            'name'      => 'sometimes|max:191',
            'email'     => 'sometimes|email|unique:users,email,' . $user->id,
            'password'  => 'sometimes|min:4|confirmed',
        ]);

        $user->name               = $request->name ?? $user->name;
        $user->email              = $request->email ?? $user->email;
        $user->password           = !empty($request->password) ? bcrypt($request->password) : $user->password;
        return $user->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if(!in_array($user->user_type, ['user']))
            abort(409, "Admin user cannot be deleted for now.");

        return $user->delete();
    }
}
