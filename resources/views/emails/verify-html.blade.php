<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Your Email Address - SkyLine Airlines</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #0a192f;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            color: #f4b400;
            margin: 0;
            font-size: 28px;
        }
        .content {
            padding: 40px 30px;
            text-align: center;
        }
        .logo {
            font-size: 48px;
            margin-bottom: 20px;
        }
        .greeting {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }
        .message {
            font-size: 14px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .button {
            display: inline-block;
            padding: 14px 40px;
            background-color: #0a192f;
            color: #f4b400 !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 14px;
            margin: 20px 0;
            border: 2px solid #f4b400;
        }
        .button:hover {
            background-color: #f4b400;
            color: #0a192f !important;
        }
        .footer {
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #eeeeee;
        }
        .footer p {
            font-size: 12px;
            color: #999;
            margin: 5px 0;
        }
        .footer .company {
            font-weight: bold;
            color: #333;
        }
        .url-text {
            font-size: 11px;
            color: #666;
            word-break: break-all;
            background-color: #f8f8f8;
            padding: 10px;
            border-radius: 4px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>SkyLine Airlines</h1>
        </div>

        <div class="content">
            <div class="logo">✈️</div>
            
            <p class="greeting">Hello!</p>
            
            <p class="message">
                Please click the button below to verify your email address.
            </p>

            <a href="{{ $verificationUrl }}" class="button">
                Verify Email Address
            </a>

            <p class="message">
                If you did not create an account, no further action is required.
            </p>

            <div class="url-text">
                If you're having trouble clicking the "Verify Email Address" button, copy and paste the URL below into your web browser:<br>
                {{ $verificationUrl }}
            </div>
        </div>

        <div class="footer">
            <p class="company">SkyLine Airlines</p>
            <p>Regards,</p>
        </div>
    </div>
</body>
</html>