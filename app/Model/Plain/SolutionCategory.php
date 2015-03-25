<?php namespace Backend\Model\Plain;

class SolutionCategory
{

    // Main Categories
    const ST_ODM_OEM_EMS = 1;
    const ST_ELECTRONIC = 2;
    const ST_MECHANICAL = 3;
    const ST_IC_DESIGN = 4;
    const ST_MODULES = 5;
    const ST_SOFTWARE = 6;
    const ST_SERVICE = 7;

    // Sub Categories
    const SD_ODM_GENERAL = 1;
    const SD_ODM_MANUFACTURING = 2;
    const SD_ODM_SPECIALIST = 3;
    const SD_ODM_APP = 4;
    const SD_ODM_CLOUD = 5;
    const SD_ODM_MECHANICAL = 6;
    const SD_ODM_SUPLLY_CHAIN = 7;

    const SD_ELEC_SCHEMATICS = 1;
    const SD_ELEC_PCB_LAYOUT = 2;
    const SD_ELEC_PCB_FAB = 3;

    const SD_MECH_IND_DESIGN = 1;
    const SD_MECH_MECH_DESIGN = 2;
    const SD_MECH_METALS = 3;
    const SD_MECH_PLASTICS = 4;
    const SD_MECH_COMPOSITIES = 5;
    const SD_MECH_RUBBERS = 6;

    const SD_ICD_AUGMENTED = 1;
    const SD_ICD_DISPLAYS = 2;
    const SD_ICD_INTERNET = 3;
    const SD_ICD_MULTIMEDIA = 4;
    const SD_ICD_OS = 5;
    const SD_ICD_SMART_WATCH = 6;
    const SD_ICD_CHARGING = 7;
    const SD_ICD_NETWORKS = 8;

    const SD_MOD_CONTROL = 1;
    const SD_MOD_WIRELESS = 2;
    const SD_MOD_ANALOG = 3;
    const SD_MOD_IMAGE = 4;
    const SD_MOD_SENSOR = 5;
    const SD_MOD_POWER_MGMT = 6;

    const SD_SW_FIRMWARE = 1;
    const SD_SW_OS = 2;
    const SD_SW_APP = 3;
    const SD_SW_CLOUD = 4;
    const SD_SW_DRIVERS = 5;

    const SD_SERVICE_MARKETING = 1;
    const SD_SERVICE_INVESTMENT = 2;
    const SD_SERVICE_RETAIL = 3;
    const SD_SERVICE_LOGISTICS = 4;
    const SD_SERVICE_SAFETY = 5;
    const SD_SERVICE_MULTIMEDIA = 6;
    const SD_SERVICE_CONSULTING = 7;

    private static $main_categories = [
        self::ST_ODM_OEM_EMS => 'ODM/OEM/EMS',
        self::ST_ELECTRONIC  => 'Electronic engineering',
        self::ST_MECHANICAL  => 'Mechanical engineering',
        self::ST_IC_DESIGN   => 'IC/Component design',
        self::ST_MODULES     => 'Modules',
        self::ST_SOFTWARE    => 'Software design',
        self::ST_SERVICE     => 'Other services',
    ];

