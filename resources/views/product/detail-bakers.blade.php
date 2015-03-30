<table class="table table-striped">
    <tr>
        <th>#</th>
        <th>Perk</th>
        <th>User</th>
        <th>Total</th>
        <th>Transaction ID</th>
        <th>Paypal Mail</th>
        <th>Date</th>
    </tr>

    @foreach($bakers as $baker)
        <tr>
            <td>{!! $baker->transaction_id !!}</td>
            <td>#{!! $baker->perk->perk_id !!} {!! $baker->perk->perk_title !!}</td>
            <td>
                <a href="//{!! config('app.front_domain') !!}/profile/{!! $baker->user->user_id !!}" target="_blank">
                    {!! $baker->name !!}
                </a> {{-- use the name record in transaction table , rather in user table --}}
            </td>
            <td>{!! HTML::cash($baker->preapproval_total_amount) !!}</td>
            <td>{!! $baker->preapproval_key !!}</td>
            <td>{!! $baker->email !!}</td>
            <td>{!! $baker->transaction_date_time !!}</td>
        </tr>
    @endforeach

</table>
