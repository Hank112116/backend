<div class="panel panel-default">
    <div class="panel-heading">Attachments</div>
    <div class="panel-body">
        <table class="table table-striped">
            <tr>
                <th>Name</th>
                <th>Size</th>
                <th>Update Time</th>
            </tr>
            @if ($project->projectAttachments()->count() > 0)
                @foreach($project->projectAttachments()->getResults() as $project_attachment)
                    <tr>
                        <td>{{ $project_attachment->attachment->name }}</td>
                        <td>{{ ToolFunction::formatSizeUnits($project_attachment->attachment->size) }}</td>
                        <td>{{ $project_attachment->attachment->updated_at }}</td>
                    </tr>
                @endforeach
            @endif
        </table>
    </div>
</div>
