@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Udostępnij Zadanie: {{ $task->name }}</div>

                    <div class="card-body">
                        <p>Skopiuj poniższy link, aby udostępnić zadanie:</p>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" value="{{ $shareUrl }}" readonly>
                            <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard()">Kopiuj</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard() {
            const copyText = document.querySelector('.input-group input');
            copyText.select();
            copyText.setSelectionRange(0, 99999); // Dla urządzeń mobilnych

            navigator.clipboard.writeText(copyText.value)
                .then(() => {
                    alert('Link został skopiowany do schowka!');
                })
                .catch(err => {
                    alert('Nie udało się skopiować linku');
                });
        }
    </script>
@endsection
