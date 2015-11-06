<div class="panel panel-default">
    <div class="panel-heading">Apply expert messages</div>
    <div class="panel-body">
        <table class="table table-striped">
            <tr>
                <th>Date</th>
                <th>Messages</th>
            </tr>

            @foreach($apply_expert_msg as $message)
                <tr>
                    <td>{{ $message->created_at }}</td>
                    <td>{{ $message->message }}</td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
