<?php

namespace Backend\Http\Controllers;

use Backend\Api\ApiInterfaces\AuthApi\OAuthApiInterface;
use Backend\Assistant\ApiResponse\OAuthResponseAssistant;
use Backend\Enums\API\Response\Key\OAuthKey;
use Backend\Repo\RepoInterfaces\AdminerInterface;
use Backend\Repo\RepoInterfaces\UserInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Log;
use Noty;
use Cache;

class AuthController extends BaseController
{
    private $user_repo;
    private $admin_repo;
    private $oauth_api;

    public function __construct(
        UserInterface $user_repo,
        AdminerInterface $admin_repo,
        OAuthApiInterface $oauth_api
    ) {
        parent::__construct();

        $this->user_repo  = $user_repo;
        $this->admin_repo = $admin_repo;
        $this->oauth_api  = $oauth_api;
    }

    /**
     * General login
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function login(Request $request)
    {
        $email    = $request->get('email');
        $password = $request->get('password');

        $adminer = $this->admin_repo->findByEmail($email);

        session()->flash('login_oauth_email', $email);

        if (empty($adminer)) {
            session()->flash('login_error_msg', trans()->trans('oauth.account-not-exist'));
            return $this->loginFail($email);
        }

        if ($adminer->hasHWTrekMember()) {
            // OAuth password authorization
            $response = $this->oauth_api->password($email, $password);

            if (!$response->isOk()) {
                session()->flash('login_error_msg', trans()->trans('oauth.oauth-error'));
                session()->flash('login_password_error', true);

                return $this->loginFail($email);
            }

            auth()->loginUsingId($adminer->id());
        } else {
            // OAuth client_credentials authorization
            $response = $this->oauth_api->clientCredentials();

            if (!$response->isOk()) {
                session()->flash('login_error_msg', trans()->trans('oauth.login-fail'));

                return $this->loginFail($email);
            }

            $cert = [
                'email'    => $email,
                'password' => $password
            ];

            if (!auth()->guard('web')->attempt($cert)) {
                session()->flash('login_error_msg', trans()->trans('oauth.login-fail'));
                session()->flash('login_password_error', true);

                return $this->loginFail($email);
            }
        }
        $adminer->handleDuplicateLoginSession();

        return $this->loginSuccess($response);
    }

    /**
     * Logout
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout()
    {
        auth()->logout();

        session()->flush();

        Cache::flush();

        Noty::success('Logout Success');

        return redirect('/');
    }

    /**
     * Handel login success
     *
     * @param Response $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    private function loginSuccess(Response $response)
    {
        $user = auth()->user();

        $oauth_assistant = OAuthResponseAssistant::create($response);

        session()->put('cert', $user->role->cert);
        session()->put('admin', $user->id);

        Cache::put(OAuthKey::ACCESS_TOKEN, $oauth_assistant->getAccessToken(), config('api.ttl'));
        Cache::put(OAuthKey::TOKEN_TYPE, $oauth_assistant->getTokenType(), config('api.ttl'));

        Noty::success('Welcome, ' . $user->name . ". Login success");

        $log_action = 'Log in';
        $log_data   = [
            'email'   => $user->email,
            'success' => true
        ];
        Log::info($log_action, $log_data);

        return redirect('/');
    }

    /**
     * Handel login fail
     *
     * @param string $email
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    private function loginFail($email)
    {
        if (session(OAuthKey::API_SERVER_STATUS) === 'stop') {
            session()->flash('login_error_msg', trans()->trans('oauth.source-server-aberrant'));
        }

        $log_action = 'Log in';
        $log_data   = [
            'email'   => $email,
            'success' => false
        ];
        Log::info($log_action, $log_data);

        return redirect('/');
    }
}
