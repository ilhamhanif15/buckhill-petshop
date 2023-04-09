<?php 

namespace App\Services\Auth;

use DateTimeImmutable;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token\Builder as TokenBuilder;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Validator;

class JwtAuth
{
    private $parsedToken = null;

    /**
     * @param uuid|string $userUuid
     * @return string
     */
    public function generateToken($userUuid)
    {
        $key = InMemory::file('storage/private.key');

        $tokenBuilder   = (new TokenBuilder(new JoseEncoder(), ChainedFormatter::default()));
        $algorithm      = new Sha256();
        $signingKey     = $key;
        $now            = new DateTimeImmutable();

        $token = $tokenBuilder
            ->issuedBy( env('APP_URL') )
            ->withClaim('user_uuid', $userUuid)
            ->expiresAt($now->modify('+10 minutes'))
            ->getToken($algorithm, $signingKey);

        return $token->toString();
    }

    /**
     * @param string $token
     * @return bool
     */
    public function validateToken($token)
    {
        $validator = new Validator();
        $parser = new Parser(new JoseEncoder());
        $this->parsedToken = $parser->parse($token);
        
        $publicKey = InMemory::file('storage/public.key');
        
        $constraint = new SignedWith(new Sha256(), $publicKey);

        if (! $validator->validate($this->parsedToken, $constraint)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param string $token
     * @return array
     */
    public function parseClaimsToken($token)
    {
        $parser         = new Parser(new JoseEncoder());
        $currentToken   = $parser->parse($token);

        return $currentToken->claims()->all();
    }
    
    /**
     * Get latest User UUID after run validateToken
     *
     * @return uuid|string|null
     */
    public function getLatestValidateUserUuid()
    {
        return $this->parsedToken->claims()->all()['user_uuid'] ?? null;
    }
}