<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $mail->subject }}</title>
</head>
<body>
<h1>{{ $mail->subject }}</h1>
<p>{!! e($mail->body) !!}</p>
</body>
</html>
