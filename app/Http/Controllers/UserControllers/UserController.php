<?php

namespace App\Http\Controllers\UserControllers;

use App\Models\User;
use App\Models\UserModels\Roles;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function userSearch($search){
        return User::selectRaw("users.*,
                                TRIM(CONCAT_WS(' ', f_name, m_name, l_name)) AS full_name")
            ->where('employee_id', $search)
            ->orWhere('f_name', 'LIKE', "%{$search}%")
            ->orWhere('m_name', 'LIKE', "%{$search}%")
            ->orWhere('l_name', 'LIKE', "%{$search}%")
            ->orWhereRaw(
                "CONCAT_WS(' ', f_name, m_name, l_name) LIKE ?",
                ["%{$search}%"]
            )
            ->first();
    }

    public function assignRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_id'       => 'required|integer|exists:roles,id',
            'search'        => 'required|string|min:2|max:100'
        ]);

        if ($validator->fails()) return sendError('Validation Error.', $validator->errors(), 422);

        // $user = User::find($request['search']);
        $user = $this->userSearch(trim($request['search']));
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }

        $role = Roles::find($request['role_id']);

        if (!$role->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot assign an inactive role.',
            ], 422);
        }

        $user->role_id = $role->id;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Role assigned successfully.',
            'data' => [
                'name' => $user->full_name,
                'role' => [
                    'name' => $role->name,
                ],
            ],
        ], 200);
    }

    /**
     * Remove a user's current role.
     */
    public function removeRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search'        => 'required|string|min:2|max:100'
        ]);

        if ($validator->fails()) return sendError('Validation Error.', $validator->errors(), 422);

        $user = $this->userSearch(trim($request['search']));

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }

        if ($user->role_id == 5) {
            return response()->json([
                'success' => false,
                'message' => 'User does not have an assigned role.',
            ], 422);
        }

        // Requires users.role_id to be nullable.
        $user->role_id = 5;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Role removed successfully.',
            'data' => [
                'full_name' => $user->full_name,
            ],
        ], 200);
    }

    public function userDropdown()
    {
        $users = User::with('role')
            ->select([
                'id',
                'employee_id',
                'f_name',
                'm_name',
                'l_name',
                'profile_photo',
                'role_id',
                'is_active',
            ])
            ->orderBy('f_name')
            ->get()
            ->map(function ($user) {
                return [
                    'employee_id' => $user->employee_id,
                    'name' => trim(
                        $user->f_name . ' ' .
                        ($user->m_name ? $user->m_name . ' ' : '') .
                        $user->l_name
                    ),
                    'profile_photo' => $user->profile_photo,
                    'role' => $user->role ? $user->role->name : null,
                    'is_active' => $user->is_active,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }
}
