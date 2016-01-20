<?php

class RSA
{
    public static function encryption($data, $public_key)
    {
        // read the public key
        $public_key         = openssl_pkey_get_public($public_key);
        $public_key_details = openssl_pkey_get_details($public_key);

        // there are 11 bytes overhead for PKCS1 padding
        $encrypt_chunk_size = ceil($public_key_details['bits'] / 8) - 11;
        $output = '';

        // loop through the long plain text, and divide by chunks
        while ($data) {
            $chunk      = substr($data, 0, $encrypt_chunk_size);
            $data = substr($data, $encrypt_chunk_size);
            $encrypted  = '';
            if (!openssl_public_encrypt($chunk, $encrypted, $public_key))
                die('Failed to encrypt data');
            $output .= $encrypted;
        }
        openssl_free_key($public_key);
        return base64_encode($output);
    }

    public static function decryption($encoded_data, $private_key)
    {
        // decode the text to bytes
        $encrypted = base64_decode($encoded_data);

        // read the private key
        $private_key         = openssl_pkey_get_private($private_key);
        $private_key_details = openssl_pkey_get_details($private_key);

        // there is no need to minus the overhead
        $decrypt_chunk_size = ceil($private_key_details['bits'] / 8);
        $output = '';

        // decrypt it back chunk-by-chunk
        while ($encrypted) {
            $chunk     = substr($encrypted, 0, $decrypt_chunk_size);
            $encrypted = substr($encrypted, $decrypt_chunk_size);
            $decrypted = '';
            if (!openssl_private_decrypt($chunk, $decrypted, $private_key))
                die('Failed to decrypt data');
            $output .= $decrypted;
        }
        openssl_free_key($private_key);
        return $output;
    }
}
