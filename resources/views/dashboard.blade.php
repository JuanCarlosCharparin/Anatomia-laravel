<x-app-layout>
    @section('title', 'Anatomia-Patologica')
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div class="container mt-4">
        <h1>Dashboard</h1>
        <p>Welcome to the dashboard!</p>
    </div>
</x-app-layout>