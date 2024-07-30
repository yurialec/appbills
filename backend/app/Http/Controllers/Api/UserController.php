<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users =  User::orderBy('name')->paginate(100);

        return response()->json([
            'status' => true,
            'users' => $users
        ], 200);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json([
            'status' => true,
            'user' => $user
        ], 200);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'user' => $user,
                'message' => 'Conta Cadastrada com sucesso',
            ], 201);
        } catch (Exception $err) {
            DB::rollBack();
            return response()->json([
                'status' => true,
                'message' => 'Erro ao cadastrar usuário'
            ], 400);
        }
    }
}
