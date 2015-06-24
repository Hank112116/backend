<div id="dialog" class="ui-widget" title="Send mail" style="display:none">
    <table class="table table-striped">
        <tr>
            <td>Expert</td>
            <td>
                <input type="number" id="expert1" placeholder="Expert Id">            
            </td>
            <td class="table--text-left">
                <span id="expert1Info"></span>
            </td>
        </tr>
        <tr>
            <td>Expert</td>
            <td>
                <input type="number" id="expert2" placeholder="Expert Id">
            </td>
            <td class="table--text-left">
                <span id="expert2Info"></span>
            </td>
        </tr>

    </table>
    <input type="hidden" id="projectId">
    <input type="hidden" id="projectTitle">
    <input type="hidden" id="userId">
    <input type="hidden" id="PM">
    <button id="sendMail">SendMail</button>
</div>