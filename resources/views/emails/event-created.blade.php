<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Event Created</title>
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
        .event-box {
            background-color: #F8F5F0;
            border-left: 4px solid #B5C401;
            padding: 20px;
            margin: 20px 0;
            border-radius: 6px;
        }
        .event-box h3 {
            color: #1B1B1B;
            margin: 0 0 15px 0;
            font-size: 15px;
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
            font-weight: 600;
        }
        .info-value {
            color: #1B1B1B;
            font-weight: 600;
            font-size: 14px;
            margin-top: 4px;
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
            <h1>LimaSync</h1>
            <p>New Event Notification</p>
        </div>

        <div class="body">
            <p class="greeting">Hello, {{ $client->contact_person }}!</p>

            <p class="text">
                Lima Deria has created a new event for <strong>{{ $client->company_name }}</strong>. 
                You can now track the progress of this event in real time through your LimaSync dashboard.
            </p>

            <div class="event-box">
                <h3>Event Details</h3>

                <div class="info-row">
                    <div class="info-label">Event Name</div>
                    <div class="info-value">{{ $event->event_name }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Event Type</div>
                    <div class="info-value">{{ $event->event_type }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Venue</div>
                    <div class="info-value">{{ $event->venue ?? 'To be confirmed' }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Start Date</div>
                    <div class="info-value">{{ $event->start_date->format('d M Y') }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">End Date</div>
                    <div class="info-value">{{ $event->end_date->format('d M Y') }}</div>
                </div>

                @if($event->estimated_attendees)
                <div class="info-row">
                    <div class="info-label">Estimated Attendees</div>
                    <div class="info-value">{{ $event->estimated_attendees }}</div>
                </div>
                @endif
            </div>

            <p class="text">
                Log in to your dashboard to monitor event progress, view sustainability metrics, and download reports.
            </p>

            <a href="{{ url('/login') }}" class="btn">
                View Event Progress
            </a>
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