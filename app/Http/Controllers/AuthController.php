<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     * @throws ValidationException
     */
    public function login(Request $request) {

        try {

            $email = $request->get('email');

            $credentials = $request->validate([
                'email' => 'required',
                'password' => 'required'
            ]);

            if(!Auth::attempt($credentials)) {
                throw ValidationException::withMessages([
                    'email' => [
                        __('auth.failed')
                    ]
                ]);
            }

            $modelUser = User::where('email', $email)->firstOrFail();
            $createToken    = $modelUser->createToken('auth_token')->plainTextToken;

            return response()->json([
                "token" => $createToken,
            ]);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new \Exception($e->getMessage(), $e->getCode(), $e);
        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function register(Request $request) {

        try {
            $email = $request->get('email');
            $password = $request->get('password');
            $fullName = $request->get('full_name');
            $bio = $request->get('bio');

            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:users',
                'password' => 'required|min:12',
                'full_name' => 'required',
                'bio' => 'required',
            ]);

            if ($validator->fails()) {
                $failedRules = $validator->failed();

                if (isset($failedRules['email']['Unique'])) {
                    return response()->json(['status' => 'failed', 'message' => 'Looks like you already have an account!'], 400);
                }
            }

            $dataToInsert = [
                'email' => $email,
                'password' => $password,
                'full_name' => $fullName,
                'bio' => $bio
            ];

            $user = User::create($dataToInsert);
            $user->password = bcrypt($password);
            $user->save();

            return response()->json([
                "email" => $email,
                "full_name" => $fullName,
                "bio" => $bio,
                'token' => $user->createToken('tokens')->plainTextToken
            ]);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new \Exception($e->getMessage(), $e->getCode(), $e);
        }

    }

    public function logout() {
        Auth::logout();
    }
}
