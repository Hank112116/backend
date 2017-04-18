@extends('layouts.master')

@section('css')
    <link rel="stylesheet" href="{{ LinkGen::assets('css/user-update.css') }}">
@stop

@section('js')
    <script src="{{ LinkGen::googleMap()}}"></script>
    <script src="{{ LinkGen::assets('js/user-update.js') }}"></script>
@stop

@section('content')
    <div class="page-header">
        <h1>{{ $user->textFullName() }}</h1>
    </div>

    <div class="form-container">
        {!! Form::open([ 'action' => ['UserController@update', $user->user_id],
                        'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form',
                        'enctype' => 'multipart/form-data' ]) !!}

        <div class="form-group">

            <label for="user-head" class="col-md-3">Upload Image</label>
            <div class="col-md-5">
                {!! HTML::image($user->getImagePath(), 'thumb', ['class' => 'user-avatar']) !!}
                <input type="file" name="head" id="user-head">
            </div>
            <div class="col-md-5"></div>
        </div>

        <!-- Active -->
        <div class="form-group">
            <label for="verify" class="col-md-3">
                Email Verify<br/>
            </label>

            <div class="col-md-5">
                <div>
                    @if($user->verified())
                    Verify
                    @else
                    None
                    @endif
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="role" class="col-md-3">
                Role
            </label>
            <div class="col-md-5">
                @if($user->isToBeExpert() or $user->isApplyExpert())
                <span class="color-red">{{ $user->textType() }}</span>
                @else
                {{ $user->textType() }}
                @endif
            </div>
        </div>

        {!! Form::hidden('email', $user->email) !!}

        <div class="form-group">
            <label for="first-name" class="col-md-3">First Name</label>
            <div class="col-md-5">
                {{ $user->user_name }}
            </div>
            <div class="col-md-5"></div>
        </div>

        <div class="form-group">
            <label for="last-name" class="col-md-3">Last Name</label>
            <div class="col-md-5">
                {{ $user->last_name }}
            </div>
            <div class="col-md-5"></div>
        </div>

        <div class="form-group">
            <label for="last-name" class="col-md-3">Country City</label>
            <div class="col-md-5">
                {{ $user->country }} {{$user->city}}
            </div>
            <div class="col-md-5"></div>
        </div>

        <div class="form-group">
            <label for="last-name" class="col-md-3">Company Position</label>
            <div class="col-md-5">
                {{ $user->company }} {{ $user->business_id }}
            </div>
            <div class="col-md-5"></div>
        </div>

        <div class="form-group">
            <label for="last-name" class="col-md-3">Registed On</label>
            <div class="col-md-5">
                {{ $user->date_added }}
            </div>
            <div class="col-md-5"></div>
        </div>

        @if ($user->isExpert() or $user->isToBeExpert())
            <div class="form-group">
                <label for="industry" class="col-md-3">Industry</label>
                <div class="col-md-9 industry">
                    @include('user.update-industry')
                </div>
            </div>
        @endif
        @if (!$attachments->isEmpty())
            <div class="form-group">
                <label for="industry" class="col-md-3">Attachments</label>
                <div class="col-md-9 industry">
                    <div class="panel panel-default">
                        <div class="panel-heading">Attachments</div>
                        <div class="panel-body">
                            @foreach($attachments as $attachment)
                                <div class="photo-preview">
                                    <a target="_blank" href="{{ $attachment->getUrl() }}">
                                        <div style="background-image:url( {{$attachment->getPreview() }});" class="photo-thumb"></div>
                                    </a>
                                    <div class="file-info">
                                        <span>{{ $attachment->getName() }}</span><span> (</span><span>{{ ToolFunction::formatSizeUnits($attachment->getSize()) }}</span><span>)</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="form-group">
            <label for="biography" class="col-md-3">Biography</label>
            <div class="col-md-9">
                {!! Form::textarea('user_about', Purifier::clean($user->user_about),
                    ['placeholder' => 'Biography', 'class'=>'js-editor', 'id'=>'biography']) !!}
            </div>
        </div>

        @if ($user->isExpert() or $user->isToBeExpert())
            <div class="form-group">
                <label for="experties" class="col-md-3">Expertise Tags</label>
                <div class="col-md-9 expertise" data-select-tags="expertises">
                    @include('modules.update-expertise')
                </div>
            </div>
        @endif

        <div class="form-group">
            <label for="" class="col-md-3"></label>
            <div class="col-md-9">
                <button class="btn-sassy btn-submit">Update</button>
            </div>
        </div>    　
        {!! Form::close() !!}
    </div>

@stop

