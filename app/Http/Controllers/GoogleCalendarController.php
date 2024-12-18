<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Google\Client;
use Google\Service\Calendar;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;

class GoogleCalendarController extends Controller
{
    /**
     * Przekierowuje użytkownika do Google OAuth.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle()
    {
        $client = new Client();
        $client->setClientId(config('google-calendar.client_id'));
        $client->setClientSecret(config('google-calendar.client_secret'));
        $client->setRedirectUri(route('google-calendar.callback'));
        $client->addScope(Calendar::CALENDAR_EVENTS);
        $client->setAccessType('offline');
        $client->setPrompt('consent');

        $authUrl = $client->createAuthUrl();

        return redirect()->away($authUrl);
    }

    /**
     * Obsługuje callback z Google OAuth.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handleGoogleCallback(Request $request)
    {
        $client = new Client();
        $client->setClientId(config('google-calendar.client_id'));
        $client->setClientSecret(config('google-calendar.client_secret'));
        $client->setRedirectUri(route('google-calendar.callback'));

        $token = $client->fetchAccessTokenWithAuthCode($request->code);

        if (array_key_exists('error', $token)) {
            return redirect()->route('tasks.index')->with('error', 'Nie udało się połączyć z Google Calendar.');
        }

        // Zapisz token do użytkownika
        $user = Auth::user();
        $user->google_calendar_token = json_encode($token);
        $user->save();

        return redirect()->route('tasks.index')->with('success', 'Google Calendar został połączony!');
    }
}


