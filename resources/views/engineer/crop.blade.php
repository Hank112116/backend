@extends('layouts.master')

@section('css')
    @cssLoader("engineer-index")
    <style>
        .component {
            position: relative;
            padding: 2em;
            height: 600px;
            border: 3px solid #49708A;
            max-width: 901px;
            overflow: hidden;
            margin: 0 auto;
            background-color: #eee;
        }

        .resize-container {
            position: relative;
            display: inline-block;
            margin: 0 auto;
            cursor: move;
        }

        .resize-container img {
            display: block;
            max-width: 100%;
        }

        .resize-container.active img {
            outline: 2px dashed rgba(222,60,80,.9);
        }

        .resize-handle-ne,
        .resize-handle-se,
        .resize-handle-nw,
        .resize-handle-sw {
            position: absolute;
            display: block;
            width: 10px;
            height: 10px;
            background: rgba(222,60,80,.9);
            z-index: 999;
        }

        .resize-handle-nw {
            top: -5px;
            left: -5px;
            cursor: nw-resize;
        }

        .resize-handle-sw {
            bottom: -5px;
            left: -5px;
            cursor: sw-resize;
        }

        .resize-handle-ne {
            top: -5px;
            right: -5px;
            cursor: ne-resize;
        }

        .resize-handle-se {
            bottom: -5px;
            right: -5px;
            cursor: se-resize;
        }

        .overlay {
            position: absolute;
            left: 50%;
            top: 50%;
            margin-left: -200px;
            margin-top: -200px;
            z-index: 999;
            width: 400px;
            height: 400px;
            border: solid 2px rgba(222,60,80,.9);
            box-sizing: content-box;
            pointer-events: none;
        }

        .overlay:after,
        .overlay:before {
            content: '';
            position: absolute;
            display: block;
            width: 204px;
            height: 40px;
            border-left: dashed 2px rgba(222,60,80,.9);
            border-right: dashed 2px rgba(222,60,80,.9);
        }

        .overlay:before {
            top: 0;
            margin-left: -2px;
            margin-top: -40px;
        }

        .overlay:after {
            bottom: 0;
            margin-left: -2px;
            margin-bottom: -40px;
        }

        .overlay-inner:after,
        .overlay-inner:before {
            content: '';
            position: absolute;
            display: block;
            width: 40px;
            height: 204px;
            border-top: dashed 2px rgba(222,60,80,.9);
            border-bottom: dashed 2px rgba(222,60,80,.9);
        }

        .overlay-inner:before {
            left: 0;
            margin-left: -40px;
            margin-top: -2px;
        }

        .overlay-inner:after{
            right: 0;
            margin-right: -40px;
            margin-top: -2px;
        }

        .btn-crop {
            position: absolute;
            vertical-align: bottom;
            right: 5px;
            bottom: 5px;
            padding: 6px 10px;
            z-index: 999;
            background-color: rgb(222,60,80);
            border: none;
            border-radius: 5px;
            color: #FFF;
        }

        .btn-crop img {
            vertical-align: middle;
            margin-left: 8px;
        }
    </style>
@stop

@section('js')
    <script type="text/javascript" src="/react/cropper.js"></script>
@stop

@section('content')

    <div id="cropper" data-image="{!! $base64 !!}">

    </div>

@stop