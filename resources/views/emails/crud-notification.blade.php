<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>LimaSync Notification</title>
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
            padding: 20px 30px;
            border-bottom: 4px solid #B5C401;
        }
        .header h1 {
            margin: 0;
            color: #B5C401;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #B0B0B0;
            font-size: 13px;
        }
        .body {
            padding: 30px;
        }
        .badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            color: #FFFFFF;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-create { background-color: #17a2b8; }
        .badge-update { background-color: #ffc107; color: #1B1B1B; }
        .badge-delete { background-color: #dc3545; }
        .badge-view   { background-color: #6c757d; }
        .badge-download { background-color: #B5C401; color: #1B1B1B; }
        .info-box {
            background-color: #F8F5F0;
            border-left: 4px solid #B5C401;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-row {
            padding: 8px 0;
            border-bottom: 1px solid #E9ECEF;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            color: #6c757d;
            font-size: 12px;
            text-transform: uppercase;
        }
        .info-value {
            color: #1B1B1B;
            font-weight: 600;
            font-size: 14px;
            margin-top: 3px;
        }
        .footer {
            background-color: #F8F5F0;
            padding: 20px 30px;
            text-align: center;
            color: #6c757d;
            font-size: 12px;
            border-top: 3px solid #B5C401;
        }
        .btn {
            display: inline-block;
            background-color: #B5C401;
            color: #1B1B1B !important;
            padding: 12px 24px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>LimaSync</h1>
            <p>System Activity Notification</p>
        </div>

        <div class="body">
            <span class="badge badge-{{ $action }}">{{ ucfirst($action) }}</span>

            <h2 style="color: #1B1B1B; margin: 20px 0 10px 0;">
                {{ $modelType }} {{ ucfirst($action) }}
            </h2>

            <p style="color: #4a4a4a; font-size: 14px; line-height: 1.6;">
                {{ $description }}
            </p>

            <div class="info-box">
                <div class="info-row">
                    <div class="info-label">Action</div>
                    <div class="info-value">{{ ucfirst($action) }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Model</div>
                    <div class="info-value">{{ $modelType }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Record</div>
                    <div class="info-value">{{ $modelName }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Performed By</div>
                    <div class="info-value">{{ $userName }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Timestamp</div>
                    <div class="info-value">{{ $timestamp }}</div>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>
                LimaSync © {{ date('Y') }} | Lima Deria Sdn Bhd<br>
                This is an automated notification. Please do not reply.
            </p>
        </div>
    </div>
</body>
</html>