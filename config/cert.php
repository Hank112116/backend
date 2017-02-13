<?php

/**
 * Certification types for roles
 * Refer scope document for detail
 **/

return [
    'all'             => [
        'Adminer'   => ['adminer' => 'Adminer Full'],
        'Member'    => ['user' => 'User Full', 'user_restricted' => 'User Limited', 'user_edit_restricted' => 'User Edit Limited'],
        'Project'   => ['project' => 'Project Full', 'schedule_manager' => 'Schedule Manager'],
        'Solution'  => ['solution' => 'Solution Full', 'solution_restricted' => 'Solution Limited'],
        'Marketing' => ['marketing_full' => 'Marketing Full' , 'marketing_expert_list' => 'Home Expert List'],
        'Report'    => ['report_full' => 'Report Full', 'registration_report' => 'Registration Report','member_matching_report' => 'Member Matching Report', 'comment_report' => 'Comment Report', 'event_report' => 'Event Report'],
    ],
    'default_admin'   => [
        'adminer', 'user', 'project', 'solution', 'front_page', 'marketing',
    ],
    'default_manager' => [
        'user_restricted', 'solution_restricted',
    ]
];
