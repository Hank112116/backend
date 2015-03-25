@extends('layouts.master')

@section('css')
	@cssLoader('mail-template-list')
@stop

@section('content')
<div class="page-header">
    <h1>{!! $is_show_active? '' : 'DISACTIVE' !!} EMAIL TEMPLATES</h1>
    <div class='header-output'>
        @if($is_show_active)
        {!! link_to_action('MailTemplateController@showDisactiveList', 
                          'Archived Emails', '',['class' => 'btn-mini header-output-link']) !!}
        @else
        {!! link_to_action('MailTemplateController@showList', 
                          'Active Emails', '',['class' => 'btn-mini header-output-link']) !!}        
        @endif

        {!! link_to_action('MailTemplateController@showCreate', 
                          'NEW TEMPLATE', '',['class' => 'btn-mini header-output-link']) !!}
    </div>
</div>

<div class="row">
<div class="col-md-12">

  <table class="table table-striped">
    <tr>
      <th>#</th>
      <th>Task</th>
      <th>From Address</th>
      <th>Reply Address</th>
      <th>Subject</th>
      <th></th>
    </tr>

    @foreach($mails as $e)
      <tr>
        <td>{!! $e->email_template_id !!}</td>
        <td>{!! $e->task !!}</td>
        <td>{!! $e->from_address !!}</td>
        <td>{!! $e->reply_address !!}</td>
        <td>{!! $e->subject !!}</td>
        <td>
          @if($is_show_active)
          {!! link_to_action( 'MailTemplateController@showUpdate', 'EDIT', 
                             $e->email_template_id, ['class' => 'btn-mini']) !!}
          {!! link_to_action( 'MailTemplateController@triggerActive', 'DEACTIVE', 
                             $e->email_template_id, ['class' => 'btn-mini']) !!}
          @else
          {!! link_to_action( 'MailTemplateController@showDetail', 'DETAIL', 
                             $e->email_template_id, ['class' => 'btn-mini']) !!}
          {!! link_to_action( 'MailTemplateController@triggerActive', 'ACTIVE', 
                             $e->email_template_id, ['class' => 'btn-mini']) !!}
          @endif
        </td>
      </tr>
    @endforeach
  </table>

</div>
</div>


@stop

