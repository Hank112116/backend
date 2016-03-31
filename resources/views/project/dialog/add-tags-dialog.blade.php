<div id="internal-tag-dialog" class="ui-widget" title="Tag" style="display:none">
    <table class="table table-striped">
        <tr>
            <td class="col-md-4"><label for="tech-tag">Tech tag</label></td>
            <td>
                <span id="tech-tag"></span>
            </td>
        </tr>
        <tr>
            <td class="col-md-4"><label for="internal-tag">internal tag</label></td>
            <td>
                <div>
                    <input type="text" id="internal-tag" name="internal-tag" size="35"
                           placeholder="Enter 'tag' then press [Enter]"
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
