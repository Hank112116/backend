<div id="internal-tag-dialog" class="ui-widget" title="Feature Tags" style="display:none">
    <table class="table table-striped">
        <tr>
            <td class="col-md-4"><label for="tech-tag">Project Tags</label></td>
            <td>
                <span id="tech-tag"></span>
            </td>
        </tr>
        <tr>
            <td class="col-md-4"><label for="internal-tag">Internal Tags</label></td>
            <td>
                <div>
                    <input type="text" id="internal-tag" name="internal-tag" size="35"
                           placeholder="Press [Enter] after adding tags, separate tags by comma"
                           value="" />
                </div>
            </td>
        </tr>
        <tr>
           <td>
               <button id="add-tags" class="btn btn-default">Add</button>
           </td>
           <td></td>
        </tr>
    </table>
    <input type="hidden" id="internal_tag_project_id" value=""/>
</div>
