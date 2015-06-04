<tr>
    <td>{!! $q->questionnaire_id !!}</td>
    <td>{{-- Deleted Schedule --}}</td>
    <td>USER DELETED</td>
    <td>{{-- Deleted Schedule --}}</td>

    <td>
        @if($q->user)
        <a href="{!! $q->user->textFrontLink() !!}" target="_blank">{{ $q->user->textFullName() }}</a>
        @endif
    </td>

    <td class="table--text-right"></td>
    <td>{{-- Deleted Schedule ship_date --}}</td>
    <td>{{-- Deleted Schedule date_added --}}</td>

    <td>{{-- Deleted Schedule --}}</td>
    <td>{{-- Deleted Schedule --}}</td>

    <td>
        <a class="btn-mini" href="{!! action('HubController@showQuestionnaireDetail', $q->questionnaire_id) !!}">
            <i class="fa fa-eye fa-fw"></i>
            DETAIL
        </a>
    </td>

</tr>