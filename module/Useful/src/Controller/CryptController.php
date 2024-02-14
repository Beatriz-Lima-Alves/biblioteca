<?php

namespace Useful\Controller;

use Laminas\Crypt\BlockCipher;

class CryptController
{

    const IDENTITY_KEY = "8350e5a3e24c153df";

    /**
     * Realiza a crypt de acordo com a chave
     *
     * @param String $decrypted
     * @param boolean $base64
     * @return string
     */
    public static function encrypt($decrypted, $base64 = false)
    {
        //$cipher = BlockCipher::factory('mcrypt', array('algorithm' => 'aes'));
        $cipher = BlockCipher::factory(
            'openssl',
            [
                'algo' => 'aes',
                'mode' => 'gcm'
            ]
        );
        $cipher->setKey(self::IDENTITY_KEY);
        $encrypted = $cipher->encrypt($decrypted);
        // Base 64
        if ($base64) {
            $encrypted = base64_encode($encrypted);
        }
        return $encrypted;
    }

    /**
     * String em base64
     * @param $decrypted
     * @param bool $isJson
     * @return string
     */
    public static function base64Encode($decrypted, bool $isJson = false): string
    {
        if ($isJson) {
            $decrypted = (\Laminas\Json\Encoder::encode($decrypted, \Laminas\Json\Json::TYPE_ARRAY));
        }
        return base64_encode($decrypted);
    }

    /**
     * Realiza a tentativa de descypt
     *
     * @param String $encrypted
     * @param boolean $base64
     * @return Ambigous <string, boolean>
     */
    public static function decrypt($encrypted, $base64 = false)
    {
        // Base 64
        if ($base64) {
            $encrypted = base64_decode($encrypted);
        }

        // Funct
        //$cipher = BlockCipher::factory('mcrypt', array('algorithm' => 'aes'));
        $cipher = BlockCipher::factory(
            'openssl',
            [
                'algo' => 'aes',
                'mode' => 'gcm'
            ]
        );
        $cipher->setKey(self::IDENTITY_KEY);
        $decrypted = $cipher->decrypt($encrypted);
        return $decrypted;
    }

    /**
     * Encrypt
     * @param $data
     * @return string
     */
    public static function encryptCryptKey($data)
    {
        return self::encrypt(\Laminas\Json\Json::encode($data), true);
    }

    /**
     * Conceder o token de sessao criptografado
     *
     * @param $uid
     * @param $uuid
     * @param $cid
     * @param $role
     * @param string $local
     * @param int $version
     * @return string
     */
    public static function grantCryptToken($uid, $uuid, $cid, $role, $local = 'en_US', $version = 1)
    {
        $data['uniqid'] = uniqid() . '-' . $uuid;
        $data['expire'] = time() + (60 * 60 * 8760);
        $data['uid'] = $uid;
        $data['cid'] = $cid;
        $data['role'] = $role;
        $data['local'] = $local;
        $data['version'] = $version;
        return self::encrypt(\Laminas\Json\Json::encode($data), true);
    }

    /**
     * Retorna os atributos de um objeto ja decodificado
     *
     * @param String $decrypted
     * @return Array
     * @throws \Exception
     */
    public static function getPrivilegesToken($decrypted)
    {
        try {
            $data = \Laminas\Json\Json::decode($decrypted, \Laminas\Json\Json::TYPE_ARRAY);
        } catch (\Exception $e) {
            throw new \Exception('Decoding failing.');
        }
        if (is_array($data)) {
            return $data;
        } else {
            throw new \Exception('Failed in checking privileges');
        }
    }

    /**
     * Gera um codigo seguro
     *
     * @param $PIN
     * @param $first_part
     * @param $second_part
     * @return string
     */
    public static function createSecureCode($PIN, $first_part = '', $second_part)
    {
        // Criando
        // SOMA DOS DIGITOS DO PIN
        $A = array_sum(str_split($PIN));
        // SOMA DOS ULTIMOS 4 DIGITOS DO MSISDN
        $L = substr($second_part, -4);
        $B = array_sum(str_split($L));
        // SE PIN FOR PAR, USAR OS ULTIMOS 4 DIGITOS SEQUENCIAL (9916)
        // SE PIN FOR IMPAR, USAR OS ULTIMOS 4 DIGITOS INVERTIDO (6199)
        $C = ($PIN % 2 == 0) ? $L : strrev($L);
        // NOME PROJECTO INVERTIDO
        $D = 'fabulamur';
        // Resultado
        $sha1 = $A . $B . $C . $D;
        // Crypt
        $crypt = sha1($sha1);
        return $crypt;
    }

    /**
     * Gera um JWT Token
     * @param string $iss
     * @param array $claim
     * @param string $pubkey , chave publica para assinar o pacote
     * @param int $seconds
     * @return \Lcobucci\JWT\Token , token criado
     */
    public static function generateJwt(string $iss, array $claim, string $pubkey, int $seconds = 604800): \Lcobucci\JWT\Token
    {
        /**
         * https://jwt.io/
         * 'id' => time(), //  the internal id of the token
         * 'jti' => time(), //a unique token identifier for the token (JWT ID)
         * 'iss' => 1, //the id of the server who issued the token (Issuer)
         * 'aud' => N, // the id of the client who requested the token (Audience)
         * 'sub' => N, // the id of the user for which the token was released (Subject)
         * 'exp' => time(),// UNIX timestamp when the token expires (Expiration)
         * 'iat' => time(), //UNIX timestamp when the token was created (Issued At)
         * 'token_type' => 'bearer', // the kind of token, will be bearer
         * 'scopes' => [] //space-separated list of scopes for which the token is issued
         **/

        $signer = new \Lcobucci\JWT\Signer\Hmac\Sha256();
        $key = new \Lcobucci\JWT\Signer\Key($pubkey);

        $builder = new \Lcobucci\JWT\Builder();
        $expiration = time() + $seconds;
        $token = $builder->identifiedBy(time())
            ->withHeader('typ', 'JWT')
            ->withHeader('alg', 'HS256')
            // ->permittedFor($aud)
            ->issuedBy($iss)
            ->relatedTo(time())
            ->expiresAt($expiration)
            ->withClaim('data', $claim)
            ->getToken($signer, $key);

        return $token;
    }

    /**
     * Retorna uma string aleatoria
     * @param int $length
     * @return string
     */
    public static function getRandomString(int $length): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    /**
     * @return string
     */
    public static function getRandomRealmId(): string
    {
        $a = self::getRandomString(9);
        $b = self::getRandomString(4);
        $c = self::getRandomString(4);
        $d = self::getRandomString(4);
        $e = self::getRandomString(12);
        return "{$a}-{$b}-{$c}-{$d}-{$e}";
    }
}