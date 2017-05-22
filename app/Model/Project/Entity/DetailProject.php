<?php
namespace Backend\Model\Project\Entity;

use Backend\Enums\API\Response\Key\ProjectKey;

class DetailProject extends BasicProject
{
    private $kickstarter_url = 'https://www.kickstarter.com/projects/';
    private $indiegogo_url   = 'https://www.indiegogo.com/projects/';

    /**
     * DetailProject constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data);
    }

    /**
     * @return string
     */
    public function getStage()
    {
        return $this->data[ProjectKey::KEY_STAGE];
    }

    /**
     * @return string
     */
    public function getTextStage()
    {
        $stage = $this->getStage();

        $stage_map = [
            'tbd'               => 'TBD',
            'brainstorming'     => 'Brainstorming an idea',
            'poc'               => 'Proof of concept',
            'prototype'         => 'Working prototype',
            'industrial-design' => 'Enhancing industrial design',
            'improving'         => 'Improving electronic board',
            'manufacturability' => 'Design for manufacturability'
        ];

        if (!$stage or
            !array_key_exists($stage, $stage_map)
        ) {
            return 'N/A';
        }

        return $stage_map[$stage];
    }

    /**
     * @return string
     */
    public function getInnovationType()
    {
        return $this->data[ProjectKey::KEY_INNOVATION_TYPE];
    }

    /**
     * @return string
     */
    public function getTextInnovationType()
    {
        $innovation_type = $this->getInnovationType();

        $innovation_type_map = [
            'new-development'                => 'New development',
            'next-generation'                => 'Gen II product (prev. enhancement project)',
            'industrial-design-improvement'  => 'Industrial design improvement',
            'mechanical-improvement'         => 'Mechanical improvement',
            'electronic-improvement'         => 'Electronic improvement',
            'software-improvement'           => 'Software improvement',
            'scale-up'                       => 'Scale up',
            'others'                         => 'Others'
        ];

        if (!$innovation_type or
            !array_key_exists($innovation_type, $innovation_type_map)
        ) {
            return 'N/A';
        }

        return $innovation_type_map[$innovation_type];
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        return $this->data[ProjectKey::KEY_SUMMARY];
    }

    public function getDesignConcept()
    {
        return $this->data[ProjectKEy::KEY_DESIGN_CONCEPT];
    }

    /**
     * @return string
     */
    public function getKickstarterUrl()
    {
        $kickstarter_project_id = $this->data[ProjectKey::KEY_KICKSTARTER];

        if ($kickstarter_project_id) {
            return $this->kickstarter_url . $kickstarter_project_id;
        }

        return 'N/A';
    }

    /**
     * @return string
     */
    public function getIndiegogoUrl()
    {
        $indiegogo_project_id = $this->data[ProjectKey::KEY_INDIEGOGO];

        if ($indiegogo_project_id) {
            return $this->indiegogo_url . $indiegogo_project_id;
        }

        return 'N/A';
    }

    /**
     * @return string
     */
    public function getTeamSize()
    {
        return $this->data[ProjectKey::KEY_TEAM_SIZE];
    }

    /**
     * @return array
     */
    public function getTeamStrength()
    {
        return $this->data[ProjectKey::KEY_TEAM_STRENGTH];
    }

    /**
     * @return array
     */
    public function getResource()
    {
        return $this->data[ProjectKey::KEY_RESOURCE];
    }

