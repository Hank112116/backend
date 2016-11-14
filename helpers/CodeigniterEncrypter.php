<?php

class CodeigniterEncrypter
{
    private $_hash_type    = 'sha1';

    // --------------------------------------------------------------------

    /**
     * Fetch the encryption key
     *
     * Returns it as MD5 in order to have an exact-length 128 bit key.
     * Mcrypt is sensitive to keys that are not the correct length
     *
     * @access	public
     * @param	string
     * @return	string
     */
    public function get_key()
    {
        return md5(config('front.product_secret_key'));
    }

    // --------------------------------------------------------------------

    /**
     * Encode
     *
     * Encodes the message string using bitwise XOR encoding.
     * The key is combined with a random hash, and then it
     * too gets converted using XOR. The whole thing is then run
     * through mcrypt (if supported) using the randomized key.
     * The end result is a double-encrypted message string
     * that is randomized with each call to this function,
     * even if the supplied message and key are the same.
     *
     * @access	public
     * @param	string
     * @param	string
     * @return	string
     */
    public static function encode($string)
    {
        $en =  new CodeigniterEncrypter();

        $key = $en->get_key();
        $enc = $en->_xor_encode($string, $key);

        $enc = base64_encode($enc);
        $enc = strtr($enc, ['+' => '.', '=' => '-', '/' => '~']);

        return $enc;
    }

    // --------------------------------------------------------------------

    /**
     * XOR Encode
     *
     * Takes a plain-text string and key as input and generates an
     * encoded bit-string using XOR
     *
     * @access	private
     * @param	string
     * @param	string
     * @return	string
     */
    private function _xor_encode($string, $key)
    {
        $PREVIEW_RANDOWN = 'bf93894bf48adcc162b6be68086c5675dfc26470';
        $rand = $PREVIEW_RANDOWN;

        $enc = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $enc .= substr($rand, ($i % strlen($rand)), 1).(substr($rand, ($i % strlen($rand)), 1) ^ substr($string, $i, 1));
        }

        return $this->_xor_merge($enc, $key);
    }

    // --------------------------------------------------------------------


    /**
     * XOR key + string Combiner
     *
     * Takes a string and key as input and computes the difference using XOR
     *
     * @access	private
     * @param	string
     * @param	string
     * @return	string
     */
    private function _xor_merge($string, $key)
    {
        $hash = $this->hash($key);
        $str = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $str .= substr($string, $i, 1) ^ substr($hash, ($i % strlen($hash)), 1);
        }

        return $str;
    }


    /**
     * Hash encode a string
     *
     * @access	public
     * @param	string
     * @return	string
     */
    public function hash($str)
    {
        return ($this->_hash_type == 'sha1') ? $this->sha1($str) : md5($str);
    }


    /**
     * Generate an SHA1 Hash
     *
     * @access	public
     * @param	string
     * @return	string
     */
    public function sha1($str)
    {
        return sha1($str);
    }
}
