<?php

namespace Backend\Enums;

class TechTag
{
    // Certification
    const CERTIFICATION_CCC    = TechCategory::CERTIFICATION . ':ccc';
    const CERTIFICATION_CE     = TechCategory::CERTIFICATION . ':ce';
    const CERTIFICATION_FCC    = TechCategory::CERTIFICATION . ':fcc';
    const CERTIFICATION_FDA    = TechCategory::CERTIFICATION . ':fda';
    const CERTIFICATION_GREEN  = TechCategory::CERTIFICATION . ':green';
    const CERTIFICATION_SAFETY = TechCategory::CERTIFICATION . ':safety';
    const CERTIFICATION_UL     = TechCategory::CERTIFICATION . ':ul';
    const CERTIFICATION_TBD    = TechCategory::CERTIFICATION . ':tbd';

    // Mechanical
    const MECHANICAL_COMPOSITE_MATERIALS = TechCategory::MECHANICAL . ':composite-materials';
    const MECHANICAL_INDUSTRIAL_DESIGN   = TechCategory::MECHANICAL . ':industrial-design';
    const MECHANICAL_MANUFACTURING       = TechCategory::MECHANICAL . ':manufacturing';
    const MECHANICAL_METAL_MATERIALS     = TechCategory::MECHANICAL . ':metal-materials';
    const MECHANICAL_MOLDING_TOOLING     = TechCategory::MECHANICAL . ':molding-tooling';
    const MECHANICAL_PLASTIC_MATERIALS   = TechCategory::MECHANICAL . ':plastic-materials';
    const MECHANICAL_RUBBER_MATERIALS    = TechCategory::MECHANICAL . ':rubber-materials';
    const MECHANICAL_THERMAL             = TechCategory::MECHANICAL . ':thermal';
    const MECHANICAL_TBD                 = TechCategory::MECHANICAL . ':tbd';

    // Electronic
    const ELECTRONIC_DESIGN_HOUSE        = TechCategory::ELECTRONIC . ':design-house';
    const ELECTRONIC_EE_FAILURE_ANALYSIS = TechCategory::ELECTRONIC . ':ee-failure-analysis';
    const ELECTRONIC_IC_DESIGN           = TechCategory::ELECTRONIC . ':ic-design';
    const ELECTRONIC_MODULE_DESIGN       = TechCategory::ELECTRONIC . ':module-design';
    const ELECTRONIC_PCBA_LAYOUT_DESIGN  = TechCategory::ELECTRONIC . ':pcba-layout-design';
    const ELECTRONIC_SMT_MANUFACTURING   = TechCategory::ELECTRONIC . ':smt-manufacturing';
    const ELECTRONIC_TBD                 = TechCategory::ELECTRONIC . ':tbd';

    // Sensor
    const SENSOR_ACCELEROMETER  = TechCategory::SENSOR . ':accelerometer';
    const SENSOR_BLOOD_PRESSURE = TechCategory::SENSOR . ':blood-pressure';
    const SENSOR_CARBON_DIOXIDE = TechCategory::SENSOR . ':carbon-dioxide';
    const SENSOR_COLORIMETER    = TechCategory::SENSOR . ':colorimeter';
    const SENSOR_EMG_ECG        = TechCategory::SENSOR . ':emg-ecg';
    const SENSOR_GLUCOSE        = TechCategory::SENSOR . ':glucose';
    const SENSOR_GYRO           = TechCategory::SENSOR . ':gyro';
    const SENSOR_HEART_RATE     = TechCategory::SENSOR . ':heart-rate';
    const SENSOR_HUMIDITY       = TechCategory::SENSOR . ':humidity';
    const SENSOR_IMAGE          = TechCategory::SENSOR . ':image';
    const SENSOR_INFRARED       = TechCategory::SENSOR . ':infrared';
    const SENSOR_LIGHT          = TechCategory::SENSOR . ':light';
    const SENSOR_MAGNETIC       = TechCategory::SENSOR . ':magnetic';
    const SENSOR_PARTICLE       = TechCategory::SENSOR . ':particle';
    const SENSOR_PHOTOELECTRIC  = TechCategory::SENSOR . ':photoelectric';
    const SENSOR_PRESSURE       = TechCategory::SENSOR . ':pressure';
    const SENSOR_PROXIMITY      = TechCategory::SENSOR . ':proximity';
    const SENSOR_TEMPERATURE    = TechCategory::SENSOR . ':temperature';
    const SENSOR_TBD            = TechCategory::SENSOR . ':tbd';

