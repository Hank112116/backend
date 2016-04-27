<?php namespace Backend\Providers;

use Backend\Model\Plain\ProjectProfile;
use Backend\Model\ProjectModifier;
use Backend\Model\ProjectProfileGenerator;
use Backend\Model\ProjectTagBuilder;
use Backend\Model\TagBuilder;
use Backend\Model\SolutionModifier;
use Backend\Model\FeatureModifier;
use Illuminate\Support\ServiceProvider;

class ModelServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(
            'Backend\Model\ModelInterfaces\ProjectProfileGeneratorInterface',
            function ($app) {
                return new ProjectProfileGenerator(
                    $app->make('Backend\Repo\RepoInterfaces\DuplicateProductInterface')
                );
            }
        );

        $this->app->singleton(
            'Backend\Model\ModelInterfaces\ProjectTagBuilderInterface',
            function ($app) {
                return new ProjectTagBuilder(
                    new \Backend\Model\Eloquent\ProjectTag()
                );
            }
        );

        $this->app->singleton(
            'Backend\Model\ModelInterfaces\TagBuilderInterface',
            function ($app) {
                return new TagBuilder(
                    new \Backend\Model\Eloquent\ProjectTag(),
                    new \Backend\Model\Eloquent\Tag()
                );
            }
        );

        $this->app->bind(
            'Backend\Model\ModelInterfaces\ProjectModifierInterface',
            function ($app) {
                return new ProjectModifier(
                    new \Backend\Model\Eloquent\Project(),
                    new ProjectProfile(),
                    new \ImageUp(),
                    new \Backend\Model\Eloquent\InternalProjectMemo(),
                    new \Backend\Model\Eloquent\ProjectTeam(),
                    new \Backend\Model\Eloquent\ProjectManager(),
                    new \Backend\Model\Eloquent\Adminer()
                );
            }
        );

        $this->app->bind(
            'Backend\Model\ModelInterfaces\SolutionModifierInterface',
            function ($app) {
                return new SolutionModifier(
                    new \Backend\Model\Eloquent\Solution(),
                    new \Backend\Model\Eloquent\DuplicateSolution(),
                    new \ImageUp()
                );
            }
        );
        $this->app->bind(
            'Backend\Model\ModelInterfaces\FeatureModifierInterface',
            function ($app) {
                return new FeatureModifier(
                    new \Backend\Model\Eloquent\Feature()
                );
            }
        );
    }
}
