<div id="schedule-manager-dialog" class="ui-widget" title="Schedule Manager " style="display:none">
        <div class="panel-body">

            <div>
                {{--<h4>View Questionnaire & Manage Schedule</h4>--}}
                @foreach($adminers as $ad)
                    @if($ad->hwtrek_member)
                        @if($ad->role->isFrontendPM() or $ad->role->isBackendPM())
                            <div class="adminer-tag">
                                <input id="hwtrek_member_{{$ad->hwtrek_member}}" type='checkbox' name="managers[]" value='{!! $ad->hwtrek_member !!}' !!}/>
                                <label for="hwtrek_member_{{$ad->hwtrek_member}}">
                                    {!! $ad->name() !!}
                                    @if($ad->role->isFrontendPM())
                                        (Frontend)
                                    @elseif($ad->role->isBackendPM())
                                        (Backend)
                                    @endif
                                </label>
                            </div>
                        @endif
                    @endif
                @endforeach
            </div>
            <div class="form-group">
                <button class="btn-sassy btn-submit" id="update-schedule-manager">UPDATE</button>
            </div>
            <input type="hidden" id="schedule_manager_project_id" value=""/>
        </div>
</div>
