@extends('layouts.master')

@section('css')
    <link rel="stylesheet" href="/css/landing-manufacturer.css">
@stop

@section('js')
  <script src='/js/landing-manufacturer.js'></script>
@stop

@section('content')
<div class="page-header">
    <h1>MANUFACTURER</h1>
</div>

<div id="manufacturer" class="row">
    {!! Form::open([ 'action' => ['LandingController@updateManufacturer'], 
                    'method' => 'POST', 'enctype' => "multipart/form-data"]) !!}
    
        <div id="manufacturers" class="row manufacturer-blocks">
            @foreach ($manufacturers as $m)
                @include('landing.manufacturer-block', ['manufacturer' => $m])
            @endforeach
        </div>

        <div class="btn-block">
            <button id="js-add-manufacturer" class="btn-sassy">ADD MANUFACTURER</button>  
            <button class="btn-sassy btn-submit">UPDATE</button>    
        </div>
        
    {!! Form::close() !!}
</div>

@stop