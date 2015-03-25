<?php

class FrontLinkGenerator
{
    public static function project($project_id)
    {
        $td = mcrypt_module_open(MCRYPT_3DES, "", MCRYPT_MODE_ECB, "");

        $secret_key = Config::get('front.project_secret_key');
        $iv = '12345678';   // Wiil be ignored in ECB mode

        mcrypt_generic_init($td, $secret_key, $iv);

        $encoded_project_id = urlencode(base64_encode(mcrypt_generic($td, $project_id)));

        // Release resource
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return "//".Config::get('app.front_domain'). "/project/{$encoded_project_id}";
    }

    public static function prodcut($project_id, $is_ongoing = false)
    {
        if ($is_ongoing) {
            return "//".Config::get('app.front_domain'). "/product/{$project_id}";
        }

        $encoded = CodeigniterEncrypter::encode($project_id);
        return "//".Config::get('app.front_domain'). "/product_preview/{$encoded}";
    }
}
