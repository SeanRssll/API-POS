<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

class LoginController extends Controller
{
    /**
     * User login API method
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) return sendError('Validation Error.', $validator->errors(), 422);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user             = Auth::user();
            $success['name']  = $user->name;
            $success['token'] = $user->createToken('accessToken')->accessToken;

            return sendResponse($success, 'You are successfully logged in.');
        } else {
            return sendError('Unauthorised', ['error' => 'Unauthorised'], 401);
        }
    }

    /**
     * User registration API method
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Personal information
            'f_name'        => 'required|string|max:255',
            'm_name'        => 'nullable|string|max:255',
            'l_name'        => 'required|string|max:255',
            'birthday'      => 'required|date|before:today',
            'gender'        => 'required|in:Male,Female,Other',

            // Address
            'house_number'  => 'required|string|max:255',
            'street'        => 'required|string|max:255',
            'barangay'      => 'required|string|max:255',
            'city'          => 'required|string|max:255',
            'province'      => 'required|string|max:255',
            'postal_code'   => 'required|string|max:20',

            // Contact and profile
            'contact_num'   => 'required|string|max:20',
            'profile_photo' => 'nullable|string|max:255',

            // Authentication
            'email'         => 'required|email|max:255|unique:users,email',
            'password'      => 'required|min:8',
            'date_hire'     => 'required|date',
        ]);

        if ($validator->fails()) {
            return sendError(
                'Validation Error.',
                $validator->errors(),
                422
            );
        }

        try {
            $user = User::create([
                // Personal information
                'f_name'        => $request->f_name,
                'm_name'        => $request->m_name,
                'l_name'        => $request->l_name,
                'gender'        => $request->gender,
                'birthday'      => $request->birthday,

                // Address
                'house_number'  => $request->house_number,
                'street'        => $request->street,
                'barangay'      => $request->barangay,
                'city'          => $request->city,
                'province'      => $request->province,
                'postal_code'   => $request->postal_code,

                // Contact and profile
                'contact_num'   => $request->contact_num,
                'profile_photo' => $request->profile_photo,

                // Account settings
                'role_id'       => 5,
                'email'         => $request->email,
                'password'      => bcrypt($request->password),
                'date_hire'     => $request->date_hire,
                'is_active'     => true,
            ]);

            $success = [
                'user' => [
                    'employee_id'=> $user->employee_id,
                    'id'         => $user->id,
                    'f_name'     => $user->f_name,
                    'm_name'     => $user->m_name,
                    'l_name'     => $user->l_name,
                    'full_name'  => $user->full_name,
                    'gender'     => $user->gender,
                    'email'      => $user->email,
                    'role_id'    => $user->role_id,
                    'is_active'  => $user->is_active,
                ],
                'token' => $user->createToken('accessToken')->accessToken,
            ];

            $message = 'Yay! A user has been successfully created.';

            return sendResponse($success, $message);

        } catch (\Exception $e) {
            \Log::error('User registration failed: ' . $e->getMessage());

            return sendError(
                'Unable to create a new user.',
                [],
                500
            );
        }
    }

}
