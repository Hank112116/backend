<?php

namespace Backend\Model\Solution\Certification;

use Backend\Model\Solution\Enums\CertificationEnum;

class TuvSud extends BaseCertification
{
    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return CertificationEnum::TUV_SUD;
    }

    /**
     * {@inheritDoc}
     */
    public function markImageName()
    {
        return 'tuv.png';
    }
}
