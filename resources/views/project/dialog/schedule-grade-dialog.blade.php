<div id="grade_dialog" class="ui-widget" title="Update Project Grade" style="display:none">
    <table class="table table-striped">
        <tr>
            <td>
                <select id="grade" title="note grade">
                    <option value="not-graded">Not graded</option>
                    <option value="pending">Pending</option>
                    <option value="A">Grade A</option>
                    <option value="B">Grade B</option>
                    <option value="C">Grade C</option>
                    <option value="D">Grade D</option>
                </select>
            </td>
        </tr>
        <tr>
            <td><textarea id="grade_note" rows="4" cols="50" title="grade message"></textarea></td>
        </tr>
        <tr>
            <td><button id="edit_grade" class="btn btn-default">Edit Grade</button></td>
        </tr>
    </table>
    <input type="hidden" id="grade_project_id" value="">
</div>
