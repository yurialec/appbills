<?php

namespace App\Service;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


class ResetPasswordValidateCodeService
{

    public function resetPasswordValidateCode($email, $code): array
    {

        $passwordResetTokens = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$passwordResetTokens) {

            Log::notice('Código não encontrado.', ['email' => $email]);

            return [
                'status' => false,
                'message' => 'Código não encontrado!',
            ];
        }

        if (!Hash::check($code, $passwordResetTokens->token)) {

            Log::notice('Código inválido.', ['email' => $email]);

            return [
                'status' => false,
                'message' => 'Código inválido!',
            ];
        }

        $differenceInMinutes = Carbon::parse($passwordResetTokens->created_at)->diffInMinutes(Carbon::now());

        if ($differenceInMinutes > 60) {

            Log::notice('Código expirado.', ['email' => $email]);

            return [
                'status' => false,
                'message' => 'Código expirado!',
            ];

        }

        return [
            'status' => true,
            'message' => 'Código válido!',
        ];
    }
}