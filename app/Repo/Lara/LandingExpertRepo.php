<?php namespace Backend\Repo\Lara;

use Backend\Repo\RepoInterfaces\UserInterface;
use Backend\Model\Eloquent\ExpertFeature;
use Backend\Repo\RepoInterfaces\LandingExpertInterface;

class LandingExpertRepo implements LandingExpertInterface
{
    public function __construct(ExpertFeature $expertFeature, UserInterface $user)
    {
        $this->expertFeature =  $expertFeature;
        $this->user = $user;
    }

    public function getExpertList()
    {
        $expertFeatures = $this->expertFeature->limit(12)->orderBy("order")->get();

        foreach ($expertFeatures as $e) {
            $e->entity = $this->user->find($e->block_data);
        }
        return $expertFeatures;
    }
    public function getExpert($user_id)
    {

        $user = $this->user->findExpert($user_id);
        return $user;
    }
    public function setExpert($sort)
    {
        $this->expertFeature->truncate();
        //key is order number
        if (is_array($sort)) {
            foreach ($sort as $key => $user_id) {
                $expert = new ExpertFeature();
                $expert->block_data = $user_id;
                $expert->order = $key;
                $expert->save();
            }
        }
    }
}
