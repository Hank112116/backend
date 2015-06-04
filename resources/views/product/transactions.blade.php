@extends('layouts.master')
@include('layouts.macro')

@section('css')
    @cssLoader('product-list')
@stop

@section('js')
    @jsLoader('list')
@stop

@section('content')
<div class="page-header">
    <h1>TRANSACTIONS</h1>
    @include('layouts.get-csv')
</div>

<div class="row search-bar">
    @include ('product.transactions-search')
</div>

<div class="row">
    <div class="col-md-12">

        <table class="table table-striped">
            <tr>
                <th>#</th>
                <th class="table--name">Project</th>
                <th>User</th>
                <th class="table--text-right">Total</th>
                <th class="table--text-right">Creator</th>
                <th class="table--text-right">HWTrek</th>
                <th class="table--text-right">Paypal</th>
                <th>Transaction ID</th>
                <th>Paypal Mail</th>
                <th>Ip</th>
                <th>Date</th>
            </tr>

            @foreach($transactions as $t)
            <tr>
                <td>{!! $t->transaction_id !!}</td>

                <td class="table--name">
                    {!! link_to_action('ProductController@showDetail', $t->textProjectTitle(), $t->project_id) !!}
                </td>

                <td>
                    @if($t->user)
                        <a href="{!! $t->user->textFrontLink() !!}" target="_blank">
                            {{ $t->user->textFullName() }}
                        </a>
                    @else
                        {!! $t->name !!}
                    @endif
                </td>

                <td class="table--text-right">{!! HTML::cash($t->preapproval_total_amount) !!}</td>
                <td class="table--text-right">{!! $t->creator_fee !!}</td>
                <td class="table--text-right">{!! $t->hwtrek_fee !!}</td>
                <td class="table--text-right">{!! $t->paypal_fee !!}</td>
                <td>{!! $t->preapproval_key !!}</td>
                <td>{!! $t->email !!}</td>
                <td>{!! $t->host_ip !!}</td>
                <td>{!! HTML::time($t->transaction_date_time) !!}</td>
            </tr>
            @endforeach
        </table>

    </div>
</div>

@include('layouts.paginate', ['collection' => $transactions, 'per_page' => isset($per_page)? $per_page : ''])


@stop