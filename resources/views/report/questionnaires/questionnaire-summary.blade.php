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
                    $approve_event_users->ait_form_rejected . ' Rejected', [
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
                    $approve_event_users->ait_kyoto . ' + ' . $approve_event_users->ait_guest_kyoto . ' Kyoto', [
                        'event'     => $event_id,
                        'attendees' => 'kyoto',
                        'dstart'    => $dstart,
                        'dend'      => $dend,
                    ], ['target' => '_blank']) !!} |
                    {!! link_to_action('ReportController@showQuestionnaire',
                    $approve_event_users->ait_osaka . ' + ' . $approve_event_users->ait_guest_osaka . ' Osaka', [
                        'event'     => $event_id,
                        'attendees' => 'osaka',
                        'dstart'    => $dstart,
                        'dend'      => $dend,
                    ], ['target' => '_blank']) !!}
                </td>
            </tr>
            <tr>
                <td>
                    <b>Meetup Shenzhen</b>
                </td>
                <td>
                    {!! link_to_action('ReportController@showEventReport',
                    $approve_event_users->meetup_sz_rejected . ' Rejected', [
                        $event_id,
                        'ticket'          => 'meetup-sz',
                        'internal_status' => 'rejected',
                        'dstart' => $dstart,
                        'dend'   => $dend,
                    ], ['target' => '_blank']) !!} |
                    {!! link_to_action('ReportController@showEventReport',
                    $approve_event_users->meetup_sz_premium . ' Premium', [
                        $event_id,
                        'ticket'          => 'meetup-sz',
                        'internal_status' => 'premium',
                        'dstart' => $dstart,
                        'dend'   => $dend,
                    ], ['target' => '_blank']) !!} |
                    {!! link_to_action('ReportController@showEventReport',
                    $approve_event_users->meetup_sz_expert . ' Expert', [
                        $event_id,
                        'ticket'          => 'meetup-sz',
                        'internal_status' => 'expert',
                        'dstart' => $dstart,
                        'dend'   => $dend,
                    ], ['target' => '_blank']) !!}
                </td>
                <td>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Meetup Osaka</b>
                </td>
                <td>
                    {!! link_to_action('ReportController@showEventReport',
                    $approve_event_users->meetup_osaka_rejected . ' Rejected', [
                        $event_id,
                        'ticket'          => 'meetup-osaka',
                        'internal_status' => 'rejected',
                        'dstart' => $dstart,
                        'dend'   => $dend,
                    ], ['target' => '_blank']) !!} |
                    {!! link_to_action('ReportController@showEventReport',
                    $approve_event_users->meetup_osaka_premium . ' Premium', [
                        $event_id,
                        'ticket'          => 'meetup-osaka',
                        'internal_status' => 'premium',
                        'dstart' => $dstart,
                        'dend'   => $dend,
                    ], ['target' => '_blank']) !!} |
                    {!! link_to_action('ReportController@showEventReport',
                    $approve_event_users->meetup_osaka_expert . ' Expert', [
                        $event_id,
                        'ticket'          => 'meetup-osaka',
                        'internal_status' => 'expert',
                        'dstart' => $dstart,
                        'dend'   => $dend,
                    ], ['target' => '_blank']) !!}
                </td>
                <td>
                </td>
            </tr>
        </table>
    </div>
</div>
