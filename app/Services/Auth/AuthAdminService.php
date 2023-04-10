<?php 

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Traits\ResponseBuilder;

class AuthAdminService
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
        $admin = User::byEmailPassword($email, $password)->isAdmin(true)->first();

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