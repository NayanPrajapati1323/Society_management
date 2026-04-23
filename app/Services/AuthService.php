<?php

namespace App\Services;

use App\Models\User;
use App\Models\Role;
use App\Repositories\SocietyRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthService
{
    protected $societyRepo;

    public function __construct(SocietyRepository $societyRepo)
    {
        $this->societyRepo = $societyRepo;
    }

    public function register(array $data, $document = null)
    {
        $docPath = null;
        if ($document) {
            $docPath = $document->store('user_documents', 'public');
        }

        return User::create([
            'name'          => $data['name'],
            'email'         => $data['email'],
            'phone'         => $data['phone'],
            'society_id'    => $data['society_id'],
            'unit_number'   => $data['unit_number'],
            'document_path' => $docPath,
            'password'      => Hash::make($data['password']),
            'role_id'       => 3, // Default tenant/user
            'is_active'     => true,
            'is_approved'   => false,
        ]);
    }

    public function login(array $credentials, $remember = false)
    {
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if ($user->role_id == 3 && !$user->is_approved) {
                Auth::logout();
                throw new \Exception('Your account is pending approval by the society admin.');
            }

            if (!$user->is_active) {
                Auth::logout();
                throw new \Exception('Your account has been deactivated.');
            }

            if ($user->role_id != 1 && $user->society_id) {
                if (!$user->society || !$user->society->is_active) {
                    Auth::logout();
                    throw new \Exception('Your society is not active. Please contact support.');
                }
            }

            return $user;
        }

        throw new \Exception('These credentials do not match our records.');
    }
}
