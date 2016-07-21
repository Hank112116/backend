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
                    {{ $event_users->ait_applied }} Applied |
                    {{ $event_users->ait_dropped }} Dropped
                </td>
                <td>
                    {{ $event_users->ait_form_sent }} FormSent |
                    {{ $event_users->ait_selected }} Selected |
                    {{ $event_users->ait_considering }} Considering |
                    {{ $event_users->ait_rejected }} Rejected
                </td>
            </tr>
            <tr>
                <td>
                    <b>Meetup Shenzhen</b>
                </td>
                <td>
                    {{ $event_users->meetup_sz_applied }} Applied |
                    {{ $event_users->meetup_dropped }} Dropped
                </td>

                <td>
                    {{ $event_users->meetup_sz_selected }} Selected |
                    {{ $event_users->meetup_sz_considering }} Considering |
                    {{ $event_users->meetup_sz_rejected }} Rejected
                </td>
            </tr>
            <tr>
                <td>
                    <b>Meetup Osaka</b>
                </td>
                <td>
                    {{ $event_users->meetup_osaka_applied }} Applied
                </td>
                <td>
                    {{ $event_users->meetup_osaka_selected }} Selected |
                    {{ $event_users->meetup_osaka_considering }} Considering |
                    {{ $event_users->meetup_osaka_rejected }} Rejected
                </td>
            </tr>
        </table>
    </div>
</div>
