<?php

use Backend\Model\Eloquent\HubQuestionnaire;
use Backend\Model\Eloquent\ProjectTag;

class QuestionnaireProjectTagMigrater extends Seeder
{
    private $tags;
    private $map = [];

    public function run()
    {
        $this->tags = ProjectTag::all()->lists('id', 'slug');
        $this-> buildMigrateMap();

        Eloquent::unguard();

        HubQuestionnaire::chunk(50, function ($questionnaires) {
            foreach ($questionnaires as $q) {
                $this->migrate($q);
            }
        });
    }

    private function migrate(HubQuestionnaire $seed)
    {
        foreach ($this->map as $column => $map) {
            if (!$seed->$column) {
                continue;
            }
            $this->migrateColumn($seed, $column, $map);
        }
        $seed->save();
    }

    private function migrateColumn(HubQuestionnaire $seed, $column, $map)
    {
        $new_tags = [];
        foreach (explode(',', $seed->$column) as $tag_id) {
            if (array_key_exists($tag_id, $map)) {
                $new_tags[] = $map[$tag_id];
            } else {
                $new_tags[] = $tag_id;
            }
        }
        $seed->$column = implode(',', array_unique($new_tags));
    }


    private function buildMigrateMap()
    {
        $map[ 'chips' ] = [
            1 => 'cpu',
            2 => 'risc',
            3 => 'misc',
            4 => 'risc',
            5 => 'cpu',
            6 => 'dsp',
            7 => 'micro-8051',
        ];

        $map[ 'connectivity' ] = [
            1  => 'zigbee',
            2  => 'bluetooth-4b',
            3  => 'wifi',
            4  => 'nfc',
            5  => 'rfid',
            6  => 'zwave',
            7  => 'cellular-networks',
            8  => 'cellular-networks',
            9  => 'cellular-networks',
            10 => 'gps-sensor',
        ];

        $map[ 'communication' ] = [
            1 => 'hdmi',
            2 => 'usb',
            3 => 'lightning',
            4 => 'displayport',
            5 => 'thunderbolt',
        ];

        $map[ 'display' ] = [
            1 => 'lcd-display',
            2 => 'flexible-display',
            3 => 'led-display',
            4 => 'oled-display',
            5 => 'eink-display',
            6 => 'plasma-display',
            7 => 'microdisplay',
        ];

        $map[ 'wireless' ] = [
            1 => 'widi',
            2 => 'wigig',
            3 => 'whdi',
            4 => 'wireless-usb',
            5 => 'wireless-hd',
        ];

        $map[ 'audio' ] = [
            1 => 'microphone',
            2 => 'speakers',
            3 => 'headphones',
        ];

        $map[ 'power' ] = [
            1 => 'ac-power',
            2 => 'dc-power',
            3 => 'battery',
            4 => 'wireless-charging',
        ];

        $map[ 'sensors' ] = [
            1 => 'accelerometer-sensor',
            2 => 'gyro-sensor',
            3 => 'pressure-sensor',
            4 => 'magnetometer-sensor',
            5 => 'temperature-sensor',
            6 => 'particle-detector',
            7 => 'humidity-sensor',
            8 => 'image-sensor',
            9 => 'heart-sensor',
        ];

        $map[ 'touch' ] = [
            1 => 'resistive-touch',
            2 => 'capacitive-touch',
            3 => 'optical-touch',
            4 => 'saw-touch',
        ];

        $map[ 'materials' ] = [
            1 => 'plastic-design',
            2 => 'metal-design',
            3 => 'composite-design',
            4 => 'rubber-design',
            5 => 'non-plastic-design',

        ];

        $map[ 'os' ] = [
            1 => 'win',
            2 => 'ios',
            3 => 'android',
            4 => 'linux',
            5 => 'rtos',
        ];

        $this->map = [];
        foreach ($map as $column => $m) {
            foreach ($m as $id => $slug) {
                $m[$id] = $this->tags[$slug];
            }
            $this->map[$column] = $m;
        }
    }
}
