<tr>
    <td>{!! $q->questionnaire_id !!}</td>
    <td>{!! $q->schedule->project_id !!}</td>

    <td class="table--name" data-project-id = "{!! $q->schedule->project_id !!}">
        <a href="{!! $q->schedule->textFrontLink() !!}" target="_blank">
            {{ $q->schedule->textTitle() }}
        </a>
    </td>

    <td class="table--text-center">
        @include('modules.list-category', ['category' => $q->schedule->category])
    </td>

    <td class="table--name">
        @if($q->user)
        <a href="{!! $q->user->textFrontLink() !!}" target="_blank">
            {{ $q->user->textFullName() }}
        </a>
        @endif
        <br/><spna class="table--text-light">{!! $q->company_name !!}</spna>
    </td>

    <td class="table--text-right">{!! $q->textFirstBatch() !!}</td>
    <td>{!! $q->ship_date !!}</td>

    <td>{!! HTML::date($q->date_added) !!}</td>
    <td class="table--text-center">
        {!! $q->schedule->profile->text_status !!}
    </td>
    <td class="table--name">
        @if($q->schedule->hub_approve)
            @if(Carbon::parse(env('SHOW_DATE'))->lt(Carbon::parse($q->schedule->date_added)))
                @if(!$q->mail_send_time)
                    @if(!$q->schedule->hub_managers)
                    <a class="btn-mini btn-danger sendmail" href="javascript:void(0)" projectId="{{ $q->schedule->project_id }}"
                        projectTitle="{{ $q->schedule->textTitle() }}" userId="{{ $q->schedule->user_id }}" PM="">
                    @else
                    <a class="btn-mini btn-danger sendmail" href="javascript:void(0)" projectId="{{ $q->schedule->project_id }}"
                        projectTitle="{{ $q->schedule->textTitle() }}" userId="{{ $q->schedule->user_id }}" PM="{!! $q->schedule->hub_managers !!}">
                    @endif
                        <i class="fa fa-envelope fa-fw"></i> SEND
                    </a>
                @else
                    @foreach ($q->mail_send_experts as $expert)
                        <p class="hub-manages">
                            e:<a href="{!! $expert["link"] !!}" target="_blank">
                            {!! $expert["id"] !!}
                            </a>
                        </p>
                    @endforeach
                        <spna class="table--text-light">
                            {{$q->mail_send_time}}<br>
                            by {{ $q->mail_send_admin }}
                        </span>
                @endif
            @else
                <spna class="table--text-center"><i class="fa fa-check"></i></span>
            @endif
        @endif
    </td>

    <td>
        @if(!$q->schedule->hub_managers)
            <p class="hub-manages">
                <i class="fa fa-fw fa-exclamation-triangle"></i> No PM
            </p>
        @else
            @foreach($q->schedule->getHubManagerNames() as $manager)
            <p class="hub-manages">
                <i class="fa fa-user fa-fw"></i> {!! $manager !!}
            </p>
            @endforeach

            @foreach($q->schedule->getDeletedHubManagerNames() as $manager)
            <p class="hub-manages">
                <i class="fa fa-flash fa-fw"></i> {!! $manager !!}
            </p>
            @endforeach
        @endif
    </td>
    <td class="table--text-left">
        @if($q->schedule->hub_note)
            {{ $q->schedule->textNoteLevel()}} : {{ $q->schedule->hub_note }}
        @endif

    </td>
    <td>
        <a class="btn-mini" href="{!! action('HubController@showQuestionnaireDetail', $q->questionnaire_id) !!}">
            <i class="fa fa-eye fa-fw"></i> DETAIL
        </a>
        @if($q->schedule->isDeleted())
            <a href="" class="btn btn-primary btn-disabled" disabled>This project was deleted</a>
        @endif

        @if(Auth::user()->role->isManagerHead() or $q->schedule->inHubManagers(Auth::user()->id))
            <a class="btn-mini" href="{!! $q->schedule->textFrontEditLink() !!}" target="_blank">
                <i class="fa fa-pencil fa-fw"></i> EDIT SCHEDULE
            </a>
        @endif
    </td>
</tr>