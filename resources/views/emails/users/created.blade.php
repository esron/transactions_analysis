@component('mail::message')
# Olá {{ $userName }}

Seu usuário foi criado com sucesso e sua senha é **{{ $password  }}**

Obrigado,<br>
{{ config('app.name') }}
@endcomponent
