<?php

namespace Backend\Model\Solution\Certification;

use Backend\Model\Solution\Enums\CertificationEnum;

class RestrictionOfHazardousSubstances extends BaseCertification
{
    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return CertificationEnum::ROHS_COMPLIANT;
    }

    /**
     * {@inheritDoc}
     */
    public function markImageName()
    {
        return 'rohs.png';
    }
}
