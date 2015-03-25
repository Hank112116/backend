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
}
