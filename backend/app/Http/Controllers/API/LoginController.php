<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Log;

class LoginController extends Controller
{
    public function login(Request $request): JsonResponse|null
    {
        try {
            if (Auth::attempt($request->only("email", "password"))) {
                $user = Auth::user();

                $token = $request->user()->createToken('api-token')->plainTextToken;

                Log::info('Login Realizado com sucesso', ['action_user_id' => Auth::user()->id]);

                return response()->json([
                    'status' => true,
                    'token' => $token,
                    'user' => $user,
                ], 201);

            } else {
                Log::info('Login ou senha incorreta', ['email' => $request->email]);

                return response()->json([
                    "status" => false,
                    "message" => "Erro ao realizar login."
                ], 404);
            }
        } catch (Exception $e) {
            Log::warning('Erro ao avlidar os dados', ['erro' => $e->getMessage()]);
        }
    }

    public function logout(): JsonResponse
    {
        try {

            $authUserId = Auth::check() ? Auth::id() : '';

            if (!$authUserId) {
                return response()->json([
                    "status" => false,
                    "message" => "Usuário não está logado."
                ], 400);
            }

            $user = User::where('id', $authUserId)->first();
            $user->tokens()->delete();

            return response()->json([
                "status" => true,
                "message" => "Deslogado com sucesso."
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Erro ao realizar logout."
            ], 400);
        }
    }
}
