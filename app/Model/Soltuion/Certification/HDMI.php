<?php

namespace Backend\Model\Solution\Certification;

use Backend\Model\Solution\Enums\CertificationEnum;

class HDMI extends BaseCertification
{
    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return CertificationEnum::HDMI;
    }

    /**
     * {@inheritDoc}
     */
    public function markImageName()
    {
        return 'HDMI.png';
    }
}
