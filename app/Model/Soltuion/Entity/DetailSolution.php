<?php
namespace Backend\Model\Solution\Entity;

use Backend\Enums\API\Response\Key\SolutionKey;
use Backend\Enums\API\Response\Key\UserKey;
use Backend\Facades\Log;
use Backend\Model\Solution\Certification\CertificationFactory;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DetailSolution extends BasicSolution
{
    /**
     * BasicSolution constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data);
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->data[SolutionKey::KEY_IMG];
    }

    /**
     * @return string
     */
    public function getImageFileName()
    {
        $path = parse_url($this->getImage(), PHP_URL_PATH);

        return pathinfo($path, PATHINFO_BASENAME);
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->data[SolutionKey::KEY_DESCRIPTION];
    }

    /**
     * TODO use value object
     *
     * @return array
     */
    public function getShowcase()
    {
        return $this->data[SolutionKey::KEY_SHOWCASE];
    }

    /**
     * TODO use value object
     *
     * @return array
     */
    public function getPulse()
    {
        return $this->data[SolutionKey::KEY_PULSE];
    }

    /**
     * @return array
     */
    public function getTargetProjectStages()
    {
        return $this->data[SolutionKey::KEY_PROJECT_STAGES];
    }

    /**
     * @return string
     */
    public function getOtherTargetProjectStage()
    {
        return $this->data[SolutionKey::KEY_OTHER_PROJECT_STAGES];
    }

    /**
     * @return array
     */
    public function getTargetProjectCategories()
    {
        return $this->data[SolutionKey::KEY_PROJECT_CATEGORIES];
    }

    /**
     * @return string
     */
    public function getOtherTargetProjectCategories()
    {
        return $this->data[SolutionKey::KEY_OTHER_PROJECT_CATEGORIES];
    }

    /**
     * @return Collection
     */
    public function getCertifications()
    {
        $certifications = $this->data[SolutionKey::KEY_CERTIFICATIONS];

        $result = Collection::make();

        if (empty($certifications)) {
            return $result;
        }

        foreach ($certifications as $certification) {
            try {
                $certification = CertificationFactory::create($certification['key']);

                $result->push($certification);
            } catch (\InvalidArgumentException $e) {
                Log::warning($e->getMessage(), $e->getTrace());
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getCustomCertifications()
    {
        return $this->data[SolutionKey::KEY_CUSTOM_CERTIFICATIONS];
    }

    /**
     * @return string
     */
    public function getCompatibility()
    {
        return $this->data[SolutionKey::KEY_COMPATIBILITY];
    }

    /**
     * @return array
     */
    public function getServedCustomers()
    {
        return $this->data[SolutionKey::KEY_SERVED_CUSTOMERS];
    }
}
