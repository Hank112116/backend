<?php namespace Backend\Providers;

use Backend\Model\Plain\ProjectProfile;
use Backend\Model\ProjectModifier;
use Backend\Model\ProjectProfileGenerator;
use Backend\Model\ProjectTagBuilder;
use Backend\Model\SolutionModifier;
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

        $this->app->bind(
            'Backend\Model\ModelInterfaces\ProjectModifierInterface',
            function ($app) {
                return new ProjectModifier(
                    new \Backend\Model\Eloquent\Project(),
                    new \Backend\Model\Eloquent\DuplicateProject(),
                    new ProjectProfile(),
                    new \ImageUp()
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
    }
}
