<?php

namespace Backend\Model\Solution\Certification;

use Backend\Model\Solution\Enums\CertificationEnum;

class ChinaCompulsoryCertificate extends BaseCertification
{
    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return CertificationEnum::CHINA_COMPULSORY_CERTIFICATE;
    }

    /**
     * {@inheritDoc}
     */
    public function markImageName()
    {
        return 'ccc.png';
    }
}
