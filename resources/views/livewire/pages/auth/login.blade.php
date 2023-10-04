<?php

use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    #[Rule(['required', 'string', 'email'])]
    public string $email = '';

    #[Rule(['required', 'string'])]
    public string $password = '';

    #[Rule(['boolean'])]
    public bool $remember = false;

    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (! auth()->attempt($this->only(['email', 'password'], $this->remember))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());

        session()->regenerate();

        $this->redirect(
            session('url.intended', RouteServiceProvider::HOME),
            navigate: true
        );
    }

    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}; ?>


<div id="hero-section">
    <div id="hero-section-content-wrapper" class="container">
        <!-- Session Status -->
        {{-- <x-auth-session-status class="mb-4" :status="session('status')" /> --}}

        <form wire:submit="login">
            <h1 class="mb-5 text-center text-primary bold">Login</h1>
            <div class="row d-flex justify-content-center">
                <div class="col-12 col-md-6">
                    <div class="form-floating mb-3">
                        <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" required autofocus autocomplete="username" wire:model="email" placeholder="name@example.com">
                        <label for="floatingInput">{{ __('Email') }}</label>
                    </div>
                    @error('email')
                        <ul>
                            @foreach ($errors->get('email') as $message)
                                <li class="invalid-feedbackd"><span class="text-danger">{{ $message }}</span></li>
                            @endforeach
                        </ul>
                    @enderror

                    <div class="form-floating mb-3">
                        <input wire:model="password" id="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password"  autocomplete="current-password">
                        <label for="floatingPassword">{{ __('Password') }}</label>
                    </div>
                    @error('password')
                        <ul>
                            @foreach ($errors->get('password') as $message)
                                <li class="invalid-feedbackd"><span class="text-danger">{{ $message }}</span></li>
                            @endforeach
                        </ul>
                    @enderror

                    <div class="form-check mb-3">
                        <input class="form-check-input" wire:model="remember" name="remember" id="remember" type="checkbox">
                        <label class="form-check-label" for="remember">
                            {{ __('Remember me') }}
                        </label>
                    </div>

                    <button class="btn btn-primary" >{{ __('Log in') }}</button>

                    @if (Route::has('password.request'))
                        <a class="ms-2" href="{{ route('password.request') }}" wire:navigate>
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
