<?php

namespace Backend\Model\Solution\Certification;

use Backend\Model\Solution\Enums\CertificationEnum;

class USB2 extends BaseCertification
{
    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return CertificationEnum::USB2;
    }

    /**
     * {@inheritDoc}
     */
    public function markImageName()
    {
        return 'usb-2.png';
    }
}
