@component('mail::message')
# Vérification de votre adresse email

Bonjour {{ $user->name ?? 'cher utilisateur' }},

Merci de vous être inscrit sur **{{ $appName }}**.

Pour activer votre compte, veuillez cliquer sur le bouton ci-dessous :

@component('mail::button', ['url' => $url])
Vérifier mon email
@endcomponent

Ce lien de vérification expire dans **{{ $expire }} minutes**.

Si vous n’avez pas créé de compte, vous pouvez ignorer cet email.

Merci,  
L’équipe {{ $appName }}

@component('mail::subcopy')
Si le bouton ne fonctionne pas, copiez et collez ce lien dans votre navigateur :

{{ $url }}
@endcomponent
@endcomponent