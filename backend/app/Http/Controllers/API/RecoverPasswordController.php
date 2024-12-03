<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordCodeRequest;
use App\Http\Requests\ResetPasswordValidateCodeRequest;
use App\Mail\SendEmailForgotPassword;
use App\Models\User;
use App\Service\ResetPasswordValidateCodeService;
use Carbon\Carbon;
use DB;
use Exception;
use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Log;
use Mail;

class RecoverPasswordController extends Controller
{
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $user = User::where("email", $request->email)->first();

        if (!$user) {
            Log::warning("Tentativa de recuperação de senha para um e-mail não cadastrado.", ["email" => $request->email]);
            return response()->json([
                'status' => false,
                'message' => 'E-mail não localizado.',
            ], 400);
        }

        try {
            $userPasswordReset = DB::table('password_reset_tokens')->where('email', $request->email)->first();

            if ($userPasswordReset) {
                DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            }

            $code = mt_rand(100000, 999999);
            $token = Hash::make($code);

            $userNewPasswordResets = DB::table('password_reset_tokens')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now(),
            ]);

            if (!$userNewPasswordResets) {
                throw new Exception('Falha ao criar o token de recuperação de senha.');
            }

            $currentDate = Carbon::now();
            $oneHourLater = $currentDate->addHour();
            $formattedDate = $oneHourLater->format('d/m/Y');
            $formattedTime = $oneHourLater->format('H:i');

            Mail::to($user->email)->send(new SendEmailForgotPassword($user, $code, $formattedDate, $formattedTime));

            return response()->json([
                'status' => true,
                'message' => 'Enviamos um e-mail com instruções para redefinir sua senha. Por favor, verifique sua caixa de entrada.',
            ]);
        } catch (Exception $err) {
            Log::warning('Erro ao recuperar senha', [
                'email' => $request->email,
                'erro' => $err->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Erro ao recuperar senha. Tente novamente mais tarde.',
            ], 400);
        }
    }

    public function resetPasswordValidateCode(ResetPasswordValidateCodeRequest $request, ResetPasswordValidateCodeService $resetPasswordValidateCode)
    {
        try {

            $validationResult = $resetPasswordValidateCode->resetPasswordValidateCode($request->email, $request->code);

            if (!$validationResult['status']) {

                return response()->json([
                    'status' => false,
                    'message' => $validationResult['message'],
                ], 400);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user) {

                Log::notice('Usuário não encontrado.', ['email' => $request->email]);

                return response()->json([
                    'status' => false,
                    'message' => 'Usuário não encontrado.',
                ], 400);
            }

            Log::info('Código recuperar senha válido.', ['email' => $request->email]);

            return response()->json([
                'status' => true,
                'message' => 'Código recuperar senha válido.',
            ], 200);

        } catch (Exception $e) {

            Log::warning('Erro validar código recuperar senha.', ['email' => $request->email, 'error' => $e->getMessage()]);

            return response()->json([
                'status' => false,
                'message' => 'Código inválido.',
            ], 400);
        }
    }

    public function resetPasswordCode(ResetPasswordCodeRequest $request, ResetPasswordValidateCodeService $resetPasswordValidateCode): JsonResponse
    {
        try {

            $validationResult = $resetPasswordValidateCode->resetPasswordValidateCode($request->email, $request->code);

            if (!$validationResult['status']) {
                return response()->json([
                    'status' => false,
                    'message' => $validationResult['message'],
                ], 400);

            }

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                Log::notice('Usuário não encontrado.', ['email' => $request->email]);
                return response()->json([
                    'status' => false,
                    'message' => 'Usuário não encontrado!',
                ], 400);
            }

            $token = $user->first()->createToken('api-token')->plainTextToken;

            $userPasswordResets = DB::table('password_reset_tokens')->where('email', $request->email);
            if ($userPasswordResets) {
                $userPasswordResets->delete();
            }

            $user->update([
                'password' => Hash::make($request->password)
            ]);

            Log::info('Senha atualizada com sucesso.', ['email' => $request->email]);

            return response()->json([
                'status' => true,
                'user' => $user,
                'token' => $token,
                'message' => 'Senha atualizada com sucesso!',
            ], 200);

        } catch (Exception $err) {

            Log::warning('Erro ao atualizar senha.', ['email' => $request->email, 'error' => $err->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Erro ao atualizar senha.',
            ], 400);
        }
    }
}