    private static $sub_categories = [
        self::ST_ODM_OEM_EMS => [
            self::SD_ODM_GENERAL       => 'General contract manufacturing',
            self::SD_ODM_MANUFACTURING => 'Manufacturing with design capabilities',
            self::SD_ODM_SPECIALIST    => 'Specialist design services',
            self::SD_ODM_APP           => 'APP development',
            self::SD_ODM_CLOUD         => 'Cloud services',
            self::SD_ODM_MECHANICAL    => 'Mechanical parts/tooling/design',
            self::SD_ODM_SUPLLY_CHAIN  => 'Supply chain services',
        ],

        self::ST_ELECTRONIC  => [
            self::SD_ELEC_SCHEMATICS => 'Schematics',
            self::SD_ELEC_PCB_LAYOUT => 'PCB layout',
            self::SD_ELEC_PCB_FAB    => 'PCB fabrication',
        ],

        self::ST_MECHANICAL  => [
            self::SD_MECH_IND_DESIGN  => 'Industrial design',
            self::SD_MECH_MECH_DESIGN => 'Mechanical design',
            self::SD_MECH_METALS      => 'Metals',
            self::SD_MECH_PLASTICS    => 'Plastics',
            self::SD_MECH_COMPOSITIES => 'Composites',
            self::SD_MECH_RUBBERS     => 'Rubbers',
        ],

        self::ST_IC_DESIGN   => [
            self::SD_ICD_AUGMENTED   => 'Augmented reality',
            self::SD_ICD_DISPLAYS    => 'Displays',
            self::SD_ICD_INTERNET    => 'Internet of Things solutions',
            self::SD_ICD_MULTIMEDIA  => 'Multimedia solutions',
            self::SD_ICD_OS          => 'Operating system solutions',
            self::SD_ICD_SMART_WATCH => 'Smartwatch solutions',
            self::SD_ICD_CHARGING    => 'Wireless charging',
            self::SD_ICD_NETWORKS    => 'Wireless networks',
        ],

        self::ST_MODULES     => [
            self::SD_MOD_CONTROL    => 'Control modules',
            self::SD_MOD_WIRELESS   => 'Wireless modules',
            self::SD_MOD_ANALOG     => 'Analog modules',
            self::SD_MOD_IMAGE      => 'Image modules',
            self::SD_MOD_SENSOR     => 'Sensor modules',
            self::SD_MOD_POWER_MGMT => 'Power management modules',
        ],

        self::ST_SOFTWARE    => [
            self::SD_SW_FIRMWARE => 'Firmware',
            self::SD_SW_OS       => 'Operating systems',
            self::SD_SW_APP      => 'APP development',
            self::SD_SW_CLOUD    => 'Cloud services',
            self::SD_SW_DRIVERS  => 'Drivers',
        ],

        self::ST_SERVICE     => [
            self::SD_SERVICE_MARKETING  => 'Marketing services',
            self::SD_SERVICE_INVESTMENT => 'Investment services',
            self::SD_SERVICE_RETAIL     => 'Retail channels services',
            self::SD_SERVICE_LOGISTICS  => 'Logistics services',
            self::SD_SERVICE_SAFETY     => 'Safety and Certifications services',
            self::SD_SERVICE_MULTIMEDIA => 'Multimedia services',
            self::SD_SERVICE_CONSULTING => 'Consultations services',
        ],
    ];

    /**
     * @return array [main, sub]
     *
     * main    = [main_id, sub_id, text]
     * sub     = self::$sub_categories
     */
    public function options()
    {
        $sub_categories = self::$sub_categories;
        unset($sub_categories[self::ST_ODM_OEM_EMS][self::SD_ODM_SUPLLY_CHAIN]);
        unset($sub_categories[self::ST_SERVICE]);

        return ['main' => $this-> mainOptions(), 'sub' => $sub_categories];
    }

    private function mainOptions()
    {
        $main = [];

        $main_categories = self::$main_categories;
        unset($main_categories[self::ST_SERVICE]);

        foreach ($main_categories as $main_id => $text) {
            $main[] = [
                'main_id' => $main_id,
                'sub_id'  => 0,
                'text'    => $text,
            ];
        }

        $main[] = [
            'main_id' => self::ST_ODM_OEM_EMS,
            'sub_id'  => self::SD_ODM_SUPLLY_CHAIN,
            'text'    => self::$sub_categories[self::ST_ODM_OEM_EMS][self::SD_ODM_SUPLLY_CHAIN],
        ];

        foreach (self::$sub_categories[self::ST_SERVICE] as $sub_id => $text) {
            $main[] = [
                'main_id' => self::ST_SERVICE,
                'sub_id'  => $sub_id,
                'text'    => $text,
            ];
        }

        return $main;
    }

    public function textMainCategory($solution_type)
    {
        if (!array_key_exists($solution_type, self::$main_categories)) {
            return 'N/A';
        }

        return self::$main_categories[$solution_type];
    }

    public function textSubCategory($solution_type, $solution_detail)
    {
        if (!array_key_exists($solution_type, self::$sub_categories)) {
            return 'N/A';
        }

        if (!array_key_exists($solution_detail, self::$sub_categories[$solution_type])) {
            return 'N/A';
        }

        return self::$sub_categories[$solution_type][$solution_detail];
    }
}
