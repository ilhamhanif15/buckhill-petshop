<?php 

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Traits\ResponseBuilder;

class AuthUserService
{
    use ResponseBuilder;

    public function __construct(
        private User $user,
        private JwtAuth $jwtAuth
    )
    {
        //
    }

    public function login($email, $password)
    {
        $admin = User::where([
            ['email', $email],
            ['is_admin', false]
        ])
        ->first();

        if (!$admin) 
        {
            throw ValidationException::withMessages([
                'email' => 'User Email not found.'
            ]);
        }

        if (!Hash::check($password, $admin->password))
        {
            throw ValidationException::withMessages([
                'password' => 'Password doesnt match.'
            ]);
        }

        $token = $this->jwtAuth->generateToken($admin->uuid);

        return $this->responseSuccess([
            "token" => $token
        ]);
    }
}