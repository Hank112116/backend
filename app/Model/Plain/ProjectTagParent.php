<?php namespace Backend\Model\Plain;

use Backend\Model\Eloquent\ProjectTag;

class ProjectTagParent
{
    public $id;
    public $name;
    public $nodes = [];
    public $node_tag_ids = [];

    private static $parent_tags = [
        'connectivity'       => 'Connectivity',
        'physical-interface' => 'Physical Interface',
        'wireless'           => 'Wireless Display Communication',
        'audio'              => 'Audio',
        'video'              => 'Video',
        'display'            => 'Display',
        'power'              => 'Power',
        'touch'              => 'Touch',
        'chip'               => 'Main Chipset / Processor',
        'os'                 => 'Operating Systems',
        'sensor'             => 'Sensors',
        'me'                 => 'Mechanical Engineering',
        'ee'                 => 'Electronic Engineering',
    ];

    public function __construct(ProjectTag $parent_tag)
    {
        $this->id = $parent_tag->tag_id;
        $this->name = self::$parent_tags[$parent_tag->slug];
    }

    public function appendNode(ProjectTag $tag)
    {
        $this->nodes[] = new ProjectTagNode($tag);
        $this->node_tag_ids[] = $tag->tag_id;
    }

    public function containsActiveProjectTag($project_tags)
    {
        $intersect = array_intersect($this->node_tag_ids, $project_tags);

        return count($intersect) > 0;
    }
}
