<?php

use Backend\Model\Eloquent\Project;
use Backend\Model\Eloquent\ProjectTag;
use Backend\Model\Eloquent\Solution;

class ProjectTagMigrater extends Seeder
{
    private $tags;
    private $map = [];

    public function run()
    {
        $this->tags = ProjectTag::all()->lists('id', 'slug');
        $this->buildMigrateMap();

        Eloquent::unguard();

        Solution::chunk(50, function ($solutions) {
            foreach ($solutions as $solution) {
                if (!$solution->tags) {
                    continue;
                }

                $this->migrate($solution);
            }
        });

        Project::chunk(50, function ($projects) {
            foreach ($projects as $project) {
                if (!$project->tags) {
                    continue;
                }

                $this->migrate($project);
            }
        });
    }

    private function migrate(Eloquent $seed)
    {
        $new_tags = [];

        foreach (explode(',', $seed->tags) as $tag_id) {
            if (array_key_exists($tag_id, $this->map)) {
                $new_tags[ ] = $this->map[ $tag_id ];
            } else {
                $new_tags[ ] = $tag_id;
            }
        }

        $seed->tags = implode(',', array_unique($new_tags));
        $seed->save();
    }

    private function buildMigrateMap()
    {
        $origin_map = [
            '20,21,22,23'          => 'cellular-networks',
            '30'                   => 'zigbee',
            '41,38'                => 'zwave',
            '31,32'                => 'bluetooth-23',
            '33,34'                => 'bluetooth-4b',
            '24,25,26,27,28,29'    => 'wifi',
            '39'                   => 'nfc',
            '40,43,44,45,46,47'    => 'rfid',
            '35,36,37'             => 'light-communication',
            '48'                   => 'hdmi',
            '49,50'                => 'usb',
            '51'                   => 'lightning',
            '52'                   => 'displayport',
            '53'                   => 'thunderbolt',
            '54'                   => 'mydp',
            '55'                   => 'mhl-interface',
            '56'                   => 'widi',
            '57'                   => 'wigig',
            '58'                   => 'whdi',
            '59'                   => 'wireless-usb',
            '60'                   => 'wireless-hd',
            '63'                   => 'speakers',
            '64'                   => 'headphones',
            '83'                   => 'lcd-display',
            '84'                   => 'flexible-display',
            '85'                   => 'oled-display',
            '86'                   => 'eink-display',
            '87'                   => 'plasma-display',
            '88,89,90,91,92'       => 'led-display',
            '95'                   => 'power-management',
            '93'                   => 'ac-power',
            '94'                   => 'dc-power',
            '70,71,72,73,74,75,76' => 'solar-power',
            '97'                   => 'battery',
            '98'                   => 'wireless-charging',
            '99'                   => 'resistive-touch',
            '100'                  => 'capacitive-touch',
            '101'                  => 'optical-touch',
            '102'                  => 'saw-touch',
            '103,104'              => 'touch-software',
            '108'                  => 'cpu',
            '106'                  => 'misc',
            '105,107'              => 'risc',
            '110'                  => 'micro-8051',
            '109'                  => 'dsp',
            '111'                  => 'win',
            '112'                  => 'ios',
            '113'                  => 'android',
            '114'                  => 'linux',
            '115'                  => 'rtos',
            '116'                  => 'accelerometer-sensor',
            '117'                  => 'gyro-sensor',
            '119'                  => 'magnetometer-sensor',
            '42'                   => 'gps-sensor',
            '122'                  => 'humidity-sensor',
            '121'                  => 'particle-detector',
            '118'                  => 'pressure-sensor',
            '120'                  => 'temperature-sensor',
            '123'                  => 'image-sensor',
            '124'                  => 'heart-sensor',
            '125'                  => 'mechanical-design',
            '126'                  => 'molding-tooling',
            '127'                  => 'plastic-design',
            '128'                  => 'metal-design',
            '130'                  => 'non-plastic-design',
            '129'                  => 'composite-design',
            '131'                  => 'industrial-design',
            '132'                  => 'thermal-design',
            '133'                  => 'ic-design',
            '138'                  => 'pcb-design',
        ];

        foreach ($origin_map as $ids => $slug) {
            if (str_contains($ids, ',')) {
                $this->parseMap($ids, $slug);
            } else {
                $this->map[ $ids ] = $this->tags[ $slug ];
            }
        }
    }

    private function parseMap($ids, $slug)
    {
        foreach (explode(',', $ids) as $id) {
            $this->map[ $id ] = $this->tags[ $slug ];
        }
    }
}
