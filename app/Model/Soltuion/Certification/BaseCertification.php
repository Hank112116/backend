<?php

namespace Backend\Model\Solution\Certification;

use Backend\Contracts\Entity\Solution\Certification;

/**
 * Class BaseCertification
 *
 * @author HankChang <hank.chang@hwtrek.com>
 */
abstract class BaseCertification implements Certification
{

    /**
     * {@inheritdoc}
     */
    public function getMarkImageUrl()
    {
        $prefix  = '/images/certifications/';
        $img_url = $prefix . $this->markImageName();

        return $img_url;
    }
}
