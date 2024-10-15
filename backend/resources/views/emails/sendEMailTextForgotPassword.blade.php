Prezado(a) {{ $user->name }}.
Para recuperar sua senha, use o código de verificação abaixo:
{{ $code }}
Por questtões de segurança esse código é válido somente até as {{ $formattedTime }}
do dia {{ $formattedDate }}.
Caso esse prazo esteja expirado, será necessário soliciar outro código.

Atenciosamente,
yuri.alec
