<?php

namespace Backend\Model\Solution\Certification;

use Backend\Contracts\Entity\Solution\Certification;
use Backend\Model\Solution\Enums\CertificationEnum;

/**
 * Class CertificationFactory
 *
 * @author HankChang <hank.chang@hwtrek.com>
 */
class CertificationFactory
{
    /**
     * @param mixed $key
     * @return Certification
     */
    public static function create($key)
    {
        switch ($key) {
            case CertificationEnum::BLUETOOTH_QUALIFIED:
                return new Bluetooth();
            case CertificationEnum::CHINA_COMPULSORY_CERTIFICATE:
                return new ChinaCompulsoryCertificate();
            case CertificationEnum::CE_MARKING:
                return new ConformiteEuropeenne();
            case CertificationEnum::FCC_CERTIFIED:
                return new FederalCommunicationsCommission();
            case CertificationEnum::FOOD_AND_DRUG_ADMINISTRATION:
                return new FoodAndDrugAdministration();
            case CertificationEnum::HDMI:
                return new HDMI();
            case CertificationEnum::MFI_PROGRAM:
                return new MFiProgram();
            case CertificationEnum::NCC_CERTIFIED:
                return new NationalCommunicationsCommission();
            case CertificationEnum::RECOGNIZED_COMPONENT_MARK:
                return new RecognizedComponentMark();
            case CertificationEnum::ROHS_COMPLIANT:
                return new RestrictionOfHazardousSubstances();
            case CertificationEnum::TUV_SUD:
                return new TuvSud();
            case CertificationEnum::UNDERWRITERS_LABORATORIES:
                return new UnderwritersLaboratories();
            case CertificationEnum::USB2:
                return new USB2();
            case CertificationEnum::USB3:
                return new USB3();
            case CertificationEnum::WIFI:
                return new WiFi();
            default:
                throw new \InvalidArgumentException("'{$id}' is not a valid certification id");
        }
    }
}
