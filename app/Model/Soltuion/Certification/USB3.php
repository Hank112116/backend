<?php

namespace Backend\Model\Solution\Certification;

use Backend\Model\Solution\Enums\CertificationEnum;

class USB3 extends BaseCertification
{
    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return CertificationEnum::USB3;
    }

    /**
     * {@inheritDoc}
     */
    public function markImageName()
    {
        return 'usb-3.png';
    }
}
