<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Project</h4>
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
                    @foreach($projects as $project)
                        <tr>
                            <td>{{ $project->id() }}</td>
                            <td>
                                <a href="{{ $project->textFrontLink() }}" target="_blank">{{ $project->textTitle() }}</a>
                            </td>
                            <td>
                                <a href="{{ $project->user->textFrontLink() }}">{{ $project->textUserName() }}</a>
                            </td>
                            <td>{{ $project->profile()->text_status }}</td>
                            <td>
                                <button class="btn-mini btn-flat-red js-revoke" rel="{!! $project->id() !!} " object="project">revoke</button>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>