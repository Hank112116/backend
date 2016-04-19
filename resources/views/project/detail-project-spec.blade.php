<div class="panel panel-default">
    <div class="panel-heading">Power and Battery</div>
    <div class="panel-body">
        <div class="row">
            <div class="option-power col-sm-6">
                <span class="option-power--type">AC Power</span>
                <span class="option-power--value">{{ $project->powerSpec()->ac->volt }} V</span>
                <span class="option-power--value">{{ $project->powerSpec()->ac->ampere }} A</span>
            </div>
            <div class="option-power col-sm-6">
                <span class="option-power--type">DC Power</span>
                <span class="option-power--value">{{ $project->powerSpec()->dc->volt }} V</span>
                <span class="option-power--value">{{ $project->powerSpec()->dc->ampere }} A</span>
            </div>
            <div class="option-power col-sm-6">
                <span class="option-power--type">Battery</span>
                <span class="option-power--value">{{ $project->powerSpec()->battery->capacity }} mAh</span>
            </div>
            <div class="option-power col-sm-6">
                <span class="option-power--type">Wireless Charge</span>
                <span class="option-power--value">{{ $project->powerSpec()->wireless->volt }} V</span>
                <span class="option-power--value">{{ $project->powerSpec()->wireless->ampere }} A</span>
            </div>
            <div class="option-power col-sm-6">
                <span class="option-power--type">Other</span>
                    <span class="option-power--value">
                        {{ $project->powerSpec()->other }}
                    </span>
            </div>
        </div>

    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        Product Dimensions:
        <span class="option-dimention">
            {{ $project->dimensionSpec()->length }} mm(L) X {{ $project->dimensionSpec()->width }} mm(W) X {{ $project->dimensionSpec()->height }} mm(L)
        </span>
    </div>
    <div class="panel-body option-other-dimention">
        {{ $project->dimensionSpec()->other }}
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        Product Weight: <span class="option-weight">{{ $project->weightSpec()->weight }} g</span>
    </div>
    <div class="panel-body option-other-weight">
        {{ $project->weightSpec()->other }}
    </div>
</div>
