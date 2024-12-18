@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{ $task->name }}
                        <div class="float-end">
                            <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-warning btn-sm">Edytuj</a>
                            <a href="{{ route('tasks.share', $task->id) }}" class="btn btn-secondary btn-sm">Udostępnij</a>
                            <a href="{{ route('tasks.history', $task->id) }}" class="btn btn-info btn-sm">Historia</a>
                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Czy na pewno chcesz usunąć to zadanie?')">Usuń</button>
                            </form>
                        </div>
                    </div>

                    <div class="card-body">
                        <p><strong>Opis:</strong> {{ $task->description }}</p>
                        <p><strong>Priorytet:</strong> {{ ucfirst($task->priority) }}</p>
                        <p><strong>Status:</strong> {{ ucfirst($task->status) }}</p>
                        <p><strong>Termin Wykonania:</strong> {{ $task->due_date }}</p>

                        <!-- Dodaj przycisk do Google Calendar -->
                        <form action="{{ route('tasks.addToGoogleCalendar', $task->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Dodaj do Google Calendar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


