<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Admin component for managing users.
 *
 * Allows administrators to create new users and send them an invitation email
 * with a password reset link, so they can set their own password.
 */
class UsersIndex extends Component
{

    public string $name = '';
    public string $email = '';
    public string $role = 'user';

    /**
     * Create a new user with a random password and send an invitation email.
     *
     * The user receives a password reset link via email to define their own password.
     * After successful creation, the form fields are reset.
     *
     * @return void
     */
    public function createUser(): void
    {
        $this->validate([
            'name'     => 'required|string|min:3',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|in:admin,user',
        ]);

        $user = User::create([
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => Hash::make(Str::random(32)),
            'role'     => $this->role,
            'status'   => 'offline',
        ]);

        // Send password reset link so the user can define their own password
        $status = Password::sendResetLink(['email' => $user->email]);

        if ($status !== Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));
        } else {
            session()->flash('status', 'Utilizador criado e email de convite enviado com sucesso!');
        }

        $this->reset(['name', 'email', 'role']);
    }

    /**
     * Render the admin users management view with the list of all users.
     *
     * @return View
     */
    public function render(): View
    {
        return view('livewire.admin.users-index', [
            'users' => User::orderBy('name')->get(),
        ]);
    }
}
