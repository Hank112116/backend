<?php

use Backend\Model\Eloquent;
use Carbon\Carbon;

$factory(Eloquent\User::class, [
    'user_name'  => $faker->firstName,
    'last_name'  => $faker->lastName,
    'email'      => $faker->email,
    'date_added' => Carbon::now()->toDateTimeString()
]);

$factory(Eloquent\Project::class, [
    'user_id'       => 1,
    'project_title' => $faker->sentence,
    'description'   => $faker->paragraph,
    'choose_type'   => Eloquent\Project::CHOOSE_TYPE_PROJECT,
    'update_time'   => Carbon::now()->toDateTimeString()
]);

$factory(Eloquent\Project::class, 'Product', [
    'user_id'       => 1,
    'project_title' => $faker->sentence,
    'description'   => $faker->paragraph,
    'choose_type'   => Eloquent\Project::CHOOSE_TYPE_PRODUCT,
    'update_time'   => Carbon::now()->toDateTimeString()
]);

$factory(
    Eloquent\DuplicateProject::class, 'DuplicateProduct', [
    'user_id'       => 1,
    'project_title' => $faker->sentence,
    'description'   => $faker->paragraph,
    'choose_type'   => Eloquent\Project::CHOOSE_TYPE_PRODUCT
]);

$factory(Eloquent\Perk::class, [
    'project_id' => 1,
    'perk_title' => $faker->sentence,
    'perk_get'   => 0
]);

$factory(Eloquent\DuplicatePerk::class, [
    'perk_id'    => $faker->numerify("###"),
    'project_id' => 1,
    'perk_title' => $faker->sentence,
    'perk_get'   => 0
]);

$factory(Eloquent\Solution::class, [
    'user_id'        => 1,
    'solution_title' => $faker->sentence,
    'description'    => $faker->paragraph,
    'update_time'    => Carbon::now()->toDateTimeString()
]);

$factory(Eloquent\DuplicateSolution::class, [
    'user_id'        => 1,
    'solution_id'    => $faker->numerify("###"),
    'solution_title' => $faker->sentence,
    'description'    => $faker->paragraph
]);

$factory(Eloquent\Comment::class, [
    'title'        => $faker->sentence,
    'comments'     => $faker->paragraph,
    'main_comment' => 0
]);

$factory(Eloquent\Comment::class, 'Thread', [
    'title'        => $faker->sentence,
    'comments'     => $faker->paragraph,
    'main_comment' => 1
]);

$factory(Eloquent\Transaction::class, [
    'user_id'               => 1,
    'project_id'            => 1,
    'perk_id'               => 1,
    'transaction_date_time' => Carbon::now()
]);

$factory(Eloquent\MailTemplate::class, [
    'task'    => $faker->sentence,
    'message' => $faker->paragraph,
    'active'  => 1,
]);
