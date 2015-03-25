@extends('layouts.master')
@include('layouts.macro')

@section('css')
    @cssLoader('product-list')
@stop

@section('js')
    @jsLoader('list')
@stop

@section('content')

<div class="page-header">
    <h1>
        PRODUCTS
        @if($has_wait_approve_product)
        <span class='header-notice fa fa-bell icon-vibrate'></span>
        @endif
    </h1>

    <div id="output_all" class='header-output'>
        @include('layouts.get-csv')
    </div>
</div>

<div class="search-bar">
    @include ('product.list-search')
</div>

<div class="row">
    <div class="col-md-12">

        <table class="table table-striped">
            <tr>
                <th>#</th>
                <th class="table--name">Title</th>
                <th>Category</th>
                <th>User</th>
                <th>Country<br/><span class="table--text-light">City</span></th>
                <th class="table--text-right">Raised <span class="table--text-light">Goal</span></th>
                <th>Status</th>
                <th>Submit</th>
                <th>Approve Date<br/>End Date</th>
                <th>Hub</th>
                <th>Last Update</th>
                <th></th>
            </tr>

            @foreach($projects as $project)
            <tr>
                <td>{!! $project->project_id !!}</td>
                <td class="table--name">
                    <a href="{!! $project->textFrontLink() !!}" target="_blank">
                        {!! $project->textTitle() !!}
                    </a>
                </td>

                <td>
                    @include('modules.list-category', ['category' => $project->category])
                </td>

                <td>
                    <a href="{!! $project->user->textFrontLink() !!}" target="_blank">
                        {!! $project->user->textFullName() !!}
                    </a>
                </td>

                <td>
                    {!! $project->project_country !!}<br/>
                    <span class="table--text-light">{!! $project->project_city !!}</span>
                </td>

                <td>
                    <span class='cash'>{!! HTML::cash($project->amount_get) !!}</span><br/>
                    <span class='cash table--text-light'>{!! HTML::cash($project->amount) !!}</span>
                </td>

                <td>{!! $project->profile->text_status !!}</td>

                <td>
                    {!! $project->profile->text_product_submit !!}
                </td>

                <td>
                    {!! HTML::date($project->approve_time) !!}<br/>
                    {!! HTML::date($project->end_date) !!}
                </td>

                <td>
                    @if($project->schedule)
                    <i class="fa fa-check"></i>
                    @endif
                </td>

                <td data-time="{!! $project->update_time !!}">
                   {!! HTML::time($project->update_time) !!}
                </td>

                <td>
                    {!! link_to_action(
                    'ProductController@showDetail', 'DETAIL',
                    $project->project_id, ['class' => 'btn-mini']) !!}

                    @if(!$project->is_find_end)
                        @if($project->profile->is_wait_approve_product or 
                            $project->profile->is_wait_approve_ongoing)

                        {!! link_to_action(
                        'ProductController@showUpdate', 'EDIT & APPROVE',
                        $project->project_id, ['class' => 'btn-mini btn-danger']) !!}
                        
                        @else
                        {!! link_to_action(
                        'ProductController@showUpdate', 'EDIT',
                        $project->project_id, ['class' => 'btn-mini']) !!}
                        @endif
                    @endif
                </td>
            </tr>
            @endforeach
        </table>

    </div>
</div>

@include('layouts.paginate', ['collection' => $projects, 'per_page' => isset($per_page)? $per_page : ''])

@stop



