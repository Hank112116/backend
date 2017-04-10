@php
    /* @var $solution \Backend\Model\LowPriorityObject\LowPrioritySolution */
@endphp
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Solution</h4>
            </div>
            <div class="panel-body">
                <table class="table table-striped">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Owner</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                    @foreach($solutions as $solution)
                        <tr>
                            <td>{{ $solution->id() }}</td>
                            <td>
                                <a href="{{ $solution->url() }}" target="_blank">{{ $solution->title() }}</a>
                            </td>
                            <td>
                                <a href="{{ $solution->ownerUrl() }}" target="_blank">{{ $solution->ownerFullName() }}</a>
                            </td>
                            <td>{{ $solution->status() }}</td>
                            <td>
                                <button class="btn-mini btn-flat-red js-revoke" rel="{!! $solution->id() !!}" object="solution">revoke</button>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>