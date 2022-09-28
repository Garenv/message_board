<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function getUser($userId) {
        $authUser = request()->user();

        $user = User::findOrFail($userId);

        if (!$authUser) {
            /**
             * Doug - Not good practice to unset attributes.
             * There are better ways to handle this, $hidden/$visible etc.
             */
            unset($user->email);
        }

        return $user;
    }
}
