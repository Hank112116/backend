<?php

class FrontLinkGenerator
{
    public static function project($uuid)
    {
        return "//".config('app.front_domain'). "/project/{$uuid}";
    }
}