    // Connectivity
    const CONNECTIVITY_BLUETOOTH                = TechCategory::CONNECTIVITY . ':bluetooth';
    const CONNECTIVITY_CELLULAR                 = TechCategory::CONNECTIVITY . ':cellular';
    const CONNECTIVITY_DLNA_MIRACAST_CHROMECAST = TechCategory::CONNECTIVITY . ':dlna-miracast-chromecast';
    const CONNECTIVITY_ETHERNET                 = TechCategory::CONNECTIVITY . ':ethernet';
    const CONNECTIVITY_GPS                      = TechCategory::CONNECTIVITY . ':gps';
    const CONNECTIVITY_IR                       = TechCategory::CONNECTIVITY . ':ir';
    const CONNECTIVITY_NFC                      = TechCategory::CONNECTIVITY . ':nfc';
    const CONNECTIVITY_RFID                     = TechCategory::CONNECTIVITY . ':rfid';
    const CONNECTIVITY_WHDI                     = TechCategory::CONNECTIVITY . ':whdi';
    const CONNECTIVITY_WIDI                     = TechCategory::CONNECTIVITY . ':widi';
    const CONNECTIVITY_WIFI                     = TechCategory::CONNECTIVITY . ':wifi';
    const CONNECTIVITY_WIGIG                    = TechCategory::CONNECTIVITY . ':wigig';
    const CONNECTIVITY_WIRELESS_HD              = TechCategory::CONNECTIVITY . ':wireless-hd';
    const CONNECTIVITY_WIRELESS_USB             = TechCategory::CONNECTIVITY . ':wireless-usb';
    const CONNECTIVITY_ZIGBEE                   = TechCategory::CONNECTIVITY . ':zigbee';
    const CONNECTIVITY_Z_WAVE                   = TechCategory::CONNECTIVITY . ':z-wave';
    const CONNECTIVITY_TBD                      = TechCategory::CONNECTIVITY . ':tbd';

    // Audio & Video
    const AUDIO_VIDEO_CAMERA     = TechCategory::AUDIO_VIDEO . ':camera';
    const AUDIO_VIDEO_HEADPHONES = TechCategory::AUDIO_VIDEO . ':headphones';
    const AUDIO_VIDEO_IP_CAMERA  = TechCategory::AUDIO_VIDEO . ':ip-camera';
    const AUDIO_VIDEO_MICROPHONE = TechCategory::AUDIO_VIDEO . ':microphone';
    const AUDIO_VIDEO_SPEAKERS   = TechCategory::AUDIO_VIDEO . ':speakers';
    const AUDIO_VIDEO_TBD        = TechCategory::AUDIO_VIDEO . ':tbd';

    // Display
    const DISPLAY_DLP_LCOS     = TechCategory::DISPLAY . ':dlp-lcos';
    const DISPLAY_E_INK        = TechCategory::DISPLAY . ':e-ink';
    const DISPLAY_FLEXIBLE     = TechCategory::DISPLAY . ':flexible';
    const DISPLAY_LCD          = TechCategory::DISPLAY . ':lcd';
    const DISPLAY_LED          = TechCategory::DISPLAY . ':led';
    const DISPLAY_MICRODISPLAY = TechCategory::DISPLAY . ':microdisplay';
    const DISPLAY_OLED         = TechCategory::DISPLAY . ':oled';
    const DISPLAY_PDP          = TechCategory::DISPLAY . ':pdp';
    const DISPLAY_TBD          = TechCategory::DISPLAY . ':tbd';

    // Hardware interface
    const HW_INTERFACE_AUDIO_JACK   = TechCategory::HW_INTERFACE . ':audio-jack';
    const HW_INTERFACE_DISPLAY_PORT = TechCategory::HW_INTERFACE . ':display-port';
    const HW_INTERFACE_HDMI         = TechCategory::HW_INTERFACE . ':hdmi';
    const HW_INTERFACE_LIGHTNING    = TechCategory::HW_INTERFACE . ':lightning';
    const HW_INTERFACE_MYDP         = TechCategory::HW_INTERFACE . ':mydp';
    const HW_INTERFACE_RS232_RJ45   = TechCategory::HW_INTERFACE . ':rs232-rj45';
    const HW_INTERFACE_THUNDERBOLT  = TechCategory::HW_INTERFACE . ':thunderbolt';
    const HW_INTERFACE_USB          = TechCategory::HW_INTERFACE . ':usb';
    const HW_INTERFACE_TBD          = TechCategory::HW_INTERFACE . ':tbd';


    // Touch
    const TOUCH_CAPACITIVE_TOUCH = TechCategory::TOUCH . ':capacitive-touch';
    const TOUCH_OPTICAL_TOUCH    = TechCategory::TOUCH . ':optical-touch';
    const TOUCH_RESISTIVE_TOUCH  = TechCategory::TOUCH . ':resistive-touch';
    const TOUCH_SONIC_WAVE_TOUCH = TechCategory::TOUCH . ':sonic-wave-touch';
    const TOUCH_TBD              = TechCategory::TOUCH . ':tbd';

    // Power
    const POWER_AC_POWER          = TechCategory::POWER . ':ac-power';
    const POWER_BATTERY           = TechCategory::POWER . ':battery';
    const POWER_DC_POWER          = TechCategory::POWER . ':dc-power';
    const POWER_WIRELESS_CHARGE   = TechCategory::POWER . ':wireless-charge';
    const POWER_SOLAR             = TechCategory::POWER . ':solar';
    const POWER_TBD               = TechCategory::POWER . ':tbd';

    // Processor
    const PROCESSOR_ASIC            = TechCategory::PROCESSOR . ':asic';
    const PROCESSOR_ASIP            = TechCategory::PROCESSOR . ':asip';
    const PROCESSOR_CPU             = TechCategory::PROCESSOR . ':cpu';
    const PROCESSOR_DSP             = TechCategory::PROCESSOR . ':dsp';
    const PROCESSOR_MICROCONTROLLER = TechCategory::PROCESSOR . ':microcontroller';
    const PROCESSOR_MIPS            = TechCategory::PROCESSOR . ':mips';
    const PROCESSOR_MISC            = TechCategory::PROCESSOR . ':misc';
    const PROCESSOR_PPU             = TechCategory::PROCESSOR . ':ppu';
    const PROCESSOR_RISC            = TechCategory::PROCESSOR . ':risc';
    const PROCESSOR_TBD             = TechCategory::PROCESSOR . ':tbd';

    // OS
    const OS_ANDROID = TechCategory::OS . ':android';
    const OS_IOS     = TechCategory::OS . ':ios';
    const OS_LINUX   = TechCategory::OS . ':linux';
    const OS_RTOS    = TechCategory::OS . ':rtos';
    const OS_WINDOWS = TechCategory::OS . ':windows';
    const OS_TBD     = TechCategory::OS . ':tbd';

    const TECH_TAGS = [
        TechCategory::CERTIFICATION => [
            self::CERTIFICATION_CCC,
            self::CERTIFICATION_CE,
            self::CERTIFICATION_FCC,
            self::CERTIFICATION_FDA,
            self::CERTIFICATION_GREEN,
            self::CERTIFICATION_SAFETY,
            self::CERTIFICATION_UL,
            self::CERTIFICATION_TBD
        ],
        TechCategory::MECHANICAL   => [
            self::MECHANICAL_COMPOSITE_MATERIALS,
            self::MECHANICAL_INDUSTRIAL_DESIGN,
            self::MECHANICAL_MANUFACTURING,
            self::MECHANICAL_METAL_MATERIALS,
            self::MECHANICAL_MOLDING_TOOLING,
            self::MECHANICAL_PLASTIC_MATERIALS,
            self::MECHANICAL_RUBBER_MATERIALS,
            self::MECHANICAL_THERMAL,
            self::MECHANICAL_TBD
        ],
        TechCategory::ELECTRONIC   => [
            self::ELECTRONIC_DESIGN_HOUSE,
            self::ELECTRONIC_EE_FAILURE_ANALYSIS,
            self::ELECTRONIC_IC_DESIGN,
            self::ELECTRONIC_MODULE_DESIGN,
            self::ELECTRONIC_PCBA_LAYOUT_DESIGN,
            self::ELECTRONIC_SMT_MANUFACTURING,
            self::ELECTRONIC_TBD
        ],
        TechCategory::SENSOR       => [
            self::SENSOR_ACCELEROMETER,
            self::SENSOR_BLOOD_PRESSURE,
            self::SENSOR_CARBON_DIOXIDE,
            self::SENSOR_COLORIMETER,
            self::SENSOR_EMG_ECG,
            self::SENSOR_GLUCOSE,
            self::SENSOR_GYRO,
            self::SENSOR_HEART_RATE,
            self::SENSOR_HUMIDITY,
            self::SENSOR_IMAGE,
            self::SENSOR_INFRARED,
            self::SENSOR_LIGHT ,
            self::SENSOR_MAGNETIC,
            self::SENSOR_PARTICLE ,
            self::SENSOR_PHOTOELECTRIC,
            self::SENSOR_PRESSURE,
            self::SENSOR_PROXIMITY,
            self::SENSOR_TEMPERATURE,
            self::SENSOR_TBD
        ],
        TechCategory::CONNECTIVITY  => [
            self::CONNECTIVITY_BLUETOOTH,
            self::CONNECTIVITY_CELLULAR,
            self::CONNECTIVITY_DLNA_MIRACAST_CHROMECAST,
            self::CONNECTIVITY_ETHERNET,
            self::CONNECTIVITY_GPS,
            self::CONNECTIVITY_IR,
            self::CONNECTIVITY_NFC,
            self::CONNECTIVITY_RFID,
            self::CONNECTIVITY_WHDI,
            self::CONNECTIVITY_WIDI,
            self::CONNECTIVITY_WIFI,
            self::CONNECTIVITY_WIGIG,
            self::CONNECTIVITY_WIRELESS_HD,
            self::CONNECTIVITY_WIRELESS_USB,
            self::CONNECTIVITY_ZIGBEE,
            self::CONNECTIVITY_Z_WAVE,
            self::CONNECTIVITY_TBD
        ],
        TechCategory::AUDIO_VIDEO   => [
            self::AUDIO_VIDEO_CAMERA,
            self::AUDIO_VIDEO_HEADPHONES,
            self::AUDIO_VIDEO_IP_CAMERA,
            self::AUDIO_VIDEO_MICROPHONE,
            self::AUDIO_VIDEO_SPEAKERS,
            self::AUDIO_VIDEO_TBD
        ],
        TechCategory::DISPLAY       => [
            self::DISPLAY_DLP_LCOS,
            self::DISPLAY_E_INK,
            self::DISPLAY_FLEXIBLE,
            self::DISPLAY_LCD,
            self::DISPLAY_LED,
            self::DISPLAY_MICRODISPLAY,
            self::DISPLAY_OLED,
            self::DISPLAY_PDP,
            self::DISPLAY_TBD
        ],
        TechCategory::HW_INTERFACE  => [
            self::HW_INTERFACE_AUDIO_JACK,
            self::HW_INTERFACE_DISPLAY_PORT,
            self::HW_INTERFACE_HDMI,
            self::HW_INTERFACE_LIGHTNING,
            self::HW_INTERFACE_MYDP,
            self::HW_INTERFACE_RS232_RJ45,
            self::HW_INTERFACE_THUNDERBOLT,
            self::HW_INTERFACE_USB,
            self::HW_INTERFACE_TBD
        ],
        TechCategory::TOUCH         => [
            self::TOUCH_CAPACITIVE_TOUCH,
            self::TOUCH_OPTICAL_TOUCH,
            self::TOUCH_RESISTIVE_TOUCH,
            self::TOUCH_SONIC_WAVE_TOUCH ,
            self::TOUCH_TBD
        ],
        TechCategory::POWER         => [
            self::POWER_AC_POWER,
            self::POWER_BATTERY,
            self::POWER_DC_POWER,
            self::POWER_WIRELESS_CHARGE,
            self::POWER_SOLAR,
            self::POWER_TBD
        ],
        TechCategory::PROCESSOR     => [
            self::PROCESSOR_ASIC,
            self::PROCESSOR_ASIP,
            self::PROCESSOR_CPU,
            self::PROCESSOR_DSP,
            self::PROCESSOR_MICROCONTROLLER,
            self::PROCESSOR_MIPS,
            self::PROCESSOR_MISC,
            self::PROCESSOR_PPU,
            self::PROCESSOR_RISC,
            self::PROCESSOR_TBD
        ],
        TechCategory::OS            => [
            self::OS_ANDROID,
            self::OS_IOS,
            self::OS_LINUX,
            self::OS_RTOS,
            self::OS_WINDOWS,
            self::OS_TBD
        ]
    ];
}
