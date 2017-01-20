<?php

namespace Backend\Model\Solution\Certification;

use Backend\Model\Solution\Enums\CertificationEnum;

class FoodAndDrugAdministration extends BaseCertification
{
    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return CertificationEnum::FOOD_AND_DRUG_ADMINISTRATION;
    }

    /**
     * {@inheritDoc}
     */
    public function markImageName()
    {
        return 'fda-logo.png';
    }
}
