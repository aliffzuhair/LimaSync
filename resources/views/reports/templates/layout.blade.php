<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #1B1B1B;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        /* ============ HEADER ============ */
        .header {
            text-align: center;
            padding: 0 0 20px 0;
            border-bottom: 3px solid #B5C401;
            margin-bottom: 25px;
        }

        .header .brand {
            color: #B5C401;
            font-weight: bold;
            font-size: 16px;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .header h1 {
            color: #1B1B1B;
            margin: 0 0 6px 0;
            font-size: 22px;
            font-weight: 700;
        }

        .header .subtitle {
            color: #6c757d;
            font-size: 12px;
            font-style: italic;
        }

        /* ============ SECTIONS ============ */
        .section {
            margin-bottom: 22px;
            page-break-inside: avoid;
        }

        .section h2 {
            color: #1B1B1B;
            font-size: 14px;
            font-weight: 700;
            margin: 0 0 12px 0;
            padding-left: 10px;
            border-left: 4px solid #B5C401;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ============ TABLES ============ */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table th {
            background-color: #1B1B1B;
            color: #FFFFFF;
            padding: 8px 10px;
            text-align: left;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border: none;
        }

        table td {
            padding: 8px 10px;
            border-bottom: 1px solid #E9ECEF;
            font-size: 10.5px;
            color: #1B1B1B;
        }

        table tr:nth-child(even) td {
            background-color: #F8F5F0;
        }

        /* Info table (label-value pairs) */
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table tr th {
            width: 22%;
            background-color: #F8F5F0;
            color: #6c757d;
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 9px 10px;
            border-bottom: 1px solid #E9ECEF;
            text-align: left;
            vertical-align: top;
        }

        .info-table tr td {
            background-color: #FFFFFF;
            padding: 9px 10px;
            border-bottom: 1px solid #E9ECEF;
            color: #1B1B1B;
            font-size: 10.5px;
            vertical-align: top;
        }

        .info-table tr:nth-child(even) td {
            background-color: #F8F5F0;
        }

        .info-table tr:nth-child(even) th {
            background-color: #F0EDE7;
        }

        /* ============ SUMMARY BOX ============ */
        .summary-box {
            background-color: #F8F5F0;
            border: 1px solid #E9ECEF;
            border-left: 4px solid #B5C401;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .summary-grid {
            width: 100%;
        }

        .summary-grid td {
            padding: 8px;
            border: none;
            background: transparent !important;
            text-align: center;
            vertical-align: top;
        }

        .summary-grid .label {
            color: #6c757d;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .summary-grid .value {
            color: #B5C401;
            font-size: 16px;
            font-weight: bold;
            line-height: 1.2;
        }

        .summary-grid .unit {
            color: #6c757d;
            font-size: 9px;
        }

        /* ============ BADGES ============ */
        .badge {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .badge-success { background-color: #B5C401; color: #1B1B1B; }
        .badge-warning { background-color: #ffc107; color: #1B1B1B; }
        .badge-danger  { background-color: #dc3545; color: #FFFFFF; }
        .badge-info    { background-color: #17a2b8; color: #FFFFFF; }
        .badge-secondary { background-color: #6c757d; color: #FFFFFF; }
        .badge-dark    { background-color: #1B1B1B; color: #FFFFFF; }

        /* ============ TEXT UTILITIES ============ */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-success { color: #28a745; font-weight: bold; }
        .text-danger { color: #dc3545; font-weight: bold; }
        .text-muted { color: #6c757d; }

        /* ============ DECLARATION BOX ============ */
        .declaration {
            background-color: #F8F5F0;
            border-left: 4px solid #B5C401;
            padding: 15px;
            border-radius: 4px;
            margin-top: 10px;
        }

        .signature-table {
            margin-top: 20px;
        }

        .signature-table td {
            padding: 15px 10px 5px 10px;
            vertical-align: top;
            border: none;
            background: transparent !important;
        }

        .signature-line {
            border-top: 1px solid #1B1B1B;
            margin-top: 40px;
            padding-top: 5px;
            font-size: 9px;
            color: #6c757d;
        }

        /* ============ FOOTER ============ */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #6c757d;
            border-top: 2px solid #B5C401;
            padding-top: 8px;
            background: #FFFFFF;
        }

        .footer strong {
            color: #B5C401;
        }

        /* ============ PAGE BREAK ============ */
        .page-break {
            page-break-after: always;
        }

        /* ============ CONCLUSION BOX ============ */
        .conclusion-box {
            background-color: #F8F5F0;
            border-radius: 6px;
            padding: 15px;
            border: 1px solid #E9ECEF;
        }

        .conclusion-box p {
            margin: 0 0 10px 0;
            font-size: 11px;
            line-height: 1.6;
        }

        .conclusion-box p:last-child {
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    @yield('content')

    <div class="footer">
        <strong>LimaSync</strong> | Lima Deria Sdn Bhd - Spectacular Sustainable Events<br>
        Generated on {{ $generated_at }} by {{ $generated_by }}
    </div>
</body>
</html>