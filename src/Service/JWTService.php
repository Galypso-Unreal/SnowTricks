<?php

namespace App\Service;

use DateTimeImmutable;

class JWTService{

    /* Generate token (ex : for email validation) */

    /**
     * The function generates a JSON Web Token (JWT) using the provided header, payload, secret, and
     * validity period.
     * 
     * @param array header An array containing the header information for the JWT token. This typically
     * includes the algorithm used for signing the token and the type of token.
     * @param array payload The payload is an array that contains the data that you want to include in
     * the JWT (JSON Web Token). It typically includes information such as the user's ID, username, and
     * any other relevant data that you want to pass along with the token.
     * @param string secret The `secret` parameter is a string that represents the secret key used for
     * signing the token. It is used in the `hash_hmac` function to generate the signature of the
     * token.
     * @param int validity The validity parameter represents the duration of time for which the
     * generated token will be considered valid, in seconds. The default value is 14400 seconds, which
     * is equivalent to 4 hours.
     * 
     * @return string a JSON Web Token (JWT) as a string.
     */
    public function generate(array $header, array $payload, string $secret, int $validity = 14400 ): string{
        if($validity > 0){
            $now = new DateTimeImmutable();
            $exp = $now->getTimestamp() + $validity;
    
            $payload['iat'] = $now->getTimestamp();
            $payload['exp'] = $exp;
        }


        /* Enconding to base64 */

        $base64Header = base64_encode(json_encode($header));
        $base64Payload = base64_encode(json_encode($payload));

        /* Clean datas with encoding values */

        $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], $base64Header);
        $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], $base64Payload);

        /* Generate signature */
        $secret = base64_encode($secret);

        $signature = hash_hmac('sha256', $base64Header . '.' . $base64Payload, $secret, true);

        $base64Signature = base64_encode($signature);

        $base64Signature = str_replace(['+', '/', '='], ['-', '_', ''], $base64Signature);

        /* Create token */
        $jwt = $base64Header . '.' . $base64Payload . '.' . $base64Signature;

        return $jwt;
    }

    /* Checking if token is correct */
    public function isValid(string $token):bool{
        return preg_match(
            '/^[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+$/',
            $token
        ) === 1;
    }

    /* Get Header */
    public function getHeader($token){

        /* Get data Header from token */
        $array = explode('.', $token);

        /* Decode Header */
        $header = json_decode(base64_decode($array[0]),true);

        return $header;
    }

    /* Get payload */
    public function getPayload($token){

        /* Get data payload from token */
        $array = explode('.', $token);

        /* Decode Payload */
        $payload = json_decode(base64_decode($array[1]),true);

        return $payload;
    }

    /* Check if JWT is expired */
    public function isExpired(string $token){
        $payload = $this->getPayload($token);

        $now = new DateTimeImmutable();

        return $payload['exp'] < $now->getTimestamp();
    }

    /* Check JWT signature */
    public function check(string $token, string $secret){

        /* Get Header and payload */
        $header = $this->getHeader($token);
        $payload = $this->getPayload($token);

        /* Regenerate token */
        $VerifToken = $this->generate($header, $payload, $secret, 0);

        return $token === $VerifToken;


    }
}