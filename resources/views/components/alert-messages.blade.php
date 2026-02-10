<div class="space-y-4">
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded" role="alert">
                <p class="font-bold">Error de validación</p>
                <p>{{ $error }}</p>
            </div>
        @endforeach
    @endif

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded" role="alert">
            <p class="font-bold">✓ Éxito</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded" role="alert">
            <p class="font-bold">✗ Error</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif
</div>
