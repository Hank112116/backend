@extends('layouts.master')
@include('layouts.macro')

@section('css')
<link rel="stylesheet" href="/css/user-detail.css">
@stop

@section('js')
<script src='/js/user-detail.js'></script>
@stop

@section('content')

<div class="page-header">
    <h1>MEMBER DETAIL</h1>
    <a href="{!! $user->textFrontLink() !!}" target="_blank" class="btn-mini">
        <i class="fa fa-eye"></i>Front
    </a>
    {!! link_to_action(
    'UserController@showUpdate', 'EDIT',
    $user->user_id, ['class' => 'btn-mini']) !!}
</div>

<div id="user-detail">
    <div class="row">
        <div class="col-md-2 col-md-offset-1">
            {!! HTML::image($user->getImagePath(), 'member-thumb', ['class' => 'user-avatar']) !!}
        </div>

        <div class="col-md-7 clearfix">
            <div class="data-group">
                <span class="label">Name</span>
                <span class="content">{{ $user->textFullName() }}</span>
            </div>
            <div class="data-group group-half">
                <span class="label">Role</span>
                  <span class="content">
                      @if($user->isToBeExpert() or $user->isApplyExpert())
                      <font color="red">{{ $user->textType() }}</font>
                      @else
                      {{ $user->textType() }}
                      @endif
                  </span>
            </div>
            <div class="data-group group-half">
                <span class="label">Active</span>
                <span class="content">{!! $user->textStatus() !!} ( Email : {!! $user->textEmailVerify() !!} )</span>
            </div>
        </div>
    </div> 
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="data-group">
                <span class="label">Country</span>
                <span class="content">{{ $user->country }}</span>
            </div>

            <div class="data-group">
                <span class="label">City</span>
                <span class="content">{{ $user->city }}</span>
            </div>

            <div class="clearfix">
                <div class="data-group group-half">
                    <span class="label">Company</span>
                    <span class="content">{{ $user->company }}</span>
                </div>

                <div class="data-group group-half">
                    <span class="label">Position</span>
                    <span class="content">{{ $user->business_id }}</span>
                </div>
            </div>

            <div class="clearfix">
                <div class="data-group group-half">
                    <span class="label">Registed On</span>
                    <span class="content">{!! $user->date_added !!}</span>
                </div>
            </div>

        </div>
    </div>
    @if ($attachments != 'null')
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Attachment</div>
                    <div class="panel-body">
                        @foreach($attachments as $attachment)
                            <div class="photo-preview">
                                <a target="_blank" href="{{ $attachment->url }}">
                                    <div style="background-image:url( {{$attachment->previews[0]}});" class="photo-thumb"></div>
                                </a>
                                <div class="file-info">
                                    <span>{{ $attachment->name }}</span><span> (</span><span>{{ ToolFunction::formatSizeUnits($attachment->size) }}</span><span>)</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Biography</div>
                <div class="panel-body rich-text-content">
                    {!! Purifier::clean($user->user_about) !!}
                </div>
            </div>
        </div>
    </div>

    @if ($user->isExpert() or $user->isToBeExpert())
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Industry</div>
                <div class="panel-body">
                    @foreach($user->getIndustryArray() as $tag)
                    <span class='tag'>{!! $tag !!}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @include ('modules.detail-expertise', ['title' => 'Expertise tags'])
    @endif
</div>

@stop

