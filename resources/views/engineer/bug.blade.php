@extends('layouts.master')

@section('css')
    @cssLoader("engineer-index")
    <style>
        .bug-reporter-wrapper {
            padding-top: 100px;
            width: 96%;
            margin-left: 2%;
        }

        .bug-content {
            width: 100%;
            height: 150px;
        }

        .bug-report {
            width: 100%;
            margin: 15px 0 15px 0;
            font-size: 12px;
            color: #666;
        }

        .onoffswitch {
            position: relative; width: 121px;
            -webkit-user-select:none; -moz-user-select:none; -ms-user-select: none;
        }
        .onoffswitch-checkbox {
            display: none;
        }
        .onoffswitch-label {
            display: block; overflow: hidden; cursor: pointer;
            border: 2px solid #999999; border-radius: 20px;
        }
        .onoffswitch-inner {
            display: block; width: 200%; margin-left: -100%;
            -moz-transition: margin 0.3s ease-in 0s; -webkit-transition: margin 0.3s ease-in 0s;
            -o-transition: margin 0.3s ease-in 0s; transition: margin 0.3s ease-in 0s;
        }
        .onoffswitch-inner:before, .onoffswitch-inner:after {
            display: block; float: left; width: 50%; height: 30px; padding: 0; line-height: 30px;
            font-size: 14px; color: white; font-family: Trebuchet, Arial, sans-serif; font-weight: bold;
            -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box;
        }
        .onoffswitch-inner:before {
            content: "ENCODE";
            padding-left: 10px;
            background-color: #34A7C1; color: #FFFFFF;
        }
        .onoffswitch-inner:after {
            content: "RAW";
            padding-right: 10px;
            background-color: #EEEEEE; color: #999999;
            text-align: right;
        }
        .onoffswitch-switch {
            display: block; width: 18px; margin: 6px;
            background: #FFFFFF;
            border: 2px solid #999999; border-radius: 20px;
            position: absolute; top: 0; bottom: 0; right: 87px;
            -moz-transition: all 0.3s ease-in 0s; -webkit-transition: all 0.3s ease-in 0s;
            -o-transition: all 0.3s ease-in 0s; transition: all 0.3s ease-in 0s;
        }
        .onoffswitch-label.active .onoffswitch-inner {
            margin-left: 0;
        }
        .onoffswitch-label.active .onoffswitch-switch {
            right: 0px;
        }
    </style>
@stop

@section('js')
    <script type="text/javascript" src="/react/bug-reporter.js"></script>
@stop

@section('content')

    <div class="bug-reporter-wrapper">
        <div id="bug-reporter">

        </div>
    </div>

@stop
