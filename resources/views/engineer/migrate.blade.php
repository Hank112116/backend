@extends('layouts.master')

@section('css')
    <link rel="stylesheet" href="/css/engineer-index.css">
@stop

@section('content')

<div class="page-header">
    <h1>Migrate</h1>
    <h2>做之前先備份一下</h2>
</div>


<div>
    <h2>1. 建立 project_tag table 資料</h2>
    <a class='btn btn-mini btn-flat-green' href="/engineer/project-tag">Build</a>
</div>

<div>
    <h2><br/>2. Map solution/project 'tags' column old_id to new_id</h2>
    <p>要先做完 HWTrek_restructure_DB_schema_update #2014/09/30 的 Migrate</p>
    <a class='btn btn-mini btn-flat-green' href="/engineer/migrate-solution-project-tags">Migrate</a>
</div>

<div>
    <h2><br/>3. Map questionnaire tag-relate column old_id to new_id</h2>
    <a class='btn btn-mini btn-flat-green' href="/engineer/migrate-questionnaire-tags">Migrate</a>
</div>

<div>
    <h2><br/>4.
        If project is_project_submitted == 1 and <br/>
        project_submit_time == '0000-00-00 00:00:00'<br/>
        Update project_submit_time = date_added
    </h2>
    <a class='btn btn-mini btn-flat-green' href="/engineer/update-project-submit-date">Set</a>
</div>


@stop
