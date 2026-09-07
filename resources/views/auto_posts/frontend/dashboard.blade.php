<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoPost Dashboard</title>
</head>
 
<body>
    <div style="text-align: center; margin-top: 50px;">
        <h1>Welcome to AutoPost Dashboard</h1>
        <p>You are logged in as {{ auth()->user()->name }}</p>
        <a href="{{ route('logout') }}">Logout</a>
    </div>
</body>

</html>