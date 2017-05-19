<?php
namespace Backend\Assistant;

use Backend\Enums\API\Response\Key\ProjectKey;
use Backend\Enums\API\Response\Key\SolutionKey;
use Illuminate\Http\Request;

class SearchAssistant
{
    /**
     * @param Request $request
     *
     * @return array
     */
    public static function projectSearchQuery(Request $request)
    {
        $query = [
            ProjectKey::KEY_PAGE  => $request->get('page', 1),
            ProjectKey::KEY_LIMIT => $request->get('pp', 100),
        ];

        if (!empty($request->get('project_id'))) {
            $query[ProjectKey::KEY_PROJECT_ID]  = $request->get('project_id');
        }

        if (!empty($request->get('user_name'))) {
            $query[ProjectKey::KEY_OWNER]  = $request->get('user_name');
        }

        if (!empty($request->get('project_title'))) {
            $query[ProjectKey::KEY_TITLE]  = $request->get('project_title');
        }

        if (!empty($request->get('assigned_pm'))) {
            $query[ProjectKey::KEY_ASSIGNED_PM]  = $request->get('assigned_pm');
        }

        if (!empty($request->get('description'))) {
            $query[ProjectKey::SEARCH_MEMO_DESCRIPTION]  = $request->get('description');
        }

        if (!empty($request->get('country'))) {
            $query[ProjectKey::KEY_COUNTRY]  = $request->get('country');
        }

        if (!empty($request->get('tag'))) {
            $query[ProjectKey::SEARCH_FEATURE_TAG]  = $request->get('tag');
        }

        if (!empty($request->get('report_action'))) {
            $query[ProjectKey::SEARCH_MEMO_ACTION]  = $request->get('report_action');
        }

        if ($request->get('status') !== 'all') {
            $query[ProjectKey::KEY_STATUS] = $request->get('status');
        }

        switch ($request->get('time_type')) {
            case 'update':
                if (!empty($request->get('dstart'))) {
                    $query[ProjectKey::SEARCH_UPDATE_START_TIME] = $request->get('dstart');
                }

                if (!empty($request->get('dend'))) {
                    $query[ProjectKey::SEARCH_UPDATE_END_TIME] = $request->get('dend');
                }
                break;
            case 'create':
                if (!empty($request->get('dstart'))) {
                    $query[ProjectKey::SEARCH_CREATED_START_TIME] = $request->get('dstart');
                }

                if (!empty($request->get('dend'))) {
                    $query[ProjectKey::SEARCH_CREATED_END_TIME] = $request->get('dend');
                }
                break;
            case 'release':
                if (!empty($request->get('dstart'))) {
                    $query[ProjectKey::SEARCH_EMAIL_OUT_START_TIME] = $request->get('dstart');
                }

                if (!empty($request->get('dend'))) {
                    $query[ProjectKey::SEARCH_EMAIL_OUT_END_TIME] = $request->get('dend');
                }
                break;
            default:
                if (!empty($request->get('dstart'))) {
                    $query[ProjectKey::SEARCH_UPDATE_START_TIME] = $request->get('dstart');
                }

                if (!empty($request->get('dend'))) {
                    $query[ProjectKey::SEARCH_UPDATE_END_TIME] = $request->get('dend');
                }
        }

        return $query;
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public static function solutionSearchQuery(Request $request)
    {
        $query = [
            SolutionKey::KEY_PAGE  => $request->get('page', 1),
            SolutionKey::KEY_LIMIT => $request->get('pp', 100),
        ];

        if (!empty($request->get('solution_id'))) {
            $query[SolutionKey::KEY_SOLUTION_ID]  = $request->get('solution_id');
        }

        if (!empty($request->get('user_name'))) {
            $query[SolutionKey::KEY_OWNER]  = $request->get('user_name');
        }

        if (!empty($request->get('solution_title'))) {
            $query[SolutionKey::KEY_TITLE]  = $request->get('solution_title');
        }

        if (!empty($request->get('dstart'))) {
            $query[SolutionKey::KEY_APPROVED_START_TIME]  = $request->get('dstart');
        }

        if (!empty($request->get('dend'))) {
            $query[SolutionKey::KEY_APPROVED_END_TIME]  = $request->get('dend');
        }

        if ($request->get('status') !== 'all') {
            $query[ProjectKey::KEY_STATUS] = $request->get('status');
        }

        return $query;
    }
}
