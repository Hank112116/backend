<?php

namespace Backend\Model\Solution\Certification;

use Backend\Model\Solution\Enums\CertificationEnum;

class MFiProgram extends BaseCertification
{
    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return CertificationEnum::MFI_PROGRAM;
    }

    /**
     * {@inheritDoc}
     */
    public function markImageName()
    {
        return 'MFiLogo.png';
    }
}
