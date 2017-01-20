<?php

namespace Backend\Model\Solution\Certification;

use Backend\Model\Solution\Enums\CertificationEnum;

class RecognizedComponentMark extends BaseCertification
{
    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return CertificationEnum::RECOGNIZED_COMPONENT_MARK;
    }

    /**
     * {@inheritDoc}
     */
    public function markImageName()
    {
        return 'RU.png';
    }
}
