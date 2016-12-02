<?php

namespace Backend\Http\Controllers;

use Backend\Api\ApiInterfaces\AuthApi\OAuthApiInterface;
use Backend\Assistant\ApiResponse\OAuthResponseAssistant;
use Backend\Enums\API\Response\Key\OAuthKey;
use Backend\Facades\Log;
use Backend\Repo\RepoInterfaces\AdminerInterface;
use Backend\Repo\RepoInterfaces\UserInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Noty;

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

        $cert = [
            'email'    => $email,
            'password' => $password
        ];

        if (!auth()->attempt($cert)) {
            return $this->loginFail($email);
        }

        // OAuth client_credentials authorization
        $response = $this->oauth_api->clientCredentials();

        if (!$response->isOk()) {
            auth()->logout();

            return $this->loginFail($email);
        }

        return $this->loginSuccess($response);
    }

    /**
     * OAuth login
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function oauthLogin(Request $request)
    {
        $email    = $request->get('email');
        $password = $request->get('password');

        // Check backend user has bind hwtrek user
        $user = $this->user_repo->byMail($email);

        if (empty($user)) {
            return $this->loginFail($email);
        }

        $adminer = $this->admin_repo->findHWTrekMember($user->id());

        if (empty($adminer)) {
            return $this->loginFail($email);
        }

        // OAuth password authorization
        $response = $this->oauth_api->password($email, $password);
        
        if (!$response->isOk()) {
            auth()->logout();
            return $this->loginFail($email);
        }

        auth()->loginUsingId($adminer->id());

        if (auth()->check() === false) {
            return $this->loginFail($email);
        }

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

        session()->clear();

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
        session()->put(OAuthKey::ACCESS_TOKEN, $oauth_assistant->getAccessToken());
        session()->put(OAuthKey::TOKEN_TYPE, $oauth_assistant->getTokenType());

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
            Noty::warn(trans()->trans('oauth.source-server-aberrant'));
        } else {
            Noty::warn('Login fail.');
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
