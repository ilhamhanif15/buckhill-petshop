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
        $user = User::byEmailPassword($email, $password)->isAdmin(false)->first();

        if (!$user) 
        {
            throw ValidationException::withMessages([
                'email' => 'User Email not found.'
            ]);
        }

        if (!Hash::check($password, $user->password))
        {
            throw ValidationException::withMessages([
                'password' => 'Password doesnt match.'
            ]);
        }

        $token = $this->jwtAuth->generateToken($user->uuid);

        return $this->responseSuccess([
            "token" => $token
        ]);
    }
}