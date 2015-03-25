<tr>
    <td>{!! $q->questionnaire_id !!}</td>
    <td>{!! $q->schedule->project_id !!}</td>

    <td class="table--name" data-project-id = "{!! $q->schedule->project_id !!}">
        <a href="{!! $q->schedule->textFrontLink() !!}" target="_blank">
            {!! $q->schedule->textTitle() !!}
        </a>
    </td>

    <td class="table--text-center">
        @include('modules.list-category', ['category' => $q->schedule->category])
    </td>

    <td class="table--name">
        @if($q->user)
        <a href="{!! $q->user->textFrontLink() !!}" target="_blank">
            {!! $q->user->textFullName() !!}
        </a>
        @endif
        <br/><spna class="table--text-light">{!! $q->company_name !!}</spna>
    </td>

    <td class="table--text-right">{!! $q->textFitstBatch() !!}</td>
    <td>{!! $q->ship_date !!}</td>

    <td>{!! HTML::date($q->date_added) !!}</td>

    <td class="table--text-center">
        @if($q->schedule->hub_approve)
        <i class="fa fa-check"></i>
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
        @endif
    </td>

    <td>
        <a class="btn-mini" href="{!! action('HubController@showQuestionnaireDetail', $q->questionnaire_id) !!}">
            <i class="fa fa-eye fa-fw"></i>
            DETAIL
        </a>

        @if(Auth::user()->role->isManagerHead() or $q->schedule->inHubManagers(Auth::user()->id))
            <a class="btn-mini" href="{!! $q->schedule->textFrontEditLink() !!}" target="_blank">
                <i class="fa fa-pencil fa-fw"></i>
                EDIT SCHEDULE
            </a>
        @endif
    </td>
</tr>