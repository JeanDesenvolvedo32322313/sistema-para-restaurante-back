<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function read()
    {

        try {
            $users = User::select(
                'users.id',
                'users.name',
                'users.username',
                'users.email',
                'roles.descricao',
                'users.active',
                'users.role_id',
                'users.created_at',
                'users.updated_at',
            )
                ->join('roles', 'roles.id', '=', 'users.role_id')
                ->orderBy('users.id', 'DESC')
                ->get();
            return response()->json($users);
        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'msg' => $e->getMessage()], 200);
        }
    }

    public function create(Request $req)
    {

        $checkUsername = User::select('username')->where('username', '=', $req->username)->first();

        if (!empty($checkUsername)) {
            return response()->json(['status' => 0, 'msg' => 'Este usuário já esta cadastrado.'], 200);
        }

        try {
            $newUser = new User();
            $newUser->name = $req->name;
            $newUser->email = $req->email;
            $newUser->username = $req->username;
            $newUser->password = Hash::make($req->password);
            $newUser->role_id = $req->role_id;
            $newUser->active = $req->active;
            if ($newUser->save()) {
                return response()->json(['status' => 1, 'msg' => "Novo usuário cadastrado com sucesso."], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'msg' => $e->getMessage()], 200);
        }
    }

    public function update(Request $req, User $id)
    {

        try {
            $id->name = $req->name;
            $id->username = $req->username;
            $id->email = $req->email;
            $id->role_id = $req->role_id;
            if ($id->save()) {
                return response()->json(['status' => 1, 'msg' => "Usuário editado com sucesso."], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'msg' => $e->getMessage()], 200);
        }
    }

    public function listRoles()
    {
        $Roles = Roles::select()->get()->toArray();
        return response()->json($Roles);
    }
}
