<?php

use Backend\Model\Eloquent\ProjectTag;

class ProjectTagSeeder extends Seeder
{
    public function run()
    {
        Eloquent::unguard();

        $parent_tags = [
            ProjectTag::CONNECTIVITY => 'connectivity',
            ProjectTag::PHYSICAL     => 'physical-interface',
            ProjectTag::WIRELESS     => 'wireless',
            ProjectTag::AUDIO        => 'audio',
            ProjectTag::VIDEO        => 'video',
            ProjectTag::DISPLAY      => 'display',
            ProjectTag::POWER        => 'power',
            ProjectTag::TOUCH        => 'touch',
            ProjectTag::CHIP         => 'chip',
            ProjectTag::OS           => 'os',
            ProjectTag::SENSOR       => 'sensor',
            ProjectTag::ME           => 'me',
            ProjectTag::EE           => 'ee'
        ];

        $tags = [
            // CONNECTIVITY
            'cellular-networks'     => ProjectTag::CONNECTIVITY,
            'zigbee'                => ProjectTag::CONNECTIVITY,
            'zwave'                 => ProjectTag::CONNECTIVITY,
            'bluetooth-23'          => ProjectTag::CONNECTIVITY,
            'bluetooth-4b'          => ProjectTag::CONNECTIVITY,
            'wifi'                  => ProjectTag::CONNECTIVITY,
            'nfc'                   => ProjectTag::CONNECTIVITY,
            'rfid'                  => ProjectTag::CONNECTIVITY,
            'light-communication'   => ProjectTag::CONNECTIVITY,
            'ir'                    => ProjectTag::CONNECTIVITY,

            // PHYSICAL INTERFACE
            'hdmi'                  => ProjectTag::PHYSICAL,
            'usb'                   => ProjectTag::PHYSICAL,
            'lightning'             => ProjectTag::PHYSICAL,
            'displayport'           => ProjectTag::PHYSICAL,
            'mydp'                  => ProjectTag::PHYSICAL,
            'thunderbolt'           => ProjectTag::PHYSICAL,
            'mhl-interface'         => ProjectTag::PHYSICAL,
            'audio-jack'            => ProjectTag::PHYSICAL,
            'rs232-rj45'            => ProjectTag::PHYSICAL,

            // WIRELESS DISPLAY COMMUNICATION
            'dlna'                  => ProjectTag::WIRELESS,
            'widi'                  => ProjectTag::WIRELESS,
            'wigig'                 => ProjectTag::WIRELESS,
            'whdi'                  => ProjectTag::WIRELESS,
            'wireless-usb'          => ProjectTag::WIRELESS,
            'wireless-hd'           => ProjectTag::WIRELESS,

            // AUDIO
            'microphone'            => ProjectTag::AUDIO,
            'speakers'              => ProjectTag::AUDIO,
            'headphones'            => ProjectTag::AUDIO,

            // VIDEO
            'camera'                => ProjectTag::VIDEO,
            'ip-camera'             => ProjectTag::VIDEO,

            // DISPLAY
            'lcd-display'           => ProjectTag::DISPLAY,
            'flexible-display'      => ProjectTag::DISPLAY,
            'oled-display'          => ProjectTag::DISPLAY,
            'dlp-lcos'              => ProjectTag::DISPLAY,
            'eink-display'          => ProjectTag::DISPLAY,
            'plasma-display'        => ProjectTag::DISPLAY,
            'microdisplay'          => ProjectTag::DISPLAY,
            'led-display'           => ProjectTag::DISPLAY,

            // POWER
            'power-management'      => ProjectTag::POWER,
            'ac-power'              => ProjectTag::POWER,
            'dc-power'              => ProjectTag::POWER,
            'solar-power'           => ProjectTag::POWER,
            'battery'               => ProjectTag::POWER,
            'wireless-charging'     => ProjectTag::POWER,

            // TOUCH
            'resistive-touch'       => ProjectTag::TOUCH,
            'capacitive-touch'      => ProjectTag::TOUCH,
            'optical-touch'         => ProjectTag::TOUCH,
            'saw-touch'             => ProjectTag::TOUCH,
            'infrared-grid-touch'   => ProjectTag::TOUCH,
            'dispersive-touch'      => ProjectTag::TOUCH,
            'apr-touch'             => ProjectTag::TOUCH,
            'touch-software'        => ProjectTag::TOUCH,

            // MAIN CHIPSET / PROCESSOR
            'cpu'                   => ProjectTag::CHIP,
            'misc'                  => ProjectTag::CHIP,
            'risc'                  => ProjectTag::CHIP,
            'mips'                  => ProjectTag::CHIP,
            'micro-8051'            => ProjectTag::CHIP,
            'cisc'                  => ProjectTag::CHIP,
            'asic'                  => ProjectTag::CHIP,
            'asip'                  => ProjectTag::CHIP,
            'ppu'                   => ProjectTag::CHIP,
            'dsp'                   => ProjectTag::CHIP,

            // OPERATING SYSTEMS
            'win'                   => ProjectTag::OS,
            'ios'                   => ProjectTag::OS,
            'android'               => ProjectTag::OS,
            'linux'                 => ProjectTag::OS,
            'rtos'                  => ProjectTag::OS,
            'firefox-os'            => ProjectTag::OS,

            // SENSORS
            'accelerometer-sensor'  => ProjectTag::SENSOR,
            'gyro-sensor'           => ProjectTag::SENSOR,
            'magnetometer-sensor'   => ProjectTag::SENSOR,
            'gps-sensor'            => ProjectTag::SENSOR,
            'carbon-dioxide-sensor' => ProjectTag::SENSOR,
            'infrared-point-sensor' => ProjectTag::SENSOR,
            'gas-detector'          => ProjectTag::SENSOR,
            'humidity-sensor'       => ProjectTag::SENSOR,
            'particle-detector'     => ProjectTag::SENSOR,
            'photoelectric-sensor'  => ProjectTag::SENSOR,
            'pressure-sensor'       => ProjectTag::SENSOR,
            'temperature-sensor'    => ProjectTag::SENSOR,
            'proximity-sensor'      => ProjectTag::SENSOR,
            'image-sensor'          => ProjectTag::SENSOR,
            'light-sensor'          => ProjectTag::SENSOR,
            'emg-ecg-sensor'        => ProjectTag::SENSOR,
            'heart-sensor'          => ProjectTag::SENSOR,
            'blood-sensor'          => ProjectTag::SENSOR,
            'glucose-sensor'        => ProjectTag::SENSOR,
            'colorimeter-sensor'    => ProjectTag::SENSOR,

            // MECHANICAL ENGINEERING
            'mechanical-design'     => ProjectTag::ME,
            'molding-tooling'       => ProjectTag::ME,
            'plastic-design'        => ProjectTag::ME,
            'metal-design'          => ProjectTag::ME,
            'rubber-design'         => ProjectTag::ME,
            'non-plastic-design'    => ProjectTag::ME,
            'composite-design'      => ProjectTag::ME,
            'industrial-design'     => ProjectTag::ME,
            'thermal-design'        => ProjectTag::ME,
            'package-design'        => ProjectTag::ME,

            // ELECTRONIC ENGINEERING
            'ic-design'             => ProjectTag::EE,
            'smt-mfg'               => ProjectTag::EE,
            'ee-failure-analysis'   => ProjectTag::EE,
            'pcb-design'            => ProjectTag::EE,
            'ee-idh'                => ProjectTag::EE,
            'ee-layout-design'      => ProjectTag::EE,
            'module-design'         => ProjectTag::EE,
        ];

        ProjectTag::truncate();

        foreach ($parent_tags as $slug) {
            ProjectTag::create([
                'slug' => $slug,
                'parent_id' => '0'
            ]);
        }

        foreach ($tags as $slug => $parent_id) {
            ProjectTag::create([
                'slug' => $slug,
                'parent_id' => $parent_id
            ]);
        }
    }
}
