@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Historia Zadania: {{ $task->name }}</div>

                    <div class="card-body">
                        @if($activities->isEmpty())
                            <p>Brak historii zmian dla tego zadania.</p>
                        @else
                            <ul class="list-group">
                                @foreach($activities as $activity)
                                    <li class="list-group-item">
                                        <strong>{{ ucfirst($activity->description) }}:</strong>
                                        @foreach($activity->properties['attributes'] as $key => $value)
                                            <br><strong>{{ ucfirst($key) }}:</strong> {{ $value }}
                                        @endforeach
                                        <br><small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
