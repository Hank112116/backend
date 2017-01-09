<?php

namespace Backend\Model\Solution\Certification;

use Backend\Model\Solution\Enums\CertificationEnum;

class ConformiteEuropeenne extends BaseCertification
{
    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return CertificationEnum::CE_MARKING;
    }

    /**
     * {@inheritDoc}
     */
    public function markImageName()
    {
        return 'ce.png';
    }
}
