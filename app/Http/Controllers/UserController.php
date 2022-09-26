<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function getUser($userId) {
        $authUser = request()->user();

        $user = User::findOrFail($userId);

        if (!$authUser) {
            unset($user->email);
        }

        return $user;
    }
}
