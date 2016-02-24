<?php namespace Backend\Repo\Lara;

use Backend\Repo\RepoInterfaces\UserInterface;
use Backend\Model\Eloquent\ExpertFeature;
use Backend\Repo\RepoInterfaces\LandingExpertInterface;

class LandingExpertRepo implements LandingExpertInterface
{
    private $expert_feature;
    private $user;

    public function __construct(ExpertFeature $expert_feature, UserInterface $user)
    {
        $this->expert_feature =  $expert_feature;
        $this->user = $user;
    }
    private function truncateTable()
    {
        $this->expert_feature->truncate();
    }
    public function getExpertList()
    {
        $expert_features = $this->expert_feature->limit(12)->orderBy("order")->get();
        foreach ($expert_features as $e) {
            $e->entity = $this->user->find($e->block_data);
        }
        return $expert_features;
    }
    public function getExpert($user_id)
    {
        $user = $this->user->findExpert($user_id);
        return $user;
    }
    public function setExpert($data)
    {
        $this->truncateTable();
        //key is order number
        if (is_array($data)) {
            foreach ($data as $key => $row) {
                $expert = new ExpertFeature();
                $expert->block_data = $row["user_id"];
                $expert->order = $key;
                $expert->description = $row["description"];
                $expert->save();
            }
        }
    }
}
