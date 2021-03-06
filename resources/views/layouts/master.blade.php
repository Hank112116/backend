<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>HWTrek Backend</title>

    <meta name="env"    content="{!! App::environment() !!}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="noty-msg"  content = "{{ Session::get('noty.msg') }}">
    <meta name="noty-type" content = "{!! Session::get('noty.type') !!} {!! Session::forget('noty') !!}">

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="Bookmark" href="/favicon.ico" type="image/x-icon" />

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    @yield('css')

    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    @yield('jqui')
</head>

<body>

    <div id='container' class='wrapper'>
        @include('layouts.master-link')
        <div class="page-wrapper">
            @yield('content')
        </div>
    </div>

    <script>
        if (!window.jQuery) {
            document.write('<script src="/js/vendor/jquery.min.js"><\/script>');
        }
    </script>

    <script src="{{ LinkGen::assets('js/vendor/vendors.js') }}"></script>
    <script src="{{ LinkGen::assets('js/common.js') }}"></script>
    @yield('js')

</body>
</html>