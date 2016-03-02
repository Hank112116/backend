<?php

namespace Backend\Enums;

class TechCategory
{
    const AUDIO_VIDEO   = 'audio-video';
    const CERTIFICATION = 'certification';
    const CONNECTIVITY  = 'connectivity';
    const DISPLAY       = 'display';
    const ELECTRONIC    = 'electronic';
    const HW_INTERFACE  = 'interface';
    const MECHANICAL    = 'mechanical';
    const OS            = 'os';
    const POWER         = 'power';
    const PROCESSOR     = 'processor';
    const SENSOR        = 'sensor';
    const TOUCH         = 'touch';

    const TECH_CATEGORIES = [
        self::AUDIO_VIDEO,
        self::CERTIFICATION,
        self::CONNECTIVITY,
        self::DISPLAY,
        self::ELECTRONIC,
        self::HW_INTERFACE,
        self::MECHANICAL,
        self::OS,
        self::POWER,
        self::PROCESSOR,
        self::SENSOR,
        self::TOUCH
    ];
}
