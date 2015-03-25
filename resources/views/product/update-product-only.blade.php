@if(!$project->profile->is_ongoing)  {{-- On going project should not change fund period--}}

    <div class="form-group">
        <label for="expert-order" class="col-md-3">Expert Order Period</label>
        <div class="col-md-1">
            {!! Form::selectRange('manufactuer_total_days', 14, 1, $project->manufactuer_total_days,
                ['class'=>'form-control', 'id' => 'expert-order']) !!}
        </div>
        <div class="col-md-4 order-days">Days</div>
        <div class="col-md-5"></div>
    </div>

    <div class="form-group">
        <label for="customer-order" class="col-md-3">Customer Order Period</label>
        <div class="col-md-1">
            {!! Form::selectRange('total_days', 31, 10, $project->total_days,
                ['class'=>'form-control', 'id' => 'customer-order']) !!}
        </div>
        <div class="col-md-4 order-days">Days</div>
        <div class="col-md-5"></div>
    </div>

@endif

<div class="form-group">
    <label for="goal" class="col-md-3">Goal</label>
    <div class="col-md-5">
        <div class="input-group">
            <span class="input-group-addon">$</span>
            <input maxlength="9" class="form-control" id='goal'
                placeholder="Product Goal" name='amount' value="12000">
        </div>
    </div>
    <div class="col-md-5"></div>
</div>

<div class="form-group">
    <label for="video" class="col-md-3">Video URL</label>
    <div class="col-md-5">
        <input type="text" class="form-control" id="video" name="video" value="{!! $project->video !!}">
    </div>
    <div class="col-md-5"></div>
</div>

<div class="form-group">
    <label for="experties" class="col-md-3">
        Perks
        {!! link_to_action(
            'ProductController@renderNewPerk', 'Add Expert Perk', '1',
            ['class' => 'perk-add js-prepend-newperk']) !!}
        {!! link_to_action(
            'ProductController@renderNewPerk', 'Add Custom Perk', '0',
            ['class' => 'perk-add js-prepend-newperk']) !!}
    </label>
    <div class="col-md-9 perks">
        @foreach ($project->perks()->orderBy('is_pro', 'desc')->get() as $perk)
            @include('product.update-perk', ['perk' => $perk])
        @endforeach
    </div>
</div>