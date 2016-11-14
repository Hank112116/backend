<?php

class Noty
{
    /**
     * @param string $msg
     * @param string $type information | success | warning | danger
     **/
    private static function sessionFlash($msg = '', $type = 'information')
    {
        if (!$msg) {
            return;
        }

        Session::flash('noty', ['msg' => $msg, 'type' => $type]);
    }

    /**
     * @param string $lang
     * @param string $type information | success | warning | danger
     **/
    private static function sessionFlashLang($lang = '', $type = 'information')
    {
        $msg = trans($lang);

        if (!$lang or !$msg) {
            return;
        }

        Session::flash('noty', ['msg' => $msg, 'type' => $type]);
    }

    public static function info($msg)
    {
        static::sessionFlash($msg, 'information');
    }
    public static function warn($msg)
    {
        static::sessionFlash($msg, 'warning');
    }
    public static function success($msg)
    {
        static::sessionFlash($msg, 'success');
    }
    public static function danger($msg)
    {
        static::sessionFlash($msg, 'danger');
    }

    public static function infoLang($lang)
    {
        static::sessionFlashLang($lang, 'information');
    }
    public static function warnLang($lang)
    {
        static::sessionFlashLang($lang, 'warning');
    }
    public static function successLang($lang)
    {
        static::sessionFlashLang($lang, 'success');
    }
    public static function dangerLang($lang)
    {
        static::sessionFlashLang($lang, 'danger');
    }
}
