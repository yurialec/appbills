<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $users = User::orderByDesc('id')->paginate(perPage: 10);

        return response()->json(data: [
            'status' => true,
            'users' => $users
        ], status: 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            DB::commit();
            return response()->json(data: [
                'status' => true,
                'user' => $user,
                'message' => 'Usuário cadastrado com sucesso!',
            ], status: 200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::warning('Erro', ['erro' => $e->getMessage()]);
            return response()->json(data: [
                'status' => false,
                'message' => 'Erro ao cadastrar usuário!',
            ], status: 201);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResponse
    {
        return response()->json([
            'status' => true,
            'user' => $user
        ], status: 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        DB::beginTransaction();

        try {

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            DB::commit();

            return response()->json(data: [
                'status' => true,
                'user' => $user,
                'message' => 'Usuário atualizado com sucesso!',
            ], status: 200);

        } catch (Exception $e) {
            DB::rollBack();
            Log::warning('Erro', ['erro' => $e->getMessage()]);
            return response()->json(data: [
                'status' => false,
                'message' => 'Erro ao alterar usuário!',
            ], status: 201);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        DB::beginTransaction();
        try {
            $user->delete();

            DB::commit();

            return response()->json(data: [
                'status' => true,
                'message' => 'Usuário excluido com sucesso!',
            ], status: 200);

        } catch (Exception $e) {
            DB::rollBack();
            Log::warning('Erro', ['erro' => $e->getMessage()]);
            return response()->json(data: [
                'status' => false,
                'message' => 'Erro ao excluir usuário!',
            ], status: 201);
        }
    }
}
