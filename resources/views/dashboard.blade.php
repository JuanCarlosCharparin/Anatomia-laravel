<x-app-layout>
    @section('title', 'Anatomia-Patologica')
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div class="container mt-4">
        <h1>Sistema de Anatomia Patológica</h1>
        <p>Hola {{ auth()->user()->name }}!</p>
        
    </div>
</x-app-layout>