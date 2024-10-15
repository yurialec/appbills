<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Http\JsonResponse;
use Log;
use Request;

class ProfileController extends Controller
{
    public function show(): JsonResponse
    {
        $user = User::where("id", Auth::user()->id)->first();

        if (!$user) {
            Log::notice("Perfil Não encontrado");

            return response()->json([
                'status' => false,
                'message' => 'Perfil Não encontrado'
            ], 400);
        }

        Log::info('Visualizar perfil', [
            'user_id' => $user->id,
            'action_user_id' => Auth::user()->id
        ]);

        return response()->json([
            'status' => false,
            'user' => $user
        ], 200);
    }

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = User::where("id", Auth::user()->id)->first();

        DB::beginTransaction();
        if (!$user) {
            Log::notice("Perfil Não encontrado");

            return response()->json([
                'status' => false,
                'message' => 'Perfil Não encontrado'
            ], 400);
        }

        try {

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'updated_at' => Carbon::now(),
            ]);

            DB::commit();

            Log::info('Perfil Editado', ['user_id' => $user->id, 'action_user_id' => Auth::id()]);

            return response()->json(data: [
                'status' => true,
                'user' => $user,
                'message' => 'Perfil atualizado com sucesso!',
            ], status: 200);

        } catch (\Exception $err) {
            DB::rollBack();
            Log::warning('Erro', ['erro' => $err->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Perfil Não encontrado'
            ], 400);
        }
    }
}
