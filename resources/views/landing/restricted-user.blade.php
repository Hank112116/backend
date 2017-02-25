<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>User</h4>
            </div>
            <div class="panel-body">
                <table class="table table-striped">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th class="table--width-limit">
                            Company<br/>
                            <span class="table--text-light">Position</span>
                        </th>
                        <th></th>
                    </tr>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id() }}</td>
                            <td>
                                <a href="{!! $user->textFrontLink() !!}" target="_blank">{{ $user->textFullName() }}</a>
                            </td>
                            <td>{{ $user->textType() }}</td>
                            <td>{{ $user->textStatus() }}</td>
                            <td class="table--width-limit">
                                {{ $user->company }}<br/>
                                <span class="table--text-light">{{ $user->business_id  }}</span>
                            </td>
                            <td>
                                <button class="btn-mini btn-flat-red js-revoke" rel="{!! $user->id() !!}" object="user">revoke</button>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>