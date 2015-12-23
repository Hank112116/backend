<?php namespace Backend\Model\Plain;

use Backend\Model\Eloquent\ProjectTag;

class ProjectTagNode
{
    public $id;
    public $name;

    private static $tags = [
        // CONNECTIVITY
        'cellular-networks'     => 'Cellular Networks (2G/3G/4G)',
        'zigbee'                => 'ZigBee',
        'zwave'                 => 'Z-Wave',
        'bluetooth-23'          => 'Bluetooth 2.x/3.x',
        'bluetooth-4b'          => 'Bluetooth 4.x/BLE',
        'wifi'                  => 'WiFi',
        'nfc'                   => 'NFC',
        'rfid'                  => 'RFID',
        'light-communication'   => 'Light Communication',
        'ir'                    => 'IR',

        // PHYSICAL INTERFACE
        'hdmi'                  => 'HDMI',
        'usb'                   => 'USB',
        'lightning'             => 'Lightning',
        'displayport'           => 'DisplayPort',
        'mydp'                  => 'MyDP',
        'thunderbolt'           => 'Thunderbolt',
        'mhl-interface'         => 'Mobile High-definition Link (MHL)',
        'audio-jack'            => 'Audio Jack',
        'rs232-rj45'            => 'RS232/RJ45',

        // WIRELESS DISPLAY COMMUNICATION
        'dlna'                  => 'DLNA/Miracast/Chromecast',
        'widi'                  => 'WiDi',
        'wigig'                 => 'WiGig',
        'whdi'                  => 'WHDI',
        'wireless-usb'          => 'Wireless USB',
        'wireless-hd'           => 'Wireless HD',

        // AUDIO
        'microphone'            => 'Microphone',
        'speakers'              => 'Speakers',
        'headphones'            => 'Headphones',

        // VIDEO
        'camera'                => 'Camera',
        'ip-camera'             => 'IP Camera',

        // DISPLAY
        'lcd-display'           => 'LCD Display',
        'flexible-display'      => 'Flexible DISPLAY',
        'oled-display'          => 'OLED Display',
        'dlp-lcos'              => 'DLP/LCoS',
        'eink-display'          => 'Electronic Paper Displays (E-Ink)',
        'plasma-display'        => 'PDP Plasma Display',
        'microdisplay'          => 'Microdisplay',
        'led-display'           => 'LED Display',

        // POWER
        'power-management'      => 'Power Management Solution (PMIC/PMU)',
        'ac-power'              => 'AC Power',
        'dc-power'              => 'DC Power',
        'solar-power'           => 'Solar Power',
        'battery'               => 'Battery',
        'wireless-charging'     => 'Wireless Charging',

        // TOUCH
        'resistive-touch'       => 'Resistive Touch',
        'capacitive-touch'      => 'Capacitive Touch',
        'optical-touch'         => 'Optical Touch',
        'saw-touch'             => 'Surface Acoustic Wave Touch',
        'infrared-grid-touch'   => 'Infrared Grid Touch',
        'dispersive-touch'      => 'Dispersive Signal Touch',
        'apr-touch'             => 'Acoustic Pulse Recognition Touch',
        'touch-software'        => 'Touch - Software Solution',

        // MAIN CHIPSET / PROCESSOR
        'cpu'                   => 'Central Processing Unit (CPU)',
        'misc'                  => 'Minimal Instruction Set Computer (MISC)',
        'risc'                  => 'Reduced Instruction Set Computing (RISC) - ARM',
        'mips'                  => 'Microprocessor without Interlocked Pipeline Stages (MIPS)',
        'micro-8051'            => 'Microcontroller - 8051',
        'cisc'                  => 'Complex Instruction Set Computer (CISC) - X86',
        'asic'                  => 'Application-specific Integrated Circuit (ASIC)',
        'asip'                  => 'Application-specific Instruction-set Processor (ASIP)',
        'ppu'                   => 'Physics Processing Unit (PPU)',
        'dsp'                   => 'Digital Signal Processor (DSP)',

        // OPERATING SYSTEMS
        'win'                   => 'Windows OS',
        'ios'                   => 'iOS',
        'android'               => 'Android OS',
        'linux'                 => 'Linux OS',
        'rtos'                  => 'RTOS',
        'firefox-os'            => 'Firefox OS',

        // SENSORS
        'accelerometer-sensor'  => 'Accelerometer Sensor',
        'gyro-sensor'           => 'Gyroscopes Sensor',
        'magnetometer-sensor'   => 'Magnetometer Sensor',
        'gps-sensor'            => 'GPS/GNASS Sensor',
        'carbon-dioxide-sensor' => 'Carbon Dioxide Sensor',
        'infrared-point-sensor' => 'Infrared Point Sensor',
        'gas-detector'          => 'Gas Detector',
        'humidity-sensor'       => 'Humidity Sensor',
        'particle-detector'     => 'Particle Detector',
        'photoelectric-sensor'  => 'Photoelectric Sensor',
        'pressure-sensor'       => 'Pressure Sensors',
        'temperature-sensor'    => 'Temperature Sensors',
        'proximity-sensor'      => 'Proximity Sensors',
        'image-sensor'          => 'Image Sensors',
        'light-sensor'          => 'Light Sensors',
        'emg-ecg-sensor'        => 'EMG/ECG Sensor',
        'heart-sensor'          => 'Heart Rate Sensor',
        'blood-sensor'          => 'Blood Pressure Sensor',
        'glucose-sensor'        => 'Glucose Sensor',
        'colorimeter-sensor'    => 'Colorimeter Sensor',

        // MECHANICAL ENGINEERING
        'mechanical-design'     => 'Mechanical Design',
        'molding-tooling'       => 'Molding/Tooling',
        'plastic-design'        => 'Plastic Parts Design/Manufacturing',
        'metal-design'          => 'Metal Parts Design/Manufacturing',
        'rubber-design'         => 'Rubber Parts Design/Manufacturing',
        'non-plastic-design'    => 'Non-plastic Parts Design/Manufacturing',
        'composite-design'      => 'Composite Material Parts Design/Manufacturing',
        'industrial-design'     => 'Industrial Design(ID)',
        'thermal-design'        => 'Thermal Management System Design',
        'package-design'        => 'Packaging Design',

        // ELECTRONIC ENGINEERING
        'ic-design'             => 'Integrated Circuit Design',
        'smt-mfg'               => 'SMT Manufacturing',
        'ee-failure-analysis'   => 'EE Failure Analysis',
        'pcb-design'            => 'PCB Design',
        'ee-idh'                => 'EE IDH (Independent Design House)',
        'ee-layout-design'      => 'EE Layout Design',
        'module-design'         => 'Module Design',
    ];

    public function __construct(ProjectTag $tag)
    {
        $this->id = $tag->tag_id;
        $this->name = self::$tags[$tag->slug];
    }

    public static function tags()
    {
        return self::$tags;
    }
}
