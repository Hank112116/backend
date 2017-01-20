<?php

namespace Backend\Model\Solution\Certification;

use Backend\Model\Solution\Enums\CertificationEnum;

class UnderwritersLaboratories extends BaseCertification
{
    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return CertificationEnum::UNDERWRITERS_LABORATORIES;
    }

    /**
     * {@inheritDoc}
     */
    public function markImageName()
    {
        return 'ul.png';
    }
}
