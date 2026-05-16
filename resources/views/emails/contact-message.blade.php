<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouveau message portfolio</title>
</head>
<body>
    <h1>Nouveau message reçu</h1>
    <p><strong>Nom :</strong> {{ $contact->name }}</p>
    <p><strong>Email :</strong> {{ $contact->email }}</p>
    <p><strong>Objet :</strong> {{ $contact->subject }}</p>
    <p><strong>Message :</strong></p>
    <p>{{ nl2br(e($contact->message)) }}</p>
    <p>Envoyé le {{ $contact->created_at->format('d/m/Y H:i') }}</p>
</body>
</html>
