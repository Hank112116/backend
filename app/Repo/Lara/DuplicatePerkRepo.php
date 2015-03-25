<?php namespace Backend\Repo\Lara;

use Carbon;
use Backend\Model\Eloquent\Perk;
use Backend\Model\Eloquent\DuplicatePerk;
use Backend\Repo\RepoInterfaces\PerkInterface;
use Backend\Repo\RepoInterfaces\DuplicatePerkInterface;

class DuplicatePerkRepo implements DuplicatePerkInterface
{
    private $copy_columns = [
        'perk_title', 'perk_description',
        'perk_amount', 'perk_total',
        'perk_limit', 'perk_delivery_date',
        'is_pro', 'has_shipping_fee',
        'shipping_fee_us', 'shipping_fee_intl',
        'update_time',
    ];

    public function __construct(Perk $perk, DuplicatePerk $duplicate, PerkInterface $perk_repo)
    {
        $this->perk = $perk;
        $this->duplicate = $duplicate;
        $this->perk_repo = $perk_repo;
    }

    private function duplicatesByProjectId($project_id)
    {
        $perks = $this->duplicate->where('project_id', $project_id)->get();
        $editable_perk_ids = $this->perk_repo->editablePerkIds();

        foreach ($perks as $perk) {
            $perk->setIsEditable(in_array($perk->perk_id, $editable_perk_ids));
        }

        return $perks;
    }

    public function updateDuplicateProjectPerks($project_id, $update_data)
    {
        $update_perk_ids = array_keys($update_data);
        $perks = $this->duplicatesByProjectId($project_id);

        foreach ($update_data as $perk_id => $data) {
            $p = $perks->filter(
                function ($perk) use ($perk_id) {
                    return $perk->perk_id == $perk_id;
                }
            )->first();

            if (array_get($data, 'is_new', false) and !$p) {
                $this->createDuplicate($project_id, $data);
                continue;
            }

            $this->updateDuplicate($p, $data);
        }

        // delete is_new but not in update_data duplicate perks
        $perks->map(
            function ($p) use ($update_perk_ids) {
                if ($p->is_new && !in_array($p->perk_id, $update_perk_ids)) {
                    $p->delete();
                }
            }
        );
    }

    private function createDuplicate($project_id, $data)
    {
        $data[ 'project_id' ]  = $project_id;
        $data[ 'update_time' ] = Carbon::now();
        $data[ 'is_new' ]      = 1;
        $this->duplicate->create(array_except($data, ['is_deleted']));
    }

    private function updateDuplicate($duplicate, $data)
    {
        $duplicate->fill(array_except($data, ['is_new', 'is_deleted']));
        $duplicate->is_editable = $duplicate->isEditable() ? '1' : '0';
        $duplicate->is_deleted = array_get($data, 'is_deleted', '0');
        $duplicate->update_time = Carbon::now();
        $duplicate->save();
    }

    /**
     * create, delete, update project perk
     *
     * @param int $project_id
     **/
    public function coverPerks($project_id)
    {
        $perks = $this->perk_repo->byProjectId($project_id);
        $duplicates = $this->duplicatesByProjectId($project_id);

        foreach ($duplicates as $duplicate) {
            if ($duplicate->is_new) {
                $this->createPerk($duplicate);
                continue;
            }

            $perk = $perks->filter(
                function ($p) use ($duplicate) {
                    return $p->perk_id == $duplicate->perk_id;
                }
            )->first();

            if ($perk && $perk->isEditable()) {
                $this->updatePerk($perk, $duplicate);
            }
        }

        // Delete Duplicate Perks
        $this->duplicate->where('project_id', $project_id)->delete();
    }

    private function createPerk(DuplicatePerk $duplicate)
    {
        $setter = $this->duplicateSetter($duplicate);
        $setter['project_id'] = $duplicate->project_id;
        $this->perk->create($setter);
    }

    private function updatePerk(Perk $perk, DuplicatePerk $duplicate)
    {
        if ($duplicate->is_deleted) {
            $perk->delete();
        } else {
            $setter = $this->duplicateSetter($duplicate);
            $perk->fill($setter);
            $perk->save();
        }
    }

    private function duplicateSetter(DuplicatePerk $duplicate)
    {
        $setter = array_only($duplicate->toArray(), $this->copy_columns);
        $setter['update_time'] = Carbon::now();

        return $setter;
    }
}
