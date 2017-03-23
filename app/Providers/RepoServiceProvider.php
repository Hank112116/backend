<?php namespace Backend\Providers;

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
            'Backend\Repo\Lara\ProjectRepo'
        );

        $this->app->bind(
            'Backend\Repo\RepoInterfaces\SolutionInterface',
            'Backend\Repo\Lara\SolutionRepo'
        );

        $this->app->bind(
            'Backend\Repo\RepoInterfaces\LandingFeatureInterface',
            'Backend\Repo\Lara\LandingFeatureRepo'
        );

        $this->app->bind(
            'Backend\Repo\RepoInterfaces\LandingRestrictedInterface',
            'Backend\Repo\Lara\LandingRestrictedRepo'
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

        $this->app->bind(
            'Backend\Repo\RepoInterfaces\ProjectStatisticInterface',
            'Backend\Repo\Lara\ProjectStatisticRepo'
        );

        $this->app->bind(
            'Backend\Repo\RepoInterfaces\EventReportInterface',
            'Backend\Repo\Lara\EventReportRepo'
        );
    }
}
