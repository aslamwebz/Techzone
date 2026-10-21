<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest')]
class Register extends Component
{
    public $name = '';

    public $email = '';

    public $password = '';

    public $password_confirmation = '';

    protected $rules = [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ];

    public function register()
    {
        $validatedData = $this->validate();

        try {
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'is_admin' => false, // Default to regular user
            ]);

            event(new Registered($user));

            Auth::login($user);

            return redirect()->intended(route('home', absolute: false));
        } catch (\Exception $e) {
            $this->addError('email', 'Registration failed. Please try again.');
            \Log::error('Registration error: '.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
