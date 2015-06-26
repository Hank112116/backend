@extends('layouts.master')

@section('css')
<link rel="stylesheet" href="/css/adminer-update.css">
@stop

@section('content')
<div class="page-header">
    <h1>Edit Role</h1>
</div>

<div class="form-container">
    {!! Form::open([
        'action' => 'AdminerController@roleCreate',
        'method' => 'POST', 'class' => 'form-horizontal']) !!}

    <div class="form-group">
        <label for="name" class="col-md-3">Name</label>
        <div class="col-md-5">
            {!! Form::text('name', '',
                ['placeholder' => 'Enter Name', 'class'=>'form-control', 'id' => 'name']) !!}
        </div>
        <div class="col-md-5 error">{!! $errors->first('name') !!}</div>
    </div>

    <div class="form-group">
        <label for="name" class="col-md-3">Certification</label>

        <div class="col-md-8">
            @foreach($certs as $cert_title => $cert_list)
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{!! $cert_title !!}</h3>
                </div>

                <div class="panel-body">
                    @foreach($cert_list as $code => $name)
                    <div class="cert-block">
                        <label for="cert[]">{!! $name !!}</label>
                        <input type='checkbox' name="cert[]" value='{!! $code !!}' />
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="form-group">
        <label for="" class="col-md-3"></label>

        <div class="col-md-9">
            <button class="btn-sassy btn-submit">CREATE</button>
        </div>
    </div>

    {!! Form::close() !!}
</div>


@stop

