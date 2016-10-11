<?php namespace Backend\Providers;

use Backend\Repo\Lara;
use Backend\Model\Plain\SolutionCategory;
use Backend\Model\Plain\SolutionCertification;
use Illuminate\Support\ServiceProvider;

class RepoServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            'Backend\Repo\RepoInterfaces\RoleInterface',
            'Backend\Repo\Lara\RoleRepo'
        );

        $this->app->bind(
            'Backend\Repo\RepoInterfaces\AdminerInterface',
            'Backend\Repo\Lara\AdminerRepo'
        );

        $this->app->bind(
            'Backend\Repo\RepoInterfaces\ExpertiseInterface',
            'Backend\Repo\Lara\ExpertiseRepo'
        );

        $this->app->bind(
            'Backend\Repo\RepoInterfaces\UserInterface',
            'Backend\Repo\Lara\UserRepo'
        );

        $this->app->bind(
            'Backend\Repo\RepoInterfaces\ReportInterface',
            'Backend\Repo\Lara\ReportRepo'
        );

        $this->app->bind(
            'Backend\Repo\RepoInterfaces\ProjectInterface',
            function ($app) {
                return new Lara\ProjectRepo(
                    $app->make('Backend\Repo\RepoInterfaces\AdminerInterface'),
                    new \Backend\Model\Eloquent\Project(),
                    new \Backend\Model\Eloquent\ProjectCategory(),
                    $app->make('Backend\Repo\RepoInterfaces\UserInterface'),
                    $app->make('Backend\Model\ModelInterfaces\TagBuilderInterface'),
                    $app->make('Backend\Model\ModelInterfaces\ProjectTagBuilderInterface'),
                    $app->make('Backend\Model\ModelInterfaces\ProjectModifierInterface'),
                    new \Backend\Model\Eloquent\ProposeSolution(),
                    new \Backend\Model\Eloquent\GroupMemberApplicant(),
                    new \Backend\Model\Eloquent\ProjectMailExpert(),
                    new \Backend\Model\Eloquent\ProjectManager(),
                    new \Backend\Model\Eloquent\InternalProjectMemo(),
                    new \Backend\Model\Eloquent\ProjectStatistic(),
                    new \Backend\Model\Eloquent\Tag()
                );
            }
        );

        $this->app->bind(
            'Backend\Repo\RepoInterfaces\SolutionInterface',
            function ($app) {
                return new Lara\SolutionRepo(
                    new \Backend\Model\Eloquent\Solution(),
                    $app->make('Backend\Repo\RepoInterfaces\DuplicateSolutionInterface'),
                    $app->make('Backend\Repo\RepoInterfaces\UserInterface'),
                    $app->make('Backend\Model\ModelInterfaces\SolutionModifierInterface'),
                    $app->make('Backend\Model\ModelInterfaces\ProjectTagBuilderInterface'),
                    $app->make('Backend\Model\ModelInterfaces\FeatureModifierInterface'),
                    new SolutionCategory(),
                    new SolutionCertification(),
                    new \ImageUp(),
                    $app->make('Backend\Repo\RepoInterfaces\UserInterface')
                );
            }
        );

        $this->app->bind(
            'Backend\Repo\RepoInterfaces\DuplicateSolutionInterface',
            function ($app) {
                return new Lara\DuplicateSolutionRepo(
                    new \Backend\Model\Eloquent\Solution(),
                    new \Backend\Model\Eloquent\DuplicateSolution(),
                    $app->make('Backend\Repo\RepoInterfaces\ExpertiseInterface'),
                    $app->make('Backend\Model\ModelInterfaces\SolutionModifierInterface'),
                    new \ImageUp()
                );
            }
        );

        $this->app->bind(
            'Backend\Repo\RepoInterfaces\LandingFeatureInterface',
            'Backend\Repo\Lara\LandingFeatureRepo'
        );
        $this->app->bind(
            'Backend\Repo\RepoInterfaces\LandingExpertInterface',
            'Backend\Repo\Lara\LandingExpertRepo'
        );

        $this->app->bind(
            'Backend\Repo\RepoInterfaces\HubInterface',
            'Backend\Repo\Lara\HubRepo'
        );

        $this->app->bind(
            'Backend\Repo\RepoInterfaces\ProjectMailExpertInterface',
            'Backend\Repo\Lara\ProjectMailExpertRepo'
        );

        $this->app->bind(
            'Backend\Repo\RepoInterfaces\CommentInterface',
            'Backend\Repo\Lara\CommentRepo'
        );


        $this->app->bind(
            'Backend\Repo\RepoInterfaces\LogAccessHelloInterface',
            'Backend\Repo\Lara\LogAccessHelloRepo'
        );

        $this->app->bind(
            'Backend\Repo\RepoInterfaces\ApplyExpertMessageInterface',
            'Backend\Repo\Lara\ApplyExpertMessageRepo'
        );

        $this->app->bind(
            'Backend\Repo\RepoInterfaces\UserCommentInterface',
            'Backend\Repo\Lara\UserCommentRepo'
        );

        $this->app->bind(
            'Backend\Repo\RepoInterfaces\NewCommentInterface',
            'Backend\Repo\Lara\NewCommentRepo'
        );

        $this->app->bind(
            'Backend\Repo\RepoInterfaces\CommentFollowerInterface',
            'Backend\Repo\Lara\CommentFollowerRepo'
        );

        $this->app->bind(
            'Backend\Repo\RepoInterfaces\CommentReplyInterface',
            'Backend\Repo\Lara\CommentReplyRepo'
        );

        $this->app->bind(
            'Backend\Repo\RepoInterfaces\EventApplicationInterface',
            'Backend\Repo\Lara\EventApplicationRepo'
        );

        $this->app->bind(
            'Backend\Repo\RepoInterfaces\EventQuestionnaireInterface',
            'Backend\Repo\Lara\EventQuestionnaireRepo'
        );

        $this->app->bind(
            'Backend\Repo\RepoInterfaces\GroupMemberApplicantInterface',
            'Backend\Repo\Lara\GroupMemberApplicantRepo'
        );
    }
}
