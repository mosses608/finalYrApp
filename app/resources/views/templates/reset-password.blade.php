<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            width: 95%;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.15);
            text-align: center;
            overflow: hidden;
        }
        .header {
            background: #007bff;
            color: #ffffff;
            padding: 15px;
            font-size: 20px;
            font-weight: 600;
        }
        .content {
            padding: 20px;
            text-align: left;
        }
        .content p {
            font-size: 16px;
            font-weight: 300;
            line-height: 1.6;
            color: #555;
        }
        .btn {
            display: inline-block;
            background: #28a745;
            color: #ffffff !important;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            transition: background 0.3s, transform 0.2s;
        }
        .btn:hover {
            background: #218838;
            transform: scale(1.05);
        }
        .footer {
            background: #ffffff;
            border-top: 1px solid #ddd;
            padding: 10px;
            font-size: 14px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Password Reset Request</div>
        <div class="content">
            <p>Hi <b>{{ $email }}</b>,</p>
            <p>You recently requested to reset your password. To ensure the security of your account, please click the button below to proceed with resetting your password. This link will expire after a certain period for your protection, so we recommend completing the process as soon as possible.</p>
            <center><a class="btn" href="{{ $resetLink }}" role="button">Reset Password</a></center><br><br>
            <p>If you did not request this, please ignore this email.</p>
        </div>
        <div class="footer">&copy; 2025 | All rights reserved.</div>
    </div>
</body>
</html>
