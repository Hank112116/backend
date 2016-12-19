<?php namespace Backend\Providers;

use Backend\Enums\API\Response\Key\OAuthKey;
use Backend\Facades\Log;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client;

/**
 * Class GuzzleServiceProvider
 *
 * @author HankChang <hank.chang@hwtrek.com>
 */
class GuzzleServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('GuzzleHttp\Client', function () {
            $config = config('api.client_config');

            // Set Authorization header
            if (session()->has(OAuthKey::TOKEN_TYPE) and session()->has(OAuthKey::ACCESS_TOKEN)) {
                $authorization = session(OAuthKey::TOKEN_TYPE) . ' ' . session(OAuthKey::ACCESS_TOKEN);
                $config['headers']['Authorization'] = $authorization;
            }

            // If has session cookies, generate cookies jar.
            if (session()->has(OAuthKey::HWTREK_SESSION_COOKIES)) {
                $jar = new CookieJar();

                $cookies = session()->get(OAuthKey::HWTREK_SESSION_COOKIES);

                foreach ($cookies as $cookie) {
                    $set_cookie = SetCookie::fromString($cookie);

                    $now_time   = new \DateTime();

                    $set_cookie->setExpires($now_time->add(new \DateInterval('PT6H'))->getTimestamp());
                    $set_cookie->setDomain(config('app.front_domain'));

                    $jar->setCookie($set_cookie);
                }
                $config['cookies'] = $jar;
            }

            // Set X-Csrf-Token header
            if (session()->has(OAuthKey::HWTREK_CSRF_TOKEN)) {
                $config['headers']['X-Csrf-Token'] = session()->get(OAuthKey::HWTREK_CSRF_TOKEN);
            }

            $client = new  Client($config);

            // Get CSRF Token and save set-cookie info
            if (!session()->has(OAuthKey::HWTREK_SESSION_COOKIES)) {
                try {
                    $response = $client->get('https://' . config('app.front_domain'));

                    $cookies = $response->getHeader('Set-Cookie');

                    session()->set(OAuthKey::HWTREK_SESSION_COOKIES, $cookies);

                    foreach ($cookies as $cookie) {
                        $set_cookie = SetCookie::fromString($cookie);
                        if ($set_cookie->getName() === 'csrf') {
                            $csrf_token = $set_cookie->getValue();
                            session()->set(OAuthKey::HWTREK_CSRF_TOKEN, $csrf_token);
                        }
                    }
                } catch (ConnectException $e) {
                    Log::error($e->getMessage(), $e->getTrace());

                    session()->flash(OAuthKey::API_SERVER_STATUS, 'stop');

                    auth()->logout();
                }
            }

            return $client;
        });
    }
}
