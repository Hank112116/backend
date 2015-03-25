<?php namespace Backend\Model\Plain;

class SolutionCertification
{
    const CERT_IMAGE_PATH = '/images/certifications/';

    const FCC_CERTIFIED = 1;
    const UNDERWRITERS_LABORATORIES = 2;
    const CONFORMITE_EUROPEENNE = 3;
    const CHINA_COMPULSORY_CERTIFICATE = 4;
    const RECOGNIZED_COMPONENT_MARK = 5;
    const NCC_CERTIFIED = 6;
    const WIFI = 7;
    const BLUETOOTH_QUALIFIED = 8;
    const HDMI = 9;
    const USB = 10;
    const USB30 = 11;
    const FOOD_AND_DRUG_ADMINISTRATION = 12;
    const ROHS_CERTIFIED = 13;
    const MADE_FOR_IPHONE_IPOD_IPAD = 14;
    const TUV_SUD = 15;

    private static $certification_images = [
        self::FCC_CERTIFIED                => 'Fcc.png',
        self::UNDERWRITERS_LABORATORIES    => 'ul.png',
        self::CONFORMITE_EUROPEENNE        => 'ce.png',
        self::CHINA_COMPULSORY_CERTIFICATE => 'ccc.png',
        self::RECOGNIZED_COMPONENT_MARK    => 'RU.png',
        self::NCC_CERTIFIED                => 'NCC.png',
        self::WIFI                         => 'wifi.png',
        self::BLUETOOTH_QUALIFIED          => 'bt.png',
        self::HDMI                         => 'HDMI.png',
        self::USB                          => 'usb-2.png',
        self::USB30                        => 'usb-3.png',
        self::FOOD_AND_DRUG_ADMINISTRATION => 'fda-logo.png',
        self::ROHS_CERTIFIED               => 'rohs.png',
        self::MADE_FOR_IPHONE_IPOD_IPAD    => 'MFiLogo.png',
        self::TUV_SUD                      => 'tuv.png',
    ];

    private $certification_ids;
    private $certification_others;

    public function options()
    {
        $options = [];
        foreach (self::$certification_images as $id => $cert) {
            $options[$id] = self::CERT_IMAGE_PATH.$cert;
        }

        return $options;
    }

    public function parse($certifications, $certification_others)
    {
        $this->certification_ids = [];

        foreach (explode(",", $certifications) as $cert_key) {
            if (array_key_exists($cert_key, self::$certification_images)) {
                $this->certification_ids[] = $cert_key;
            }
        }

        $this->certification_others = trim($certification_others) ?
            explode(",", $certification_others) : [];
    }

    public function logoList()
    {
        $logos = [];

        foreach ($this->certification_ids as $cert_id) {
            $logos[] = self::CERT_IMAGE_PATH.self::$certification_images[ $cert_id ];
        }

        return $logos;
    }

    public function otherList()
    {
        return $this->certification_others;
    }

    public function contains($certification_id)
    {
        return in_array($certification_id, $this->certification_ids);
    }
}
