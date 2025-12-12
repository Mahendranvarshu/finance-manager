<!DOCTYPE html>
<html>
<body>
    <h2>Welcome, {{ $user->name }}!</h2>

    <p>Your collector login details are below:</p>
    <ul>
        <li><strong>Name:</strong> {{ $user->name }}</li>
        <li><strong>Phone:</strong> {{ $user->phone }}</li>
        <li><strong>Area:</strong> {{ $user->area }}</li>
        <li><strong>Username:</strong> {{ $user->username }}</li>
        <li><strong>Status:</strong> {{ $user->status }}</li>
    </ul>

    <p>You can now login to your account.</p>
    <p>
        <a href="{{ url('/collector/login') }}" style="padding:10px 18px;background:#2d6cdf;color:#fff;text-decoration:none;border-radius:4px;">Login Here</a>
    </p>

    <p>Thank you,<br>Team</p>
</body>
</html>
