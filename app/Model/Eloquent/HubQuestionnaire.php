<?php
namespace Backend\Model\Eloquent;
/**
 * HubQuestionnaire Model
 *
 * @refer : hwp/application/schema/pms_questionnaire.php
 * @table : pms_questionnaire
 * @pk    : questionnaire_id
 * @columns :
 *     project_id  company_name  no_company  project_name  project_location  project_target
 *     ship_date  quantity  members  budget  stage  key  key_list  category  other_category
 *     nature  other_nature  shape_l  shape_w  shape_h  other_shape  weight  other_weight
 *     chips  other_chips  connectivity  other_connectivity  communication  other_communication
 *     display  other_display  wireless  other_wireless  audio  other_audio
 *     power  power_1_v  power_1_a  power_2_v  power_2_a
 *     power_3_v  power_3_a  power_4_v  power_4_a
 *     other_power  sensors  other_sensors  touch  other_touch
 *     materials  other_materials  cer  other_cer  os  other_os
 *     scope  involved  requirements  information  comments
 **/

class HubQuestionnaire extends \Eloquent
{

    protected $table = 'pms_questionnaire';
    protected $primaryKey = 'questionnaire_id';

    public $timestamps = false;

    public static $options = [
        'members'       => [
            1 => '1-5', 2 => '6-10', 3 => '11-20', 4 => '20+',
        ],
        'quantity'      => [
            1 => '0-500', 2 => '500-1000', 3 => '1000-2000',
            4 => '2000-5000', 5 => '5000+', 0 => 'Not sure yet',
        ],
        'budget'        => [
            1 => 'Up to $50,000', 2 => '$50,000-$100,000',
            3 => '$100,000-$200,000', 4 => '$200,000-$500,000',
            5 => '$500,000+', 0 => 'Don’t know yet',
        ],
        'stage'         => [
            1 => 'Proof of Concept', 2 => 'Working Prototype',
            3 => 'Product Design (e.g. industrial design or electronic board)',
            4 => 'Design for Manufacturability', 5 => 'Idea / Brainstorming'
        ],
        'category'      => [
            1 => 'Wearable', 2 => 'Industrial Applications',
            3 => 'Sports', 4 => 'Toys / Games', 5 => 'Cameras / Audio & Video',
            6 => 'Family / Home Automation', 7 => 'Mobile Device Accessories',
            8 => 'Auto', 9 => 'Health', 10 => 'Science',
        ],
        'nature'        => [
            1 => 'New development', 2 => 'Enhancement project',
            3 => 'Industrial design improvement', 4 => 'Mechanical improvement',
            5 => 'Electronic improvement', 6 => 'Software improvement',
            7 => 'Scale up',
        ],
        'chips'         => [
            1 => 'CPU core', 2 => 'RISC', 3 => 'MISC',
            4 => 'ARM', 5 => 'Intel', 6 => 'DSP', 7 => 'MCU',
        ],
        'connectivity'  => [
            1 => 'ZigBee', 2 => 'Bluetooth', 3 => 'WiFi',
            4 => 'NFC', 5 => 'RFID', 6 => 'Z-Wave',
            7 => '2G', 8 => '3G', 9 => '4G', 10 => 'GPS',
        ],
        'communication' => [
            1 => 'HDMI', 2 => 'USB', 3 => 'Lightning',
            4 => 'Display port', 5 => 'Thunderbolt',
        ],
        'display'       => [
            1 => 'LCD', 2 => 'Flexible Display', 3 => 'LED',
            4 => 'OLED', 5 => 'Electronic Paper Displays (E-Ink)',
            6 => 'PDP', 7 => 'Microdisplay',
        ],
        'wireless'      => [
            1 => 'WiDi', 2 => 'WiGig', 3 => 'WHDI',
            4 => 'Wireless USB', 5 => 'Wireless HD',
        ],
        'audio'         => [
            1 => 'Microphone', 2 => 'Speaker', 3 => 'Headphones',
        ],
        'power'         => [
            1 => 'AC Power', 2 => 'DC Power', 3 => 'Battery', 4 => 'Wireless Charging',
        ],
        'sensors'       => [
            1 => 'Accelerometer', 2 => 'Gyro', 3 => 'Pressure',
            4 => 'Magnetic', 5 => 'Temperature', 6 => 'Particles',
            7 => 'Moisture', 8 => 'Image', 9 => 'Bio-physiological',
        ],
        'touch'         => [
            1 => 'Resistive Touch', 2 => 'Capacitive Touch',
            3 => 'Optical Touch', 4 => 'Sonic Wave Touch',
        ],
        'materials'     => [
            1 => 'Plastic Parts', 2 => 'Metal Parts',
            3 => 'Composite Material Parts', 4 => 'Rubber Parts',
            5 => 'Non-metal & Non-plastic Parts',
        ],
        'cer'           => [
            1 => 'Safety', 2 => 'Green', 3 => 'FCC', 4 => 'FDA',
            5 => 'CE', 6 => 'CCC', 7 => 'UL',
        ],
        'os'            => [
            1 => 'Windows', 2 => 'iOS', 3 => 'Android', 4 => 'Linux', 5 => 'RTOS',
        ],
        'involved'      => [
            1 => 'Electronic Design House', 2 => 'Industrial Designer',
            3 => 'Mechanical Engineering', 4 => 'Tooling',
            5 => 'PCB Schematic or Layout Design', 6 => 'EMS',
            7 => 'OEM', 8 => 'ODM', 9 => 'Software or Firmware',
        ],
    ];

    public function schedule()
    {
        return $this->belongsTo(HubSchedule::class, 'project_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    private function explodeAttr($arr = '')
    {
        return $arr ? explode(',', $arr) : [];
    }

    public function textFirstBatch()
    {
        return static::$options['quantity'][$this->quantity];
    }

    public function textBudget()
    {
        return static::$options['budget'][$this->budget];
    }

    public function textProjectStage()
    {
        return static::$options['stage'][$this->stage];
    }

    public function textCategory()
    {
        if ($this->other_category) {
            return $this->other_category;
        } else {
            return $this->schedule ? $this->schedule->textCategory() : '';
        }
    }

    public function textProjectNature()
    {
        $result = implode(', ', $this->getProjectNatureOptions());

        if ($this->other_nature) {
            return "$this->other_nature, {$result}";
        } else {
            return $result;
        }
    }

    private function getProjectNatureOptions()
    {
        if (!$this->nature) {
            return [];
        }

        $natures = [];
        foreach (explode($this->nature, ',') as $opt) {
            if (isset(static::$options['nature'][$opt])) {
                $natures[] = static::$options['nature'][$opt];
            }
        }

        return $natures;
    }

    public function getOptions($type)
    {
        return static::$options[$type];
    }

    public function getOtherOption($type)
    {
        $attr = "other_{$type}";

        return $this->$attr ?: '';
    }

    public function hasOption($attr, $index)
    {
        return in_array($index, $this->explodeAttr($this->$attr));
    }
}
