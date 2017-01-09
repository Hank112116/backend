<?php

namespace Backend\Model\Solution\Certification;

use Backend\Model\Solution\Enums\CertificationEnum;

class Bluetooth extends BaseCertification
{
    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return CertificationEnum::BLUETOOTH_QUALIFIED;
    }

    /**
     * {@inheritDoc}
     */
    public function markImageName()
    {
        return 'bt.png';
    }
}
