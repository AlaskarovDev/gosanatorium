<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f6f8;
            color: #333333;
        }
        .email-wrapper {
            max-width: 650px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .email-header {
            background-color: #2c3e50;
            color: #ffffff;
            padding: 25px 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
        }
        .email-body {
            padding: 30px;
            line-height: 1.7;
        }
        .email-body h2 {
            color: #2c3e50;
            font-size: 18px;
            margin-top: 0;
        }
        .email-body table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .email-body table td {
            padding: 10px 12px;
            border: 1px solid #e0e0e0;
            font-size: 14px;
        }
        .email-body table tr td:first-child {
            background-color: #f8f9fa;
            font-weight: 600;
            width: 40%;
            color: #555;
        }
        .email-footer {
            background-color: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            font-size: 12px;
            color: #999999;
            border-top: 1px solid #e0e0e0;
        }
        .email-footer a {
            color: #2c3e50;
            text-decoration: none;
        }
        @media only screen and (max-width: 600px) {
            .email-body {
                padding: 20px 15px;
            }
            .email-body table td {
                display: block;
                width: 100% !important;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <!-- Header -->
        <div class="email-header">
            <h1>{{ config('app.name', 'Booking System') }}</h1>
        </div>

        <!-- Body - Admin paneldən redaktə edilən məzmun -->
        <div class="email-body">
            {!! $mailBody !!}
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Bütün hüquqlar qorunur.</p>
            <p>Bu email avtomatik göndərilib. Zəhmət olmasa cavab yazmayın.</p>
        </div>
    </div>
</body>
</html>