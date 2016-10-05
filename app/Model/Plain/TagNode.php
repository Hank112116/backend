<?php namespace Backend\Model\Plain;

use Backend\Enums\TechTag;
use Backend\Model\Eloquent\Tag as OtherTag;

class TagNode
{
    public $key;
    public $name;

    private static $tags = [
        // Certification
        TechTag::CERTIFICATION_CCC                     => 'CCC',
        TechTag::CERTIFICATION_CE                      => 'CE',
        TechTag::CERTIFICATION_FCC                     => 'FCC',
        TechTag::CERTIFICATION_FDA                     => 'FDA',
        TechTag::CERTIFICATION_GREEN                   => 'Green',
        TechTag::CERTIFICATION_SAFETY                  => 'Safety',
        TechTag::CERTIFICATION_UL                      => 'UL',
        TechTag::CERTIFICATION_TBD                     => 'TBD',

        // Mechanical
        TechTag::MECHANICAL_COMPOSITE_MATERIALS        => 'Composite Materials',
        TechTag::MECHANICAL_INDUSTRIAL_DESIGN          => 'Industrial Design',
        TechTag::MECHANICAL_MANUFACTURING              => 'Manufacturing',
        TechTag::MECHANICAL_METAL_MATERIALS            => 'Metal Materials',
        TechTag::MECHANICAL_MOLDING_TOOLING            => 'Molding/Tooling',
        TechTag::MECHANICAL_PLASTIC_MATERIALS          => 'Plastic Materials',
        TechTag::MECHANICAL_RUBBER_MATERIALS           => 'Rubber Materials',
        TechTag::MECHANICAL_THERMAL                    => 'Thermal',
        TechTag::MECHANICAL_TBD                        => 'TBD',

        // Electronic
        TechTag::ELECTRONIC_DESIGN_HOUSE               => 'Design House',
        TechTag::ELECTRONIC_EE_FAILURE_ANALYSIS        => 'EE Failure Analysis',
        TechTag::ELECTRONIC_IC_DESIGN                  => 'IC Design',
        TechTag::ELECTRONIC_MODULE_DESIGN              => 'Module Design',
        TechTag::ELECTRONIC_PCBA_LAYOUT_DESIGN         => 'PCBA Layout Design',
        TechTag::ELECTRONIC_SMT_MANUFACTURING          => 'SMT Manufacturing',
        TechTag::ELECTRONIC_TBD                        => 'TBD',

        // Sensor
        TechTag::SENSOR_ACCELEROMETER                  => 'Accelerometer',
        TechTag::SENSOR_BLOOD_PRESSURE                 => 'Blood Pressure',
        TechTag::SENSOR_CARBON_DIOXIDE                 => 'Carbon Dioxide',
        TechTag::SENSOR_COLORIMETER                    => 'Colorimeter',
        TechTag::SENSOR_EMG_ECG                        => 'EMG/ECG',
        TechTag::SENSOR_GLUCOSE                        => 'Glucose',
        TechTag::SENSOR_GYRO                           => 'Gyro',
        TechTag::SENSOR_HEART_RATE                     => 'Heart Rate',
        TechTag::SENSOR_HUMIDITY                       => 'Humidity',
        TechTag::SENSOR_IMAGE                          => 'Image',
        TechTag::SENSOR_INFRARED                       => 'Infrared',
        TechTag::SENSOR_LIGHT                          => 'Light',
        TechTag::SENSOR_MAGNETIC                       => 'Magnetic',
        TechTag::SENSOR_PARTICLE                       => 'Particle',
        TechTag::SENSOR_PHOTOELECTRIC                  => 'Photoelectric',
        TechTag::SENSOR_PRESSURE                       => 'Pressure',
        TechTag::SENSOR_PROXIMITY                      => 'Proximity',
        TechTag::SENSOR_TEMPERATURE                    => 'Temperature',
        TechTag::SENSOR_TBD                            => 'TBD',

        // Connectivity
        TechTag::CONNECTIVITY_BLUETOOTH                => 'Bluetooth',
        TechTag::CONNECTIVITY_CELLULAR                 => 'Cellular',
        TechTag::CONNECTIVITY_DLNA_MIRACAST_CHROMECAST => 'DLNA/Miracast/Chromecast',
        TechTag::CONNECTIVITY_ETHERNET                 => 'Ethernet',
        TechTag::CONNECTIVITY_GPS                      => 'GPS',
        TechTag::CONNECTIVITY_IR                       => 'IR',
        TechTag::CONNECTIVITY_NFC                      => 'NCF',
        TechTag::CONNECTIVITY_RFID                     => 'RFID',
        TechTag::CONNECTIVITY_WHDI                     => 'WHDI',
        TechTag::CONNECTIVITY_WIDI                     => 'WiDi',
        TechTag::CONNECTIVITY_WIFI                     => 'WiFi',
        TechTag::CONNECTIVITY_WIGIG                    => 'WiGig',
        TechTag::CONNECTIVITY_WIRELESS_HD              => 'Wireless HD',
        TechTag::CONNECTIVITY_WIRELESS_USB             => 'Wireless USB',
        TechTag::CONNECTIVITY_ZIGBEE                   => 'ZigBee',
        TechTag::CONNECTIVITY_Z_WAVE                   => 'Z-Wave',
        TechTag::CONNECTIVITY_TBD                      => 'TBD',

        // Audio & Video
        TechTag::AUDIO_VIDEO_CAMERA                    => 'Camera',
        TechTag::AUDIO_VIDEO_HEADPHONES                => 'Headphones',
        TechTag::AUDIO_VIDEO_IP_CAMERA                 => 'IP Camera',
        TechTag::AUDIO_VIDEO_MICROPHONE                => 'Microphone',
        TechTag::AUDIO_VIDEO_SPEAKERS                  => 'Speakers',
        TechTag::AUDIO_VIDEO_TBD                       => 'TBD',

        // Display
        TechTag::DISPLAY_DLP_LCOS                      => 'DLP/LCos',
        TechTag::DISPLAY_E_INK                         => 'E-Ink',
        TechTag::DISPLAY_FLEXIBLE                      => 'Flexible',
        TechTag::DISPLAY_LCD                           => 'LCD',
        TechTag::DISPLAY_LED                           => 'LED',
        TechTag::DISPLAY_MICRODISPLAY                  => 'Microdisplay',
        TechTag::DISPLAY_OLED                          => 'OLED',
        TechTag::DISPLAY_PDP                           => 'PDP',
        TechTag::DISPLAY_TBD                           => 'TBD',

        // Interface
        TechTag::HW_INTERFACE_AUDIO_JACK               => 'Audio Jack',
        TechTag::HW_INTERFACE_DISPLAY_PORT             => 'Display Port',
        TechTag::HW_INTERFACE_HDMI                     => 'HDMI',
        TechTag::HW_INTERFACE_LIGHTNING                => 'Lightning',
        TechTag::HW_INTERFACE_MYDP                     => 'MyDP',
        TechTag::HW_INTERFACE_RS232_RJ45               => 'RS232/RJ45',
        TechTag::HW_INTERFACE_THUNDERBOLT              => 'Thunderbolt',
        TechTag::HW_INTERFACE_USB                      => 'USB',
        TechTag::HW_INTERFACE_TBD                      => 'TBD',

        // Touch
        TechTag::TOUCH_CAPACITIVE_TOUCH                => 'Capacitive Touch',
        TechTag::TOUCH_OPTICAL_TOUCH                   => 'Optical Touch',
        TechTag::TOUCH_RESISTIVE_TOUCH                 => 'Resistive Touch',
        TechTag::TOUCH_SONIC_WAVE_TOUCH                => 'Sonic Wave Touch',
        TechTag::TOUCH_TBD                             => 'TBD',

        // Power
        TechTag::POWER_AC_POWER                        => 'AC Power',
        TechTag::POWER_BATTERY                         => 'Battery',
        TechTag::POWER_DC_POWER                        => 'DC Power',
        TechTag::POWER_WIRELESS_CHARGE                 => 'Wireless Charge',
        TechTag::POWER_SOLAR                           => 'Solar',
        TechTag::POWER_TBD                             => 'TBD',

        // Processor
        TechTag::PROCESSOR_ASIC                        => 'ASIC',
        TechTag::PROCESSOR_ASIP                        => 'ASIP',
        TechTag::PROCESSOR_CPU                         => 'CPU',
        TechTag::PROCESSOR_DSP                         => 'DSP',
        TechTag::PROCESSOR_MICROCONTROLLER             => 'Microcontroller',
        TechTag::PROCESSOR_MIPS                        => 'MIPS',
        TechTag::PROCESSOR_MISC                        => 'MISC',
        TechTag::PROCESSOR_PPU                         => 'PPU',
        TechTag::PROCESSOR_RISC                        => 'RISC',
        TechTag::PROCESSOR_TBD                         => 'TBD',

        // OS
        TechTag::OS_ANDROID                            => 'Android',
        TechTag::OS_IOS                                => 'iOS',
        TechTag::OS_LINUX                              => 'Linux',
        TechTag::OS_RTOS                               => 'RTOS',
        TechTag::OS_WINDOWS                            => 'Windows',
        TechTag::OS_TBD                                => 'TBD',
    ];

    public function __construct($tag, $all_tags)
    {
        $this->key = $tag;
        $this->name = $this->tags($all_tags)[$tag];

    }

    public static function tags($all_tags = null)
    {
        if ($all_tags == null) {
            $other_tag_model = new OtherTag();
            $other_tags = $other_tag_model->all(['name', 'classified_slug']);
        } else {
            $other_tags = $all_tags;
        }
        $result = [];
        if ($other_tags) {
            foreach ($other_tags as $other_tag) {
                $result[$other_tag->classified_slug] = $other_tag->name;
            }
        }
        $result = array_merge(self::$tags, $result);
        return $result;
    }
}
