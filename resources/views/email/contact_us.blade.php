<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Contact Us</title>
</head>
<body>
    <h5>You Have Received a New Contact</h5>
    <p><strong>Name:</strong> {{ $data['name'] }}</p>
    <p><strong>Email:</strong> <a href="mail:{{ $data['email'] }}">{{ $data['email'] }}</a></p>
     <p><strong>Phone:</strong> <a href="tel:{{ $data['phone'] }}">{{ $data['phone'] }}</a></p>
    <p><strong>Message:</strong> {{ $data['message'] }}</p>
    <br>
    <hr>
    <p>Regards,<br>
    Meishicadi By Chapy Inc
    </p>
</body>
</html>
