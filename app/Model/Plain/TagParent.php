<?php namespace Backend\Model\Plain;

use Backend\Enums\TechCategory;

class TagParent
{
    public $name;
    public $nodes = [];
    public $node_tag_ids = [];
    public $key;

    private static $parent_tags = [
        TechCategory::AUDIO_VIDEO   => 'Audio & Video',
        TechCategory::CERTIFICATION => 'Certification',
        TechCategory::CONNECTIVITY  => 'Connectivity',
        TechCategory::DISPLAY       => 'Display',
        TechCategory::ELECTRONIC    => 'Electronic',
        TechCategory::HW_INTERFACE  => 'Interface',
        TechCategory::MECHANICAL    => 'Mechanical',
        TechCategory::OS            => 'Operating System',
        TechCategory::POWER         => 'Power',
        TechCategory::PROCESSOR     => 'Processor',
        TechCategory::SENSOR        => 'Sensor',
        TechCategory::TOUCH         => 'Touch',
    ];

    public function __construct($parent_tag)
    {
        $this->key  = $parent_tag;
        $this->name = self::$parent_tags[$parent_tag];
    }

    public function appendNode($tag, $all_tags)
    {
        $this->nodes[] = new TagNode($tag, $all_tags);
        $this->node_tag_ids[] = $tag;
    }

    public function containsActiveProjectTag($project_tags)
    {
        $intersect = array_intersect($this->node_tag_ids, $project_tags);

        return count($intersect) > 0;
    }
}
