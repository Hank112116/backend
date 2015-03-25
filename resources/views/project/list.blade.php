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
    <h1>{!! $title !!}</h1>

    <div id="output_all" class='header-output'>
        @include('layouts.get-csv')
    </div>
</div>

<div class="search-bar">
    @include ('project.list-search')
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

                <th>Status</th>
                <th>Submit</th>

                <th>Hub</th>
                <th class="table--text-center">Propose</th>
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
                    @include('layouts.user', ['user' => $project->user])
                </td>

                <td>
                    {!! $project->project_country !!}
                    <br/>
                    <span class="table--text-light">{!! $project->project_city !!}</span>
                </td>

                <td>
                    {!! $project->profile->text_status !!}
                    @if($project->is_deleted)
                        <br/>Deleted ({{$project->deleted_date}})
                    @endif
                </td>

                <td>{!! $project->profile->text_project_submit !!}<br/> <!-- project summit date --></td>

                <td>
                    @if($project->schedule)
                    <i class="fa fa-check"></i>
                    @endif
                </td>

                <td class="table--text-center">{!! $project->propose->count() !!}</td>

                <td>{!! HTML::time($project->update_time)  !!}</td>

                <td>
                    {!!
                        link_to_action('ProjectController@showDetail', 'DETAIL',
                            $project->project_id, ['class' => 'btn-mini'])
                    !!}

                    {!!
                        link_to_action('ProjectController@showUpdate', 'EDIT',
                            $project->project_id, ['class' => 'btn-mini'])
                    !!}
                </td>
            </tr>
            @endforeach
        </table>

    </div>
</div>

@include('layouts.paginate', ['collection' => $projects, 'per_page' => isset($per_page)? $per_page : ''])


@stop



