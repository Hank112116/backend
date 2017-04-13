<?php

/**
 * Generate customeize link
 *
 * @auther Jaster
 **/
class LinkGen
{
    /**
     * @param bool $all
     * @param null $limit
     * @return string
     */
    public static function csv($all = false, $limit = null)
    {
        $num = $all?  'all' : 'this';
        $tail = (request()->all()? '&' : '?') . "csv={$num}";

        if (!is_null($limit)) {
            $tail = $tail . '&pp=' . $limit;
        }

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

        $assets_mapping = File::exists(config('assets.mapping'))
            ? File::getRequire(config('assets.mapping')) : [];

        if (array_key_exists($path, $assets_mapping)) {
            $check_sum = $assets_mapping[$path];
        } else {
            $check_sum = md5_file($path);
        }

        $path = $path . '?v=' . $check_sum;

        return secure_url($path);
    }

    /**
     * Generate google map js link
     *
     * @return string
     */
    public static function googleMap()
    {
        return '//maps.googleapis.com/maps/api/js?key=' . config('app.google_map_frontend_key') . '&v=3.exp&libraries=places&language=en';
    }
}
