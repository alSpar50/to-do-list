<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Mail\TaskReminder;
use App\Jobs\SendTaskReminderEmail;
use Spatie\Activitylog\Models\Activity;
use App\Http\Controllers\GoogleCalendarController;

class TaskController extends Controller
{





    /**
     * Konstruktor kontrolera.
     *
     * Używa middleware 'auth' do ochrony wszystkich metod w tym kontrolerze.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Wyświetla listę zadań.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Filtrowanie
        $query = Task::where('user_id', $user->id);

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('due_date')) {
            $query->whereDate('due_date', $request->due_date);
        }

        $tasks = $query->orderBy('due_date', 'asc')->paginate(10);

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Wyświetla formularz do tworzenia nowego zadania.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Zapisuje nowe zadanie w bazie danych.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:to-do,in progress,done',
            'due_date' => 'required|date|after_or_equal:today',
        ]);

        $task = new Task($validated);
        $task->user_id = Auth::id();
        $task->save();

        // Dispatch job for email reminder
        SendTaskReminderEmail::dispatch($task);

        return redirect()->route('tasks.index')->with('success', 'Zadanie zostało dodane.');
    }

    /**
     * Wyświetla szczegóły konkretnego zadania.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        // Sprawdzenie, czy zadanie należy do zalogowanego użytkownika
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        return view('tasks.show', compact('task'));
    }

    /**
     * Wyświetla formularz do edycji zadania.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        // Sprawdzenie, czy zadanie należy do zalogowanego użytkownika
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        return view('tasks.edit', compact('task'));
    }

    /**
     * Aktualizuje dane konkretnego zadania.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        // Sprawdzenie, czy zadanie należy do zalogowanego użytkownika
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:to-do,in progress,done',
            'due_date' => 'required|date|after_or_equal:today',
        ]);

        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', 'Zadanie zostało zaktualizowane.');
    }

    /**
     * Usuwa konkretne zadanie z bazy danych.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        // Sprawdzenie, czy zadanie należy do zalogowanego użytkownika
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Zadanie zostało usunięte.');
    }

    /**
     * Udostępnia zadanie za pomocą tokenu.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function share(Task $task)
    {
        // Sprawdzenie, czy zadanie należy do zalogowanego użytkownika
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        // Generowanie tokenu jeśli nie istnieje
        if (!$task->share_token) {
            $task->share_token = Str::random(32);
            $task->save();
        }

        $shareUrl = route('tasks.public.show', $task->share_token);

        return view('tasks.share', compact('task', 'shareUrl'));
    }

    /**
     * Wyświetla zadanie publicznie za pomocą tokenu.
     *
     * @param  string  $token
     * @return \Illuminate\Http\Response
     */
    public function publicShow($token)
    {
        $task = Task::where('share_token', $token)->firstOrFail();

        return view('tasks.public_show', compact('task'));
    }

    /**
     * Wyświetla historię zmian zadania.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function history(Task $task)
    {
        // Sprawdzenie, czy zadanie należy do zalogowanego użytkownika
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        // Pobranie aktywności zarejestrowanej przez spatie/laravel-activitylog
        $activities = $task->activities()->latest()->get();

        return view('tasks.history', compact('task', 'activities'));
    }

    /**
     * Dodaje zadanie do Google Calendar.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function addToGoogleCalendar(Task $task)
    {
        // Sprawdzenie, czy zadanie należy do zalogowanego użytkownika
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        // Sprawdzenie, czy użytkownik połączył swoje konto Google Calendar
        $user = Auth::user();
        if (!$user->google_calendar_token) {
            return redirect()->route('google-calendar.connect')->with('error', 'Najpierw połącz swoje konto Google Calendar.');
        }

        // Inicjalizacja klienta Google
        $client = new \Google\Client();
        $client->setClientId(config('google-calendar.client_id'));
        $client->setClientSecret(config('google-calendar.client_secret'));
        $client->setRedirectUri(config('google-calendar.redirect_uri'));
        $client->setAccessToken(json_decode($user->google_calendar_token, true));

        // Odświeżenie tokenu jeśli wygasł
        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            $user->google_calendar_token = json_encode($client->getAccessToken());
            $user->save();
        }

        $service = new \Google\Service\Calendar($client);

        // Tworzenie wydarzenia
        $event = new \Google\Service\Calendar\Event([
            'summary' => $task->name,
            'description' => $task->description,
            'start' => [
                'dateTime' => $task->due_date . 'T09:00:00',
                'timeZone' => config('google-calendar.default_timezone'),
            ],
            'end' => [
                'dateTime' => $task->due_date . 'T10:00:00',
                'timeZone' => config('google-calendar.default_timezone'),
            ],
        ]);

        // Dodanie wydarzenia do kalendarza
        $calendarId = 'primary';
        $event = $service->events->insert($calendarId, $event);

        return back()->with('success', 'Zadanie zostało dodane do Google Calendar.');
    }



}



