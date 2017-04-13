<div class="row">
    <div class="col-md-12">
        <table class="table table-striped">
            <tr>
                <th>Tickets</th>
                <th>Application status</th>
                <th>Internal Selection status</th>
            </tr>
            <tr>
                <td>
                    <b>AIT</b>
                </td>
                <td>
                    {!! link_to_action('ReportController@showEventReport',
                    $event_users->ait_applied . ' Applied', [
                        $event_id,
                        'ticket' => 'tour',
                        'status' => 'applied',
                        'dstart' => $dstart,
                        'dend'   => $dend,
                    ], ['target' => '_blank']) !!} |
                    {!! link_to_action('ReportController@showEventReport',
                    $event_users->ait_dropped . ' Dropped', [
                        $event_id,
                        'ticket' => 'tour',
                        'status' => 'dropped',
                        'dstart' => $dstart,
                        'dend'   => $dend,
                    ], ['target' => '_blank']) !!}
                </td>
                <td>
                    {!! link_to_action('ReportController@showEventReport',
                    $event_users->ait_form_sent . ' FormSent', [
                        $event_id,
                        'ticket'          => 'tour',
                        'internal_status' => 'form-sent',
                        'dstart' => $dstart,
                        'dend'   => $dend,
                    ], ['target' => '_blank']) !!} |
                    {!! link_to_action('ReportController@showEventReport',
                    $event_users->ait_selected . ' Selected', [
                        $event_id,
                        'ticket'          => 'tour',
                        'internal_status' => 'selected',
                        'dstart' => $dstart,
                        'dend'   => $dend,
                    ], ['target' => '_blank']) !!} |
                    {!! link_to_action('ReportController@showEventReport',
                    $event_users->ait_considering . ' Considering', [
                        $event_id,
                        'ticket'          => 'tour',
                        'internal_status' => 'considering',
                        'dstart' => $dstart,
                        'dend'   => $dend,
                    ], ['target' => '_blank']) !!} |
                    {!! link_to_action('ReportController@showEventReport',
                    $event_users->ait_rejected . ' Rejected', [
                        $event_id,
                        'ticket'          => 'tour',
                        'internal_status' => 'rejected',
                        'dstart' => $dstart,
                        'dend'   => $dend,
                    ], ['target' => '_blank']) !!}
                </td>
            </tr>
            <tr>
                <td>
                    <b>Meetup Shenzhen</b>
                </td>
                <td>
                    {!! link_to_action('ReportController@showEventReport',
                    $event_users->meetup_sz_applied . ' Applied', [
                        $event_id,
                        'ticket' => 'meetup-sz',
                        'status' => 'applied',
                        'dstart' => $dstart,
                        'dend'   => $dend,
                    ], ['target' => '_blank']) !!} |
                    {!! link_to_action('ReportController@showEventReport',
                    $event_users->meetup_dropped . ' Dropped', [
                        $event_id,
                        'ticket' => 'meetup',
                        'status' => 'dropped',
                        'dstart' => $dstart,
                        'dend'   => $dend,
                    ], ['target' => '_blank']) !!}
                </td>

                <td>
                    {!! link_to_action('ReportController@showEventReport',
                    $event_users->meetup_sz_sent . ' Sent', [
                        $event_id,
                        'ticket'          => 'meetup-sz',
                        'internal_status' => 'sent',
                        'dstart' => $dstart,
                        'dend'   => $dend,
                    ], ['target' => '_blank']) !!} |
                    {!! link_to_action('ReportController@showEventReport',
                    $event_users->meetup_sz_rejected . ' Rejected', [
                        $event_id,
                        'ticket'          => 'meetup-sz',
                        'internal_status' => 'rejected',
                        'dstart' => $dstart,
                        'dend'   => $dend,
                    ], ['target' => '_blank']) !!} |
                    {!! link_to_action('ReportController@showEventReport',
                    $event_users->meetup_sz_premium . ' Premium', [
                        $event_id,
                        'ticket'          => 'meetup-sz',
                        'internal_status' => 'premium',
                        'dstart' => $dstart,
                        'dend'   => $dend,
                    ], ['target' => '_blank']) !!} |
                    {!! link_to_action('ReportController@showEventReport',
                    $event_users->meetup_sz_expert . ' Expert', [
                        $event_id,
                        'ticket'          => 'meetup-sz',
                        'internal_status' => 'expert',
                        'dstart' => $dstart,
                        'dend'   => $dend,
                    ], ['target' => '_blank']) !!}
                </td>
            </tr>
            <tr>
                <td>
                    <b>Meetup Osaka</b>
                </td>
                <td>
                    {!! link_to_action('ReportController@showEventReport',
                    $event_users->meetup_osaka_applied . ' Applied', [
                        $event_id,
                        'ticket' => 'meetup-osaka',
                        'status' => 'applied','dstart' => $dstart,
                        'dend'   => $dend,

                    ], ['target' => '_blank']) !!}
                </td>
                <td>
                    {!! link_to_action('ReportController@showEventReport',
                    $event_users->meetup_osaka_sent . ' Sent', [
                        $event_id,
                        'ticket'          => 'meetup-osaka',
                        'internal_status' => 'sent',
                        'dstart' => $dstart,
                        'dend'   => $dend,
                    ], ['target' => '_blank']) !!} |
                    {!! link_to_action('ReportController@showEventReport',
                    $event_users->meetup_osaka_rejected . ' Rejected', [
                        $event_id,
                        'ticket'          => 'meetup-osaka',
                        'internal_status' => 'rejected',
                        'dstart' => $dstart,
                        'dend'   => $dend,
                    ], ['target' => '_blank']) !!} |
                    {!! link_to_action('ReportController@showEventReport',
                    $event_users->meetup_osaka_premium . ' Premium', [
                        $event_id,
                        'ticket'          => 'meetup-osaka',
                        'internal_status' => 'premium',
                        'dstart' => $dstart,
                        'dend'   => $dend,
                    ], ['target' => '_blank']) !!} |
                    {!! link_to_action('ReportController@showEventReport',
                    $event_users->meetup_osaka_expert . ' Expert', [
                        $event_id,
                        'ticket'          => 'meetup-osaka',
                        'internal_status' => 'expert',
                        'dstart' => $dstart,
                        'dend'   => $dend,
                    ], ['target' => '_blank']) !!}
                </td>
            </tr>
        </table>
    </div>
</div>
