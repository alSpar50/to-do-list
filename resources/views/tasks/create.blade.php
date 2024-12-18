@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dodaj Zadanie</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('tasks.store') }}">
                            @csrf

                            <div class="form-group mb-3">
                                <label for="name">Nazwa Zadania<span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required maxlength="255">
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="description">Opis</label>
                                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                                @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="priority">Priorytet</label>
                                <select name="priority" id="priority" class="form-control @error('priority') is-invalid @enderror">
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Niski</option>
                                    <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Średni</option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Wysoki</option>
                                </select>
                                @error('priority')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                    <option value="to-do" {{ old('status') == 'to-do' ? 'selected' : '' }}>Do zrobienia</option>
                                    <option value="in progress" {{ old('status') == 'in progress' ? 'selected' : '' }}>W trakcie</option>
                                    <option value="done" {{ old('status') == 'done' ? 'selected' : '' }}>Zrobione</option>
                                </select>
                                @error('status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="due_date">Termin Wykonania<span class="text-danger">*</span></label>
                                <input type="date" name="due_date" id="due_date" class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date') }}" required>
                                @error('due_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Dodaj Zadanie</button>
                            <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Powrót</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