    /**
     * @return array
     */
    public function getTextResource()
    {
        $resource = $this->getResource();

        if (empty($resource)) {
            return [];
        }

        $resource_map = [
            'manufacturing' => 'ODM / OEM / EMS',
            'modules'       => 'Modules',
            'ic-design'     => 'IC / Component design',
            'ee'            => 'Electrical engineering',
            'me'            => 'Mechanical engineering',
            'sd'            => 'Software design',
            'consulting'    => 'Manufacturing consulting',
            'marketing'     => 'Marketing services',
        ];

        $result = [];

        foreach ($resource as $item) {
            if (array_key_exists($item, $resource_map)) {
                $result[] = $resource_map[$item];
            }
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getResourceOther()
    {
        return $this->data[ProjectKey::KEY_RESOURCE_OTHER];
    }

    /**
     * @return string
     */
    public function getResourceMessage()
    {
        return $this->data[ProjectKey::KEY_RESOURCE_MESSAGE];
    }

    /**
     * @return array
     */
    public function getPowerSpec()
    {
        return $this->data[ProjectKey::KEY_POWER_SPEC];
    }

    /**
     * @return string
     */
    public function getPowerSpecACVolt()
    {
        $power_spec = $this->getPowerSpec();

        if (empty($power_spec)) {
            return 0;
        }

        if (!array_key_exists('ac', $power_spec)) {
            return 0;
        }

        return $power_spec['ac']['volt'];
    }

    /**
     * @return string
     */
    public function getPowerSpecACAmpere()
    {
        $power_spec = $this->getPowerSpec();

        if (empty($power_spec)) {
            return 0;
        }

        if (!array_key_exists('ac', $power_spec)) {
            return 0;
        }

        return $power_spec['ac']['ampere'];
    }

    /**
     * @return string
     */
    public function getPowerSpecDCVolt()
    {
        $power_spec = $this->getPowerSpec();

        if (empty($power_spec)) {
            return 0;
        }

        if (!array_key_exists('dc', $power_spec)) {
            return 0;
        }

        return $power_spec['dc']['volt'];
    }

    /**
     * @return string
     */
    public function getPowerSpecDCAmpere()
    {
        $power_spec = $this->getPowerSpec();

        if (empty($power_spec)) {
            return 0;
        }

        if (!array_key_exists('dc', $power_spec)) {
            return 0;
        }

        return $power_spec['dc']['ampere'];
    }

    /**
     * @return string
     */
    public function getPowerSpecWirelessVolt()
    {
        $power_spec = $this->getPowerSpec();

        if (empty($power_spec)) {
            return 0;
        }

        if (!array_key_exists('wireless', $power_spec)) {
            return 0;
        }

        return $power_spec['wireless']['volt'];
    }

    /**
     * @return string
     */
    public function getPowerSpecWirelessAmpere()
    {
        $power_spec = $this->getPowerSpec();

        if (empty($power_spec)) {
            return 0;
        }

        if (!array_key_exists('wireless', $power_spec)) {
            return 0;
        }

        return $power_spec['wireless']['ampere'];
    }

    /**
     * @return string
     */
    public function getPowerSpecBatteryCapacity()
    {
        $power_spec = $this->getPowerSpec();

        if (empty($power_spec)) {
            return 0;
        }

        if (!array_key_exists('battery', $power_spec)) {
            return 0;
        }

        return $power_spec['battery']['capacity'];
    }

    /**
     * @return array
     */
    public function getDimensionSpec()
    {
        return $this->data[ProjectKey::KEY_DIMENSION_SPEC];
    }

    /**
     * @return string
     */
    public function getDimensionSpecLength()
    {
        $dimension_spec = $this->getDimensionSpec();

        if (empty($dimension_spec)) {
            return 0;
        }

        if (!array_key_exists('length', $dimension_spec)) {
            return 0;
        }

        return $dimension_spec['length'];
    }

    /**
     * @return string
     */
    public function getDimensionSpecWidth()
    {
        $dimension_spec = $this->getDimensionSpec();

        if (empty($dimension_spec)) {
            return 0;
        }

        if (!array_key_exists('width', $dimension_spec)) {
            return 0;
        }

        return $dimension_spec['width'];
    }

    /**
     * @return string
     */
    public function getDimensionSpecHeight()
    {
        $dimension_spec = $this->getDimensionSpec();

        if (empty($dimension_spec)) {
            return 0;
        }

        if (!array_key_exists('height', $dimension_spec)) {
            return 0;
        }

        return $dimension_spec['height'];
    }

    /**
     * @return string
     */
    public function getDimensionSpecOther()
    {
        $dimension_spec = $this->getDimensionSpec();

        if (empty($dimension_spec)) {
            return 0;
        }

        if (!array_key_exists('other', $dimension_spec)) {
            return 0;
        }

        return $dimension_spec['other'];
    }

    /**
     * @return string
     */
    public function getWeightSpecWeight()
    {
        $weight_spec = $this->data[ProjectKey::KEY_WEIGHT_SPEC];

        if (empty($weight_spec)) {
            return 0;
        }

        if (!array_key_exists('weight', $weight_spec)) {
            return 0;
        }

        return $weight_spec['weight'];
    }

    /**
     * @return string
     */
    public function getWeightSpecOther()
    {
        $weight_spec = $this->data[ProjectKey::KEY_WEIGHT_SPEC];

        if (empty($weight_spec)) {
            return 0;
        }

        if (!array_key_exists('other', $weight_spec)) {
            return 0;
        }

        return $weight_spec['other'];
    }

    /**
     * @return array
     */
    public function getTargetMarket()
    {
        return $this->data[ProjectKey::KEY_TARGET_MARKET];
    }

    /**
     * @return string
     */
    public function getShipDate()
    {
        return $this->data[ProjectKey::KEY_SHIP_DATE];
    }

    /**
     * @return string
     */
    public function getQuantity()
    {
        return $this->data[ProjectKey::KEY_QUANTITY];
    }

    /**
     * @return string
     */
    public function getBudget()
    {
        return $this->data[ProjectKey::KEY_BUDGET];
    }

    /**
     * @return string
     */
    public function getTargetPrice()
    {
        return $this->data[ProjectKey::KEY_TARGET_PRICE];
    }

    /**
     * @return array
     */
    public function getAttachments()
    {
        $attachments = $this->data[ProjectKey::KEY_ATTACHMENTS];

        if (is_null($attachments)) {
            return [];
        }

        return $attachments;
    }

    public static function denormalize(array $data)
    {
        if (!is_array($data)) {
            throw new \InvalidArgumentException("'{$data}' is not a valid array format");
        }

        return new DetailProject($data);
    }
}
