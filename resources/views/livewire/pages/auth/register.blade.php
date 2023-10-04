<?php

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        auth()->login($user);

        $this->redirect(RouteServiceProvider::HOME, navigate: true);
    }
}; ?>

<div id="hero-section">
    <div id="hero-section-content-wrapper" class="container">
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit="register">
            <h1 class="mb-5 text-center text-primary bold">{{ __('Register') }}</h1>
            <div class="row d-flex justify-content-center">
                <div class="col-12 col-md-6">
                    <div class="form-floating mb-3">
                        <input id="name" class="form-control @error('name') is-invalid @enderror" type="name" name="name" required autofocus autocomplete="username" wire:model="name" placeholder="name@example.com">
                        <label for="floatingInput">{{ __('Name') }}</label>
                    </div>
                    @error('name')
                        <ul>
                            @foreach ($errors->get('name') as $message)
                                <li class="invalid-feedbackd"><span class="text-danger">{{ $message }}</span></li>
                            @endforeach
                        </ul>
                    @enderror

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
                        <input wire:model="password" id="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password"  autocomplete="new-password">
                        <label for="floatingPassword">{{ __('Password') }}</label>
                    </div>
                    @error('password')
                        <ul>
                            @foreach ($errors->get('password') as $message)
                                <li class="invalid-feedbackd"><span class="text-danger">{{ $message }}</span></li>
                            @endforeach
                        </ul>
                    @enderror

                    <div class="form-floating mb-3">
                        <input wire:model="password_confirmation" id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Password_confirmation"  autocomplete="new-password">
                        <label for="floatingPassword_confirmation">{{ __('Confirm Password') }}</label>
                    </div>
                    @error('password_confirmation')
                        <ul>
                            @foreach ($errors->get('password_confirmation') as $message)
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

                    <button class="btn btn-primary" >{{ __('Register') }}</button>

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
