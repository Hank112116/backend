<?php
/**
 * Url Filter
 * @author Hank
 **/
class UrlFilter
{
    public static function filter($str)
    {
        $str = stripslashes($str);
        return preg_replace(['/[?&*~,#|`\/)"\'(@<>}{\[\].!+=%_$\^;:]/','/[ -]+/'], ['','-'], $str);
    }
}
