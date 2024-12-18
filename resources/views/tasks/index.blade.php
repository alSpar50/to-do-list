@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Moje Zadania</span>
                        <a href="{{ route('tasks.create') }}" class="btn btn-primary btn-sm">Dodaj Zadanie</a>
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- Formularz Filtrowania -->
                        <form method="GET" action="{{ route('tasks.index') }}" class="mb-4">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="priority">Priorytet</label>
                                    <select name="priority" id="priority" class="form-control">
                                        <option value="">Wszystkie</option>
                                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Niski</option>
                                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Średni</option>
                                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>Wysoki</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">Wszystkie</option>
                                        <option value="to-do" {{ request('status') == 'to-do' ? 'selected' : '' }}>Do zrobienia</option>
                                        <option value="in progress" {{ request('status') == 'in progress' ? 'selected' : '' }}>W trakcie</option>
                                        <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Zrobione</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="due_date">Termin Wykonania</label>
                                    <input type="date" name="due_date" id="due_date" class="form-control" value="{{ request('due_date') }}">
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary mr-2">Filtruj</button>
                                    <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Resetuj</a>
                                </div>
                            </div>
                        </form>

                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Nazwa</th>
                                <th>Opis</th>
                                <th>Priorytet</th>
                                <th>Status</th>
                                <th>Termin</th>
                                <th>Akcje</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($tasks as $task)
                                <tr>
                                    <td>{{ $task->name }}</td>
                                    <td>{{ $task->description }}</td>
                                    <td>{{ ucfirst($task->priority) }}</td>
                                    <td>{{ ucfirst($task->status) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') }}</td>
                                    <td>
                                        <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-info btn-sm">Pokaż</a>
                                        <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-warning btn-sm">Edytuj</a>
                                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Czy na pewno chcesz usunąć to zadanie?')">Usuń</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Brak zadań do wyświetlenia.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                        <!-- Paginacja -->
                        <div class="d-flex justify-content-center">
                            {{ $tasks->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

