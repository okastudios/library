<?php
/*
 *     ____  __
 *    / __ \/ /______ _
 *   / / / / //_/ __ `/
 *  / /_/ / ,< / /_/ /
 *  \____/_/|_|\__,_/
 *
 *  @author Ole K. Aanensen <ole@okastudios.com>
 *  @copyright Copyright (c) 2015, okastudios.com
 *
 */

// Package
namespace Oka;

/**
 * Class Cipher
 * @package Oka
 */
class Cipher {

    /**
     * Method for encryption
     */
    const METHOD = 'aes-256-cbc';

    /**
     * Encode with base64 by default
     */
    const BASE64_DEFAULT = true;

    public static $method = '';

    public static $base64ByDefault = '';

    /**
     * Encrypts (but does not authenticate) a message
     *
     * @param string $message - plaintext message
     * @param string $key - encryption key (raw binary expected)
     * @param boolean $encode - set to TRUE to return a base64-encoded
     * @return string (raw binary)
     */
    public static function Encrypt($message, $key, $encode = self::BASE64_DEFAULT)
    {
        $ivSize = openssl_cipher_iv_length(self::METHOD);
        $iv = openssl_random_pseudo_bytes($ivSize);
        $ciphertext = openssl_encrypt(
            $message,
            self::METHOD,
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );

        // Merge IV and text
        $response = $iv . $ciphertext;

        // base64 encode
        if($encode)
            $response = base64_encode($response);

        return $response;
    }

    /**
     * Decrypts (but does not verify) a message
     *
     * @param string $message - ciphertext message
     * @param string $key - encryption key (raw binary expected)
     * @param boolean $encoded - are we expecting an encoded string?
     * @return string
     * @throws \Exception
     */
    public static function Decrypt($message, $key, $encoded = self::BASE64_DEFAULT)
    {

        // base64 decode
        if($encoded) {
            $message = base64_decode($message);
            if($message === false)
                throw new \Exception('Decoding failure');
        }

        $ivSize = openssl_cipher_iv_length(self::METHOD);
        $iv = mb_substr($message, 0, $ivSize, '8bit');
        $ciphertext = mb_substr($message, $ivSize, null, '8bit');

        $plaintext = openssl_decrypt(
            $ciphertext,
            self::METHOD,
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );

        return $plaintext;
    }

}