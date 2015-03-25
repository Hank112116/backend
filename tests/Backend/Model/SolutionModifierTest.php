<?php

use Backend\Model\Eloquent;
use Backend\Model\Eloquent\DuplicateSolution;
use Backend\Model\Eloquent\Solution;
use Carbon\Carbon;
use Laracasts\TestDummy\Factory;

class SolutionModifierTest extends BackendTestCase
{
    private $solution;
    private $solutions;
    private $modifier;
    private $image_uploader;

    public function setUp()
    {
        parent::setUp();

        $this->solution       = new Solution;
        $this->solutions      = Factory::times(3)->create(Eloquent\Solution::class);
        $this->image_uploader = Mockery::mock('ImageUp');

        $this->modifier = new Backend\Model\SolutionModifier(
            $this->solution,
            new DuplicateSolution,
            $this->image_uploader
        );
    }

    private function assetEqualToAttributes($mock, $attrs)
    {
        foreach ($attrs as $attr => $value) {
            $this->assertEquals($value, $mock->$attr);
        }
    }

    /** @test */
    public function it_set_manager_approve()
    {
        $solution = $this->solutions->first();
        $this->modifier->managerApprove($solution->solution_id);

        $this->assertEquals(1, Solution::find($solution->solution_id)->is_manager_approved);
    }

    /** @test */
    public function is_set_to_on_shelf_after_approve()
    {
        $solution = $this->solutions->first();
        $this->modifier->approve($solution->solution_id);

        $s = Solution::find($solution->solution_id);
        $this->assertEquals(0, $s->is_manager_approved);
        $this->assetEqualToAttributes($s, $this->solution->on_shelf_status);
    }

    /** @test */
    public function it_set_to_draft_after_reject()
    {
        $solution = $this->solutions->first();
        $this->modifier->reject($solution->solution_id);

        $s = Solution::find($solution->solution_id);
        $this->assertEquals(0, $s->is_manager_approved);
        $this->assetEqualToAttributes($s, $this->solution->draft_status);
    }

    /** @test */
    public function is_can_set_to_on_shelf()
    {
        $solution = $this->solutions->first();
        $this->modifier->onShelf($solution->solution_id);

        $s = Solution::find($solution->solution_id);
        $this->assetEqualToAttributes($s, $this->solution->on_shelf_status);
    }

    /** @test */
    public function is_can_set_to_off_shelf()
    {
        $solution = $this->solutions->first();
        $this->modifier->offShelf($solution->solution_id);

        $s = Solution::find($solution->solution_id);
        $this->assetEqualToAttributes($s, $this->solution->off_shelf_status);
    }

    /** @test */
    public function it_copy_and_delete_duplicate_after_approve()
    {
        $solution  = $this->solutions->first();
        Factory::create(Eloquent\DuplicateSolution::class, [
            'solution_id'    => $solution->solution_id,
            'solution_title' => 'Duplicate Title'
        ]);

        $this->modifier->ongoingApprove($solution->solution_id);

        $this->assertNull(DuplicateSolution::find($solution->solution_id));
        $this->assertEquals(
            'Duplicate Title',
            Solution::find($solution->solution_id)->solution_title
        );
    }

    /** @test */
    public function it_delete_duplicate_after_reject()
    {
        $solution  = $this->solutions->first();
        $duplicate = Factory::create(Eloquent\DuplicateSolution::class);
        $duplicate->update([
            'solution_id' => $solution->solution_id
        ]);

        $this->modifier->ongoingReject($solution->solution_id);

        $this->assertNull(DuplicateSolution::find($solution->solution_id));
    }
}
