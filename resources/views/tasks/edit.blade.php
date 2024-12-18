@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edytuj Zadanie</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tasks.update', $task->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Nazwa Zadania -->
            <div class="mb-3">
                <label for="name" class="form-label">Nazwa Zadania</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $task->name) }}" required>
            </div>

            <!-- Opis Zadania -->
            <div class="mb-3">
                <label for="description" class="form-label">Opis</label>
                <textarea class="form-control" id="description" name="description">{{ old('description', $task->description) }}</textarea>
            </div>

            <!-- Priorytet -->
            <div class="mb-3">
                <label for="priority" class="form-label">Priorytet</label>
                <select class="form-select" id="priority" name="priority" required>
                    <option value="low" {{ $task->priority === 'low' ? 'selected' : '' }}>Niski</option>
                    <option value="medium" {{ $task->priority === 'medium' ? 'selected' : '' }}>Średni</option>
                    <option value="high" {{ $task->priority === 'high' ? 'selected' : '' }}>Wysoki</option>
                </select>
            </div>

            <!-- Status -->
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="to-do" {{ $task->status === 'to-do' ? 'selected' : '' }}>Do zrobienia</option>
                    <option value="in progress" {{ $task->status === 'in progress' ? 'selected' : '' }}>W trakcie</option>
                    <option value="done" {{ $task->status === 'done' ? 'selected' : '' }}>Zrobione</option>
                </select>
            </div>

            <!-- Termin Wykonania -->
            <div class="mb-3">
                <label for="due_date" class="form-label">Termin Wykonania</label>
                <input type="date" class="form-control" id="due_date" name="due_date" value="{{ old('due_date', $task->due_date->format('Y-m-d')) }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Zaktualizuj Zadanie</button>
        </form>
    </div>
@endsection


