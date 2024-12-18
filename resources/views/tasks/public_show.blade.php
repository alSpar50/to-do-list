<!DOCTYPE html>
<html>
<head>
    <title>Zadanie: {{ $task->name }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            {{ $task->name }}
        </div>
        <div class="card-body">
            <p><strong>Opis:</strong> {{ $task->description }}</p>
            <p><strong>Priorytet:</strong> {{ ucfirst($task->priority) }}</p>
            <p><strong>Status:</strong> {{ ucfirst($task->status) }}</p>
            <p><strong>Termin Wykonania:</strong> {{ $task->due_date }}</p>
        </div>
    </div>
</div>
</body>
</html>

