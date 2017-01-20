<?php

namespace Backend\Model\Solution\Certification;

use Backend\Model\Solution\Enums\CertificationEnum;

class WiFi extends BaseCertification
{
    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return CertificationEnum::WIFI;
    }

    /**
     * {@inheritDoc}
     */
    public function markImageName()
    {
        return 'wifi.png';
    }
}
