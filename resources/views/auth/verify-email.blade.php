<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Talika user Verification</title>
</head>
<body>
    <h2>Hellow</h2>
    <p>
        Please click the button bellow to verify your email address.
    </p>
    <div style="text-align: center;">
        <a style="padding:5px 20px; border:1px;background:blue;color:white;" href="{{ route('register-verify',$user->email_verify_token)  }}">
            Click To Verify
        </a>
    </div>
    <p>Please Click Button to Confirm your Talika App Account</p>


    <p>Regards,</p>
    <p>Talika App</p>
</body>
</html>
