<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #1B1B1B;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            border-bottom: 4px solid #B5C401;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #1B1B1B;
            margin: 0;
            font-size: 24px;
        }
        .header .subtitle {
            color: #666;
            font-size: 14px;
            margin-top: 5px;
        }
        .header .brand {
            color: #B5C401;
            font-weight: bold;
            font-size: 18px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section h2 {
            color: #1B1B1B;
            border-left: 4px solid #B5C401;
            padding-left: 10px;
            font-size: 16px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table th {
            background-color: #1B1B1B;
            color: #FFFFFF;
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }
        table td {
            padding: 6px 8px;
            border-bottom: 1px solid #E9ECEF;
            font-size: 11px;
        }
        table tr:nth-child(even) {
            background-color: #F8F5F0;
        }
        .summary-box {
            background-color: #F8F5F0;
            border: 1px solid #B5C401;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .summary-box h3 {
            margin: 0 0 10px 0;
            color: #1B1B1B;
            font-size: 14px;
        }
        .summary-item {
            display: inline-block;
            margin-right: 30px;
        }
        .summary-item strong {
            color: #1B1B1B;
        }
        .summary-item .value {
            color: #B5C401;
            font-size: 18px;
            font-weight: bold;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #E9ECEF;
            padding-top: 10px;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-success { background-color: #B5C401; color: #1B1B1B; }
        .badge-warning { background-color: #ffc107; color: #1B1B1B; }
        .badge-danger { background-color: #dc3545; color: #FFFFFF; }
        .badge-info { background-color: #17a2b8; color: #FFFFFF; }
        .badge-secondary { background-color: #6c757d; color: #FFFFFF; }
        .badge-dark { background-color: #1B1B1B; color: #FFFFFF; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-success { color: #28a745; }
        .text-danger { color: #dc3545; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    @yield('content')

    <div class="footer">
        <p>
            <strong>LimaSync</strong> | Generated on {{ $generated_at }} by {{ $generated_by }}<br>
            Lima Deria Sdn Bhd - Spectacular Sustainable Events
        </p>
    </div>
</body>
</html>