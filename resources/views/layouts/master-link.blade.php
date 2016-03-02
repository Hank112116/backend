<nav class="navbar master-navbar" role="navigation">

    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="/"></a>
    </div>

    <div class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">

            @if(Auth::check())
            <ul class="nav" id="side-menu">

                @if(Auth::user()->isShowLink('adminer'))
                <li>
                    <a href="{!! action('AdminerController@showList') !!}">
                        <i class="fa fa-user fa-fw"></i>
                        Back-end team
                    </a>
                </li>
                @endif

                @if(Auth::user()->isShowLink('report'))
                    <li>
                        <a href="#">
                            <i class="fa fa-line-chart fa-fw"></i>
                            Report
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level">
                            @if(Auth::user()->isShowLink('report_full') || Auth::user()->isShowLink('registration_report'))
                                <li>
                                    <a href="{!! action('ReportController@showRegistrationReport', ['range' => 7]) !!}">
                                        <i class="fa fa-users fa-fw"></i>
                                        Registration Summary
                                    </a>
                                </li>
                            @endif
                            @if(Auth::user()->isShowLink('report_full') || Auth::user()->isShowLink('comment_report'))
                                <li>
                                    <a href="{!! action('ReportController@showCommentReport', ['range'=>7]) !!}">
                                        <i class="fa fa-comment-o fa-fw"></i>
                                        Comments Summary
                                    </a>
                                </li>
                            @endif
                            @if(Auth::user()->isShowLink('report_full') || Auth::user()->isShowLink('event_report'))
                                <li>
                                    <a href="{!! action('ReportController@showEventReport', ['event'=>'']) !!}">
                                        <i class="fa fa-glass fa-fw"></i>
                                        Events Summary
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if(Auth::user()->isShowLink('user'))
                <li>
                    <a href="{!! action('UserController@showList') !!}">
                        <i class="fa fa-users fa-fw"></i>
                        Members
                    </a>
                </li>
                @endif

                @if(Auth::user()->isShowLink('project'))
                <li>
                    <a href="{!! action('ProjectController@showList') !!}">
                        <i class="fa fa-send fa-fw"></i>
                        Projects
                    </a>
                </li>
                @endif

                @if(Auth::user()->isShowLink('solution'))
                <li>
                    <a href="{!! action('SolutionController@showList') !!}">
                        <i class="fa fa-plug fa-fw"></i>
                        Solutions
                    </a>
                </li>
                @endif

                @if(Auth::user()->isShowLink('email_template'))
                <li>
                    <a href="{!! action('MailTemplateController@showList') !!}">
                        <i class="fa fa-envelope fa-fw"></i>
                        Emails
                    </a>
                </li>
                @endif
                <?php //print_r(Auth::user()->isShowLink('marketing_full')); die();?>
                @if(Auth::user()->isShowLink('marketing'))
                <li>
                    <a href="#">
                        <i class="fa fa-anchor fa-fw"></i>
                        Marketing
                        <span class="fa arrow"></span>
                    </a>

                    <ul class="nav nav-second-level">
                        @if(Auth::user()->isShowLink('marketing_full'))
                        <li>
                            <a href="{!! action('LandingController@showFeature') !!}">
                                <i class="fa fa-tag fa-fw"></i> Feature
                            </a>
                        </li>
                        <li>
                            <a href="{!! action('LandingController@showHello') !!}">
                                <i class="fa fa-tag fa-fw"></i> Hello
                            </a>
                        </li>
                        @endif
                        <li>
                            <a href="{!! action('LandingController@showExpert') !!}">
                                <i class="fa fa-tag fa-fw"></i> Home Expert List
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                @if(Auth::user()->isAdmin())
                <li>
                    <a href="#">
                        <i class="fa fa-comments fa-fw"></i>
                        Comments
                        <span class="fa arrow"></span>
                    </a>

                    <ul class="nav nav-second-level">
                        <li>
                            <a href="{!! action('CommentController@showProfession') !!}">
                                <i class="fa fa-comment-o fa-fw"></i>
                                Expert Comments
                            </a>
                        </li>

                        <li>
                            <a href="{!! action('CommentController@showProject') !!}">
                                <i class="fa fa-comment-o fa-fw"></i>
                                Project Comments
                            </a>
                        </li>
                        <li>
                            <a href="{!! action('CommentController@showSolution') !!}">
                                <i class="fa fa-comment-o fa-fw"></i>
                                Solution Comments
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="{!! action('InboxController@index') !!}">
                        <i class="fa fa-inbox fa-fw"></i>
                        Inbox
                    </a>
                </li>
                @endif

                <li>
                    <a href="/logout">
                        <i class="fa fa-sign-out fa-fw"></i>
                        Logout
                    </a>
                </li>
            </ul>
            @endif
            <!-- /#side-menu -->
        </div>
        <!-- /.sidebar-collapse -->
        <div class="sidebar-toggle-block">
            <button class="btn-sidebar-toggle">
                <i class="fa fa-dot-circle-o"></i>
            </button>
        </div>
    </div>

</nav>