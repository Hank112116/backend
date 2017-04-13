<div class="row">
    <div class="col-md-12">
        <table class="table table-striped">
            <tr>
                <th>Tickets</th>
                <th>Form Status</th>
                <th>Attendees</th>
            </tr>
            <tr>
                <td>
                    <b>AIT</b>
                </td>
                <td>
                    {!! link_to_action('ReportController@showQuestionnaire',
                    $approve_event_users->ait_form_rejected . ' Decline', [
                        'event'       => $event_id,
                        'form_status' => 'Rejected',
                        'dstart'      => $dstart,
                        'dend'        => $dend,
                    ], ['target' => '_blank']) !!} |
                    {!! link_to_action('ReportController@showQuestionnaire',
                    $approve_event_users->ait_form_ongoing . ' In Progress', [
                        'event'       => $event_id,
                        'form_status' => 'Ongoing',
                        'dstart'      => $dstart,
                        'dend'        => $dend,
                    ], ['target' => '_blank']) !!} |
                    {!! link_to_action('ReportController@showQuestionnaire',
                    $approve_event_users->ait_form_completed . ' Completed', [
                        'event'       => $event_id,
                        'form_status' => 'Completed',
                        'dstart'      => $dstart,
                        'dend'        => $dend,
                    ], ['target' => '_blank']) !!}
                </td>
                <td>
                    {!! link_to_action('ReportController@showQuestionnaire',
                    $approve_event_users->ait_dinner . ' + ' . $approve_event_users->ait_guest_dinner . ' Dinner', [
                        'event'     => $event_id,
                        'attendees' => 'dinner',
                        'dstart'    => $dstart,
                        'dend'      => $dend,
                    ], ['target' => '_blank']) !!} |
                    {!! link_to_action('ReportController@showQuestionnaire',
                    $approve_event_users->ait_sz . ' + ' . $approve_event_users->ait_guest_sz . ' SZ', [
                        'event'     => $event_id,
                        'attendees' => 'shenzhen',
                        'dstart'    => $dstart,
                        'dend'      => $dend,
                    ], ['target'    => '_blank']) !!} |
                    {!! link_to_action('ReportController@showQuestionnaire',
                    $approve_event_users->ait_sh . ' + ' . $approve_event_users->ait_guest_sh . ' SH', [
                        'event'     => $event_id,
                        'attendees' => 'sh',
                        'dstart'    => $dstart,
                        'dend'      => $dend,
                    ], ['target' => '_blank']) !!}
                </td>
            </tr>
        </table>
    </div>
</div>
