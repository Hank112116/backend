<?php

/**
 * Generate customeize link
 *
 * @auther Jaster
 **/
class LinkGen
{
    /**
     * Gen a link to output the page to csv
     **/
    public static function csv($all = false)
    {
        $num = $all?  'all' : 'this';
        $tail = (Input::all()? '&' : '?') . "csv={$num}";
        return Request::fullUrl() . $tail;
    }

    /**
     * @param  $path
     * @return string
     */
    public static function assets($path)
    {
        if (!in_array(env('APP_ENV'), Config::get('assets.environments'))) {
            return URL::secureAsset($path);
        }

        if (!is_file($path)){
            return URL::secureAsset($path);
        }

        $check_sum = md5_file($path);
        $path      = $path . '?v=' . $check_sum;
        return URL::secureAsset($path);
    }
}
