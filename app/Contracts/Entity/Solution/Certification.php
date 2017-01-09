<?php

namespace Backend\Contracts\Entity\Solution;

/**
 * Interface Certification
 *
 * @package HWTrek\Contracts\Entity
 */
interface Certification
{
    /**
     * @return string
     */
    public function key();

    /**
     * Get certification file name
     * @return string
     */
    public function markImageName();

    /**
     * Get certification file url
     * @return string
     */
    public function getMarkImageUrl();
}
