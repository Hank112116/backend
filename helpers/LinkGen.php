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
        $tail = (request()->all()? '&' : '?') . "csv={$num}";
        return request()->fullUrl() . $tail;
    }

    /**
     * @param  $path
     * @return string
     */
    public static function assets($path)
    {
        if (!in_array(env('APP_ENV'), config('assets.environments'))) {
            return secure_url($path);
        }

        if (!is_file($path)){
            return secure_url($path);
        }

        $check_sum = md5_file($path);
        $path      = $path . '?v=' . $check_sum;
        return secure_url($path);
    }
}
