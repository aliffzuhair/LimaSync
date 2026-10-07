<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welcome to LimaSync</title>
    <style>
        body {
            font-family: 'Montserrat', Arial, sans-serif;
            background-color: #F8F5F0;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #FFFFFF;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        .header {
            background-color: #1B1B1B;
            color: #FFFFFF;
            padding: 25px 30px;
            text-align: center;
            border-bottom: 4px solid #B5C401;
        }
        .header h1 {
            margin: 0;
            color: #B5C401;
            font-size: 26px;
        }
        .header p {
            margin: 8px 0 0 0;
            color: #B0B0B0;
            font-size: 13px;
        }
        .body {
            padding: 30px;
        }
        .greeting {
            color: #1B1B1B;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .text {
            color: #4a4a4a;
            font-size: 14px;
            line-height: 1.6;
        }
        .credentials-box {
            background-color: #F8F5F0;
            border-left: 4px solid #B5C401;
            padding: 20px;
            margin: 20px 0;
            border-radius: 6px;
        }
        .credentials-box h3 {
            color: #1B1B1B;
            margin: 0 0 15px 0;
            font-size: 15px;
        }
        .credential-row {
            padding: 10px 0;
            border-bottom: 1px solid #E9ECEF;
        }
        .credential-row:last-child {
            border-bottom: none;
        }
        .credential-label {
            color: #6c757d;
            font-size: 12px;
            text-transform: uppercase;
            font-weight: 600;
        }
        .credential-value {
            color: #1B1B1B;
            font-weight: 600;
            font-size: 15px;
            font-family: 'Courier New', monospace;
            margin-top: 5px;
            padding: 8px 12px;
            background: #FFFFFF;
            border: 1px solid #E9ECEF;
            border-radius: 4px;
            display: inline-block;
        }
        .btn {
            display: inline-block;
            background-color: #B5C401;
            color: #1B1B1B !important;
            padding: 14px 32px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: bold;
            font-size: 15px;
            margin-top: 20px;
        }
        .warning {
            background-color: #FFF3CD;
            border-left: 4px solid #ffc107;
            padding: 12px 15px;
            border-radius: 4px;
            margin-top: 20px;
            font-size: 13px;
            color: #856404;
        }
        .footer {
            background-color: #F8F5F0;
            padding: 20px 30px;
            text-align: center;
            color: #6c757d;
            font-size: 12px;
            border-top: 3px solid #B5C401;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🌿 LimaSync</h1>
            <p>Welcome to Lima Deria's Event Management Portal</p>
        </div>

        <div class="body">
            <p class="greeting">Hello, {{ $user->full_name }}!</p>

            <p class="text">
                Your LimaSync account has been created by the administrator. 
                You can now log in to the system using the credentials below.
            </p>

            <div class="credentials-box">
                <h3>🔑 Your Login Credentials</h3>

                <div class="credential-row">
                    <div class="credential-label">Login URL</div>
                    <div class="credential-value">{{ $loginUrl }}</div>
                </div>

                <div class="credential-row">
                    <div class="credential-label">Email</div>
                    <div class="credential-value">{{ $user->email }}</div>
                </div>

                <div class="credential-row">
                    <div class="credential-label">Password</div>
                    <div class="credential-value">{{ $plainPassword }}</div>
                </div>

                <div class="credential-row">
                    <div class="credential-label">Role</div>
                    <div class="credential-value">{{ ucfirst($user->role->name ?? 'N/A') }}</div>
                </div>

                @if($user->department)
                <div class="credential-row">
                    <div class="credential-label">Department</div>
                    <div class="credential-value">{{ $user->department }}</div>
                </div>
                @endif
            </div>

            <a href="{{ $loginUrl }}" class="btn">
                Log In to LimaSync
            </a>

            <div class="warning">
                ⚠️ <strong>Important:</strong> For security reasons, please change your password 
                immediately after your first login. Go to <strong>My Profile → Change Password</strong>.
            </div>
        </div>

        <div class="footer">
            <p>
                LimaSync © {{ date('Y') }} | Lima Deria Sdn Bhd<br>
                This is an automated email. Please do not reply.
            </p>
        </div>
    </div>
</body>
</html>