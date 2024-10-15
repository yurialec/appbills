<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>AppBills</title>

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoMTBNGDJ5SmXj6Hx2PvIs7lrpC13g5LGpJIGWtD06iBvXK" crossorigin="anonymous">

    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
            color: #333;
        }

        .email-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
            padding: 30px;
        }

        .email-header {
            font-size: 20px;
            font-weight: bold;
            color: #007bff;
            text-align: center;
            margin-bottom: 20px;
        }

        .email-body p {
            font-size: 16px;
            line-height: 1.6;
        }

        .verification-code {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
            text-align: center;
            margin: 20px 0;
        }

        .email-footer {
            font-size: 14px;
            color: #6c757d;
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            Recuperação de Senha - AppBills
        </div>

        <div class="email-body">
            <p>Prezado(a) <strong>{{ $user->name }}</strong>,</p>
            <p>Para recuperar sua senha, use o código de verificação abaixo:</p>
            <p class="verification-code">{{ $code }}</p>
            <p>Por questões de segurança, este código é válido somente até as <strong>{{ $formattedTime }}</strong> do
                dia <strong>{{ $formattedDate }}</strong>.</p>
            <p>Se o prazo estiver expirado, será necessário solicitar um novo código.</p>
        </div>

        <div class="email-footer">
            Atenciosamente,<br>
            <strong>yuri.alec</strong><br>
            <a href="https://www.appbills.com" class="text-decoration-none text-primary">www.appbills.com</a>
        </div>
    </div>
</body>

</html>
