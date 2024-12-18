@component('mail::message')
    # Przypomnienie o Zadaniu

    Witaj {{ $task->user->name }},

    Przypominamy o nadchodzącym terminie zadania:

    **Nazwa:** {{ $task->name }}

    @if($task->description)
        **Opis:** {{ $task->description }}
    @endif

    **Priorytet:** {{ ucfirst($task->priority) }}

    **Status:** {{ ucfirst($task->status) }}

    **Termin:** {{ \Carbon\Carbon::parse($task->due_date)->format('d.m.Y') }}

    @component('mail::button', ['url' => $taskUrl])
        Przejdź do zadania
    @endcomponent

    Dzięki,
    {{ config('app.name') }}
@endcomponent




