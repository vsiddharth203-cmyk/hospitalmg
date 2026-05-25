<x-guest-layout>
    <form method="POST" action="{{ route('login') }}">
        @csrf

        @if ($errors->any())
            <div class="alert alert-danger mb-3">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if (session('status'))
            <div class="alert alert-success mb-3">{{ session('status') }}</div>
        @endif

        <div class="mb-3">
            <label class="form-label fw-500">Email Address</label>
            <input type="email" name="email" class="form-control"
                value="{{ old('email', 'admin@hospital.com') }}" required autofocus>
        </div>

        <div class="mb-3">
            <label class="form-label fw-500">Password</label>
            <input type="password" name="password" class="form-control"
                placeholder="password" required>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="remember" class="form-check-input" id="remember">
            <label class="form-check-label" for="remember">Remember me</label>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary py-2">
                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
            </button>
        </div>

        @if (Route::has('register'))
        <div class="text-center mt-3">
            <small class="text-muted">Don't have an account?
                <a href="{{ route('register') }}">Register</a>
            </small>
        </div>
        @endif
    </form>
</x-guest-layout>