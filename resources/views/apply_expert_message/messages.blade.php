<table class="table table-striped">
    <tbody>
        <tr>
            <th>Date</th>
            <th>Message</th>
        </tr>
        @foreach( $messages as $message)
            <tr>
                <td>{{ $message->created_at }}</td>
                <td>{{ $message->message }}</td>
            </tr>
        @endforeach
    </tbody>
</table>