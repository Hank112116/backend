<?php

namespace Backend\Model\Solution\Certification;

use Backend\Model\Solution\Enums\CertificationEnum;

class NationalCommunicationsCommission extends BaseCertification
{
    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return CertificationEnum::NCC_CERTIFIED;
    }

    /**
     * {@inheritDoc}
     */
    public function markImageName()
    {
        return 'NCC.png';
    }
}
