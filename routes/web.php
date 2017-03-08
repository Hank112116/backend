<?php

Route::get('/', 'HomeController@index');

Route::post('login', 'AuthController@login')->middleware(['throttle:10,1']);

Route::get('logout', 'AuthController@logout');

Route::group(['middleware' => 'route_filter:adminer'], function () {
    Route::get('adminer/all', 'AdminerController@showList');

    Route::get('adminer/create', 'AdminerController@showCreate');
    Route::get('adminer/update/{id}', 'AdminerController@showUpdate');

    Route::get('adminer/role/create', 'AdminerController@showRoleCreate');
    Route::get('adminer/role/upeate/{id}', 'AdminerController@showRoleUpdate');

    Route::get('adminer/delete/{id}', 'AdminerController@delete');
    Route::get('adminer/role/delete/{id}', 'AdminerController@roleDelete');

    Route::post('adminer/create', 'AdminerController@create');
    Route::post('adminer/update/{id}', 'AdminerController@update');
    Route::post('adminer/role/create', 'AdminerController@roleCreate');
    Route::post('adminer/role/update/{id}', 'AdminerController@roleUpdate');
});

Route::group(['middleware' => ['route_filter:user', 'check_source_server', 'api_auth']], function () {

    Route::get('user/all', 'UserController@showList');
    Route::get('user/search', 'UserController@showSearch');
    Route::get('user/detail/{id}', 'UserController@showDetail');
    Route::get('user/update/{id}/param/{param}', 'UserController@showUpdate');
    Route::get('user/update/{id}', 'UserController@showUpdate');


    Route::get('user/api/search/{id}', 'UserController@searchUser');

    Route::get('user/comments', 'CommentController@showProfession');
    Route::get('user/comments/professions', 'CommentController@professions');
    Route::get('user/comments/search', 'CommentController@searchProfessions');

    Route::get('user/comments/professions/delete/{comment_id}', 'CommentController@delete');
    Route::get('user/comments/professions/private/{comment_id}', 'CommentController@togglePrivate');

    Route::post('user/disable', 'UserController@disable');
    Route::post('user/enable', 'UserController@enable');
    Route::post('user/update/{id}', 'UserController@update');
    Route::post('user/change-hwtrek-pm-type', 'UserController@changeUserType');
    Route::post('user/update-memo', 'UserController@updateMemo');
    Route::post('user/put-attachment', 'UserController@putAttachment');
});

// Project
Route::group(['middleware' => ['route_filter:project', 'check_source_server', 'api_auth']], function () {

    Route::get('project/all', 'ProjectController@showList');

    Route::get('project/search', 'ProjectController@showSearch');

    Route::get('project/detail/{id}', 'ProjectController@showDetail');
    Route::get('project/update/{id}', 'ProjectController@showUpdate');
    Route::get('project/delete/{id}', 'ProjectController@delete');

    Route::get('project/update-status/{status}/{project_id}', 'ProjectController@updateStatus');

    Route::get('project/comments', 'CommentController@showProject');
    Route::get('project/comments/projects', 'CommentController@projects');
    Route::get('project/comments/search', 'CommentController@searchProjects');

    Route::get('project/comments/projects/delete/{comment_id}', 'CommentController@delete');
    Route::get('project/comments/projects/private/{comment_id}', 'CommentController@togglePrivate');

    Route::post('project/update/{id}', 'ProjectController@update');
    Route::post('project/update-memo', 'ProjectController@updateMemo');
    Route::post('project/update-project-manager', 'ProjectController@updateManager');
    Route::post('project/approve-schedule', 'ProjectController@approveSchedule');
    Route::post('project/get-expert', 'ProjectController@getExpert');
});

// Solution
Route::group(['middleware' => ['route_filter:solution', 'check_source_server', 'api_auth']], function () {
    Route::get('solution', 'SolutionController@showList');

    Route::get('solution/detail/{id}', 'SolutionController@showDetail');
    Route::get('solution/update/{id}', 'SolutionController@showUpdate');

    Route::post('solution/approve/{id}', 'SolutionController@approve');
    Route::post('solution/reject/{id}', 'SolutionController@reject');

    Route::get('solution/on-shelf/{id}', 'SolutionController@onShelf');
    Route::get('solution/off-shelf/{id}', 'SolutionController@offShelf');

    Route::get('solution/comments', 'CommentController@showSolution');
    Route::get('solution/comments/solutions', 'CommentController@solutions');
    Route::get('solution/comments/search', 'CommentController@searchSolutions');

    Route::get('solution/comments/solutions/delete/{comment_id}', 'CommentController@delete');
    Route::get('solution/comments/solutions/private/{comment_id}', 'CommentController@togglePrivate');

    Route::post('solution/update/{id}', 'SolutionController@update');
    Route::post('solution/to-program', 'SolutionController@toProgram');
    Route::post('solution/to-solution', 'SolutionController@toSolution');
    Route::post('solution/cancel-pending-program', 'SolutionController@cancelPendingProgram');
    Route::post('solution/cancel-pending-solution', 'SolutionController@cancelPendingSolution');
});

// Landing
Route::group(['middleware' => 'route_filter:marketing'], function () {
    Route::get('landing/feature', 'LandingController@showFeature');
    Route::get('landing/hello', 'LandingController@showHello');
    Route::get('landing/low-priority', 'LandingController@showRestricted');

    Route::post('landing/find-feature/{type}', 'LandingController@findFeatureEntity');
    Route::post('landing/find-refer-project', 'LandingController@findReferenceProject');
    Route::post('landing/update-feature', 'LandingController@updateFeature');
    Route::post('landing/update-refer', 'LandingController@updateReferenceProject');
    Route::post('landing/update-hello-redirect', 'LandingController@updateHelloRedirect');
    Route::post('landing/add-object/{type}', 'LandingController@addRestrictedObject');
    Route::post('landing/remove-restricted-object', 'LandingController@removeRestrictedObject');
});

// Report
Route::group([ 'middleware' => 'route_filter:report_full|registration_report' ], function () {
    Route::get('report/registration', 'ReportController@showRegistrationReport');
});
Route::group([ 'middleware' => 'route_filter:report_full|comment_report' ], function () {
    Route::get('report/comment', 'ReportController@showCommentReport');
});
Route::group([ 'middleware' => 'route_filter:report_full|member_matching_report' ], function () {
    Route::get('report/member-matching', 'ReportController@showMemberMatchingReport');
    Route::post('report/matching-data', 'ReportController@showMatchingDate');
});
Route::group([ 'middleware' => 'route_filter:report_full|event_report' ], function () {
    Route::get('report/events', 'ReportController@showEventReport');
    Route::get('report/events/{event_id}', 'ReportController@showEventReport');
    Route::get('report/tour-form', 'ReportController@showQuestionnaire');
    Route::post('report/events/update-memo', 'ReportController@updateEventMemo');
    Route::post('report/events/approve-user', 'ReportController@approveEventUser');
    Route::post('report/events/user-questionnaire', 'ReportController@showUserQuestionnaire');
});

Route::post('/upload-editor-image', 'ImageUploadController@index');
Route::post('/hub_email-send', 'EmailSendController@hubMailSend');

Route::post('apply-expert-message/messages', 'ApplyExpertMessageController@showMessages');
