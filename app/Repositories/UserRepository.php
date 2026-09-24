<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    public function create(array $data)
    {
        return User::create([
            'firstname' => $data['firstname'],
            'lastname'  => $data['lastname'],
            'email'     => $data['email'],
            'phone'     => $data['phone'] ?? null,
            'address'   => $data['address'] ?? null,
            'latitude'  => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'password'  => Hash::make($data['password']),
        ]);
    }

    /**
     * Find a user by their email.
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }
}