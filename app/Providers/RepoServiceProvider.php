<?php namespace Backend\Providers;

use Backend\Model\Plain\SolutionCategory;
use Backend\Model\Plain\SolutionCertification;

use Backend\Repo\Lara;
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
            'Backend\Repo\RepoInterfaces\InboxInterface',
            'Backend\Repo\Lara\InboxRepo'
        );

        $this->app->bind(
            'Backend\Repo\RepoInterfaces\PerkInterface',
            'Backend\Repo\Lara\PerkRepo'
        );

        $this->app->bind(
            'Backend\Repo\RepoInterfaces\DuplicatePerkInterface',
            'Backend\Repo\Lara\DuplicatePerkRepo'
        );

        $this->app->bind(
            'Backend\Repo\RepoInterfaces\ProjectInterface', function ($app) {
                return new Lara\ProjectRepo(
                    new \Backend\Model\Eloquent\Project(),
                    new \Backend\Model\Eloquent\ProjectCategory(),
                    $app->make('Backend\Repo\RepoInterfaces\UserInterface'),
                    $app->make('Backend\Model\ModelInterfaces\ProjectTagBuilderInterface'),
                    $app->make('Backend\Model\ModelInterfaces\ProjectModifierInterface')
                );
            }
        );

        $this->app->bind(
            'Backend\Repo\RepoInterfaces\ProductInterface', function ($app) {
                return new Lara\ProductRepo(
                    new \Backend\Model\Eloquent\Project(),
                    $app->make('Backend\Repo\RepoInterfaces\DuplicateProductInterface'),
                    $app->make('Backend\Repo\RepoInterfaces\PerkInterface'),
                    $app->make('Backend\Repo\RepoInterfaces\UserInterface'),
                    $app->make('Backend\Model\ModelInterfaces\ProjectModifierInterface'),
                    new \ImageUp()
                );
            }
        );

        $this->app->bind(
            'Backend\Repo\RepoInterfaces\DuplicateProductInterface', function ($app) {
                return new Lara\DuplicateProductRepo(
                    new \Backend\Model\Eloquent\Project(),
                    new \Backend\Model\Eloquent\DuplicateProject(),
                    $app->make('Backend\Repo\RepoInterfaces\DuplicatePerkInterface'),
                    $app->make('Backend\Model\ModelInterfaces\ProjectModifierInterface')
                );
            }
        );

        $this->app->bind(
            'Backend\Repo\RepoInterfaces\TransactionInterface', function ($app) {
                return new Lara\TransactionRepo(
                    new \Backend\Model\Eloquent\Transaction(),
                    $app->make('Backend\Repo\RepoInterfaces\UserInterface'),
                    $app->make('Backend\Repo\RepoInterfaces\ProjectInterface')
                );
            }
        );

        $this->app->bind(
            'Backend\Repo\RepoInterfaces\SolutionInterface', function ($app) {
                return new Lara\SolutionRepo(
                    new \Backend\Model\Eloquent\Solution(),
                    $app->make('Backend\Repo\RepoInterfaces\DuplicateSolutionInterface'),
                    $app->make('Backend\Repo\RepoInterfaces\UserInterface'),
                    $app->make('Backend\Model\ModelInterfaces\SolutionModifierInterface'),
                    $app->make('Backend\Model\ModelInterfaces\ProjectTagBuilderInterface'),
                    new SolutionCategory(),
                    new SolutionCertification(),
                    new \ImageUp()
                );
            }
        );

        $this->app->bind(
            'Backend\Repo\RepoInterfaces\DuplicateSolutionInterface', function ($app) {
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
            'Backend\Repo\RepoInterfaces\MailTemplateInterface',
            'Backend\Repo\Lara\MailTemplateRepo'
        );

        $this->app->bind(
            'Backend\Repo\RepoInterfaces\LandingFeatureInterface',
            'Backend\Repo\Lara\LandingFeatureRepo'
        );

        $this->app->bind(
            'Backend\Repo\RepoInterfaces\LandingManufacturerInterface',
            'Backend\Repo\Lara\LandingManufacturerRepo'
        );

        $this->app->bind(
            'Backend\Repo\RepoInterfaces\LandingReferProjectInterface',
            'Backend\Repo\Lara\LandingReferProjectRepo'
        );

        $this->app->bind(
            'Backend\Repo\RepoInterfaces\HubInterface',
            'Backend\Repo\Lara\HubRepo'
        );

        $this->app->bind(
            'Backend\Repo\RepoInterfaces\CommentInterface', function ($app) {
                return new Lara\CommentRepo(
                    new \Backend\Model\Eloquent\Comment(),
                    $app->make('Backend\Repo\RepoInterfaces\UserInterface'),
                    $app->make('Backend\Repo\RepoInterfaces\ProjectInterface'),
                    $app->make('Backend\Repo\RepoInterfaces\ProductInterface'),
                    $app->make('Backend\Repo\RepoInterfaces\SolutionInterface')
                );
            }
        );

        $this->app->bind(
            'Backend\Repo\RepoInterfaces\LogAccessHelloInterface',
            'Backend\Repo\Lara\LogAccessHelloRepo'
        );
    }
}
