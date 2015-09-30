<?php

Route::get('/', 'HomeController@index');

Route::post('login', 'AuthController@login');
Route::get('logout', 'AuthController@logout');

Route::group(['before' => 'backend.adminer'], function () {
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

Route::group(['before' => 'backend.user'], function () {

    Route::get('user/all', 'UserController@showList');
    Route::get('user/all-expert', 'UserController@showExperts');
    Route::get('user/all-creator', 'UserController@showCreators');
    Route::get('user/to-be-expert', 'UserController@showToBeExperts');
    Route::get('user/search/{by}', 'UserController@showSearch');
    Route::get('user/detail/{id}', 'UserController@showDetail');
    Route::get('user/update/{id}', 'UserController@showUpdate');

    Route::get('user/api/search/{id}', 'UserController@searchUser');

    Route::get('user/comments', 'CommentController@showProfession');
    Route::get('user/comments/professions', 'CommentController@professions');
    Route::get('user/comments/search', 'CommentController@searchProfessions');

    Route::get('user/comments/professions/delete/{comment_id}', 'CommentController@delete');
    Route::get('user/comments/professions/private/{comment_id}', 'CommentController@togglePrivate');

    Route::post('user/update/{id}', 'UserController@update');
    Route::post('user/change-hwtrek-pm-type', 'UserController@changeHWTrekPM');
});

// Inbox
Route::group(['before' => 'backend.user'], function () {

    Route::get('inbox', 'InboxController@index');
    Route::get('inbox/topics', 'InboxController@topics');
    Route::get('inbox/search', 'InboxController@search');

    Route::post('inbox/delete/{message_id}', 'InboxController@delete');

});

// Project
Route::group(['before' => 'backend.project'], function () {

    Route::get('project/all', 'ProjectController@showList');
    Route::get('project/deleted', 'ProjectController@showDeletedProjects');

    Route::get('project/search/{by}', 'ProjectController@showSearch');

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
});

// Product
Route::group(['before' => 'backend.project'], function () {

    Route::get('product/all', 'ProductController@showList');
    Route::get('product/wait-approve', 'ProductController@showWaitApproves');
    Route::get('product/search/{by}', 'ProductController@showSearch');

    Route::get('product/detail/{id}', 'ProductController@showDetail');
    Route::get('product/project-detail/{id}', 'ProductController@showProjectDetail');

    Route::get('product/update/{id}', 'ProductController@showUpdate');
    Route::get('product/project-update/{id}', 'ProductController@showProjectUpdate');

    Route::get('product/new-perk/{is_pro}', 'ProductController@renderNewPerk');

    Route::get('product/postpone/{id}', 'ProductController@postpone');
    Route::get('product/recover-postpone/{id}', 'ProductController@recoverPostpone');

    Route::get('product/approve/{id}', 'ProductController@approve');
    Route::get('product/reject/{id}', 'ProductController@reject');
    Route::post('product/update/{id}', 'ProductController@update');

    Route::post('product/update-ongoing/{id}', 'ProductController@updateOngoing');
    Route::post('product/update-project/{id}', 'ProductController@updateProject');
});

// Transaction
Route::group(['before' => 'backend.project'], function () {
    Route::get('transaction/all', 'TransactionController@showList');
    Route::get('transaction/search/{by}', 'TransactionController@showSearch');
});

// Solution
Route::group(['before' => 'backend.solution'], function () {
    Route::get('solution/all', 'SolutionController@showList');
    Route::get('solution/drafts', 'SolutionController@showDraftSolutions');
    Route::get('solution/wait-approve', 'SolutionController@showWaitApproveSolutions');
    Route::get('solution/deleted', 'SolutionController@showDeletedSolutions');
    Route::get('solution/program', 'SolutionController@showProgram');
    Route::get('solution/pending-solution', 'SolutionController@showPendingSolution');
    Route::get('solution/pending-program', 'SolutionController@showPendingProgram');

    Route::get('solution/search/{by}', 'SolutionController@showSearch');
    Route::get('solution/detail/{id}', 'SolutionController@showDetail');
    Route::get('solution/update/{id}', 'SolutionController@showUpdate');

    Route::get('solution/approve/{id}', 'SolutionController@approve');
    Route::get('solution/reject/{id}', 'SolutionController@reject');

    Route::get('solution/approve-edition/{id}', 'SolutionController@approveEdition');
    Route::get('solution/reject-edition/{id}', 'SolutionController@rejectEdition');

    Route::get('solution/on-shelf/{id}', 'SolutionController@onShelf');
    Route::get('solution/off-shelf/{id}', 'SolutionController@offShelf');

    Route::get('solution/comments', 'CommentController@showSolution');
    Route::get('solution/comments/solutions', 'CommentController@solutions');
    Route::get('solution/comments/search', 'CommentController@searchSolutions');

    Route::get('solution/comments/solutions/delete/{comment_id}', 'CommentController@delete');
    Route::get('solution/comments/solutions/private/{comment_id}', 'CommentController@togglePrivate');

    Route::post('solution/update/{id}', 'SolutionController@update');
    Route::post('solution/update-ongoing/{id}', 'SolutionController@updateOngoing');
    Route::post('solution/to-program', 'SolutionController@toProgram');
    Route::post('solution/to-solution', 'SolutionController@toSolution');
    Route::post('solution/cancel-pending-program', 'SolutionController@cancelPendingProgram');
    Route::post('solution/cancel-pending-solution', 'SolutionController@cancelPendingSolution');

});

// Hub
Route::group(['before' => 'backend.hub'], function () {
    Route::get('hub/questionnaires', 'HubController@indexQuestionnaire');
    Route::get('hub/schedules', 'HubController@indexSchedule');
    Route::get('hub/questionnaires/detail/{id}', 'HubController@showQuestionnaireDetail');
    Route::get('hub/schedule-manager/{id}', 'HubController@showUpdateScheduleManager');
    Route::get('hub/questionnaires/approve/{project_id}', 'HubController@approveSchedule');

    Route::post('hub/update-schedule-manager/{id}', 'HubController@updateScheduleManager');
    Route::post('hub/update-project-note', 'HubController@updateProjectNote');
    Route::post('hub/get-expert', 'HubController@getExpert');
});

// Landing
Route::group(['before' => 'backend.landing'], function () {
    Route::get('landing/feature', 'LandingController@showFeature');
    Route::get('landing/hello', 'LandingController@showHello');
    Route::get('landing/expert', 'LandingController@showExpert');

    Route::post('landing/find-feature/{tyep}', 'LandingController@findFeatureEntity');
    Route::post('landing/find-feature/{tyep}', 'LandingController@findFeatureEntity');
    Route::post('landing/find-feature/{tyep}', 'LandingController@findFeatureEntity');
    Route::post('landing/find-refer-project', 'LandingController@findReferenceProject');
    Route::post('landing/update-feature', 'LandingController@updateFeature');
    Route::post('landing/update-refer', 'LandingController@updateReferenceProject');
    Route::post('landing/update-hello-redirect', 'LandingController@updateHelloRedirect');
    Route::post('landing/find-expert/{tyep}', 'LandingController@findExpertEntity');
    Route::post('landing/update-expert', 'LandingController@updateExpert');
});

// Email Template
Route::group(['before' => 'backend.mail'], function () {
    Route::get('mail/all', 'MailTemplateController@showList');
    Route::get('mail/disactive', 'MailTemplateController@showDisactiveList');
    Route::get('mail/detail/{id}', 'MailTemplateController@showDetail');
    Route::get('mail/create', 'MailTemplateController@showCreate');
    Route::get('mail/update/{id}', 'MailTemplateController@showUpdate');
    Route::get('mail/trigger-active/{id}', 'MailTemplateController@triggerActive');
    Route::get('mail/template', 'MailTemplateController@fetchHtmlTemplate');

    Route::post('mail/create', 'MailTemplateController@create');
    Route::post('mail/update/{id}', 'MailTemplateController@update');
});

// Report
Route::group([ 'before' => 'backend.reportRegistration' ], function () {
    Route::get('report/registration', 'ReportController@showRegistrationReport');
});
Route::group([ 'before' => 'backend.reportComment' ], function () {
    Route::get('report/comment', 'ReportController@showCommentReport');
});


// Engineer
Route::group(['before' => 'backend.login'], function () {
    Route::get('engineer/bug', 'EngineerController@bug');
    Route::post('engineer/bug-decode', 'EngineerController@bugDecode');
});

Route::post('/upload-editor-image', 'ImageUploadController@index');
Route::post('/hub_email-send', 'EmailSendController@hubMailSend');

get('add-a-log', function () {
    Log::info("Hello World");
});
