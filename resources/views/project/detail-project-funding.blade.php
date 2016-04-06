 <div class="panel panel-default">
    <div class="panel-heading">Funding Rounds</div>
    <div class="panel-body">
        <table class="table table-striped">
            <tr>
                <th>Round</th>
                <th>Funding Date</th>
                <th>Amount (USD)</th>
                <th>Investors</th>
                <th>Press URL</th>
            </tr>
            @if ($funding_rounds)
                @foreach($funding_rounds as $funding_round)
                    <tr>
                        <td>{{ $funding_round->rounds }}</td>
                        <td>{{ $funding_round->date }}</td>
                        <td>{{ number_format($funding_round->amount, 1) }}</td>
                        <td>{{ $funding_round->investors }}</td>
                        <td>{!! link_to($funding_round->url, null, ['target' => '_blank']) !!}</td>
                    </tr>
                @endforeach
            @endif
        </table>
    </div>
 </div>
