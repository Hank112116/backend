<div id="edit-feature-dialog" class="ui-widget" style="display:none">
    <table class="table table-striped">
        <tr>
            <td>
                <select id="object_type" title="Object Type" class="form-control">
                    <option value="">Select one Object type</option>
                    <option value="expert">Expert</option>
                    <option value="project">Project</option>
                    <option value="solution">Solution</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <input type="number" class="form-control" name="object_id" id="object_id" placeholder="Object Id">
            </td>
        </tr>
        <tr>
            <td><button id="edit-feature" class="btn btn-default">Edit</button></td>
        </tr>
    </table>
    <input type="hidden" id="block_id" value="">
</div>
