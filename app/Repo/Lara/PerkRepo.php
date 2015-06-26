<?php namespace Backend\Repo\Lara;

use Backend\Model\Eloquent\Perk;
use Carbon;
use Backend\Repo\RepoInterfaces\PerkInterface;

class PerkRepo implements PerkInterface
{
    public function __construct(Perk $perk)
    {
        $this->perk = $perk;
    }

    public function byProjectId($project_id)
    {
        $perks = $this->perk->where('project_id', $project_id)->get();
        $editable_perk_ids = $this->editablePerkIds()->toArray();

        foreach ($perks as $perk) {
            $perk->setIsEditable(in_array($perk->perk_id, $editable_perk_ids));
        }

        return $perks;
    }

    public function editablePerkIds()
    {
        return $this->perk
            ->where('perk_get', 0)
            ->orWhereNull('perk_get')
            ->lists('perk_id');
    }

    public function updateProjectPerks($project_id, $update_data)
    {
        $perks = $this->byProjectId($project_id);

        foreach ($update_data as $perk_id => $data) {
            if (array_get($data, 'is_new', false)) {
                $this->createPerk($project_id, $data);
                continue;
            }

            $p = $perks->filter(
                function ($perk) use ($perk_id) {
                    return $perk->perk_id == $perk_id;
                }
            )->first();

            $this->updatePerk($p, $data);
        }
    }

    private function updatePerk($perk, $data)
    {
        if (!$perk->isEditable()) {
            return;
        }

        if (array_get($data, 'is_deleted', false)) {
            $perk->delete();

            return;
        }

        $perk->fill(array_except($data, ['is_new', 'is_deleted']));
        $perk->update_time = Carbon::now();
        $perk->save();
    }

    private function createPerk($project_id, $data)
    {
        $data['project_id'] = $project_id;
        $data['update_time'] = Carbon::now();

        $this->perk->create(array_except($data, ['is_new', 'is_deleted']));
    }

    public function newEntity($is_pro = 0)
    {
        $perk          = new Perk();
        $perk->is_pro  = $is_pro;
        $perk->is_new  = 1;
        $perk->is_editable = 1;
        $perk->perk_id = rand() % 10000000000;

        return $perk;
    }
}
