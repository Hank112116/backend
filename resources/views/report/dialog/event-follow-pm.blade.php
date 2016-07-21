<div id="follow_pm_dialog" class="ui-widget" title="Follow up PM" style="display:none">
    <table class="table table-striped">
        <tr>
            <td>
                <select id="follow_pm" title="Follow up PM">
                    <option value="">Select one PM</option>
                    @foreach ($admins as $admin)
                        <option value="{{ $admin->name }}">{{ $admin->name }}</option>
                    @endforeach
                </select>
            </td>
        </tr>
        <tr>
            <td><button id="edit_follow_pm" class="btn btn-default">Edit</button></td>
        </tr>
    </table>
    <input type="hidden" id="id" value="">
</div>
