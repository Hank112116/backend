<?php

namespace Backend\Model\Solution\Certification;

use Backend\Model\Solution\Enums\CertificationEnum;

class FederalCommunicationsCommission extends BaseCertification
{
    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return CertificationEnum::FCC_CERTIFIED;
    }

    /**
     * {@inheritDoc}
     */
    public function markImageName()
    {
        return 'Fcc.png';
    }
}
