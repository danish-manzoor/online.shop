<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <p>Name: {{$mailData['user']->name}}</p>
    <h2>Request to reset your password information</h2>
    <p>Please click on the link</p>
    <a href="{{route('front.reset.password',$mailData['token'])}}">Click Here</a>
</body>
</html>