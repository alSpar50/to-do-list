<?php

return [
    'client_id' => env('GOOGLE_CALENDAR_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CALENDAR_CLIENT_SECRET'),
    'redirect_uri' => env('GOOGLE_CALENDAR_REDIRECT_URI', 'http://localhost:8000/google-calendar/callback'),
    'default_timezone' => env('GOOGLE_CALENDAR_DEFAULT_TIMEZONE', 'Europe/Warsaw'),
];
