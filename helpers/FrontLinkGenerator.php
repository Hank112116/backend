<?php

class FrontLinkGenerator
{
    public static function project($uuid)
    {
        return "//".Config::get('app.front_domain'). "/project/{$uuid}";
    }
}
