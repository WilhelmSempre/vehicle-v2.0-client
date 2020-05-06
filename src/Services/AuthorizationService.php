<?php

namespace App\Services;

use Exception;

/**
 * Class AuthorizationService
 * @package App\Services
 */
class AuthorizationService
{
    /**
     * @var false|string
     */
    private $publicKey;

    /**
     * AuthorizationService constructor.
     */
    public function __construct()
    {
        if (isset($_ENV['APP_AUTHORIZATION_PUBLIC_KEY_PATH']) && file_exists($_ENV['APP_AUTHORIZATION_PUBLIC_KEY_PATH'])) {
            $this->publicKey = file_get_contents($_ENV['APP_AUTHORIZATION_PUBLIC_KEY_PATH']);
        }
    }

    /**
     * @param $secret
     * @return array
     * @throws Exception
     */
    public function encrypt($secret): array
    {
        $keypair = sodium_crypto_box_keypair();
        $publicKey = sodium_crypto_box_publickey($keypair);

        $encrypted = sodium_crypto_box_seal($secret, $publicKey);

        if ($encrypted) {
            $secret = base64_encode($encrypted);
            $keypair = base64_encode($keypair);
        } else {
            throw new Exception('Unable to encrypt data. Perhaps it is bigger than the key size?');
        }

        return [
            'secret' => $secret,
            'iv' => $keypair,
        ];
    }
}