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

            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:users',
                'password' => ['required', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/'],
                'full_name' => 'required|regex:/^[\p{L}\s-]+$/u',
                'bio' => 'required',
            ]);

            if ($validator->fails()) {
                $failedRules = $validator->failed();

                if (isset($failedRules['email']['Unique'])) {
                    return response()->json(['message' => 'Looks like you already have an account!'], 400);
                }

                if(isset($failedRules['full_name']['Regex'])) {
                    return response()->json(['message' => 'Your full name can only contain letters!'], 400);
                }

                if (isset($failedRules['password']['Min'])) {
                    return response()->json(['message' => 'Enter a password with at least 8 characters!'], 400);
                }

                if(isset($failedRules['password']['Regex'])) {
                    return response()->json(['message' => 'Please make sure you have at least 1 lowercase and 1 uppercase and 1 number and 1 symbol!'], 400);
                }

                /**
                 * Doug - Validations that don't match the explicitly checked ones aren't being handled. Causes
                 * code to continue executing.  Eg pass no parameters and this throws an exception when it tries
                 * to create a User with a null email.
                 */
            }

            $email = $request->get('email');
            $password = $request->get('password');
            $fullName = $request->get('full_name');
            $bio = $request->get('bio');

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
            /**
             * Doug - Catching and re-throwing ValidationExceptions is causing me to get 500 responses with no
             * messages.  No real need for this.
             */
            Log::error($e->getMessage());
            throw new \Exception($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->user()->tokens()->delete();

        return response()->json(['message' => 'You have successfully logged out and the token was successfully deleted']);
    }
}
