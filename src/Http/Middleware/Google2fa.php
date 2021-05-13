<?php

namespace Lifeonscreen\Google2fa\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Lifeonscreen\Google2fa\Google2fa as Google2faManager;
use Lifeonscreen\Google2fa\Google2FAAuthenticator;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PragmaRX\Google2FA\Google2FA as G2fa;
use PragmaRX\Recovery\Recovery;

/**
 * Class Google2fa
 * @package Lifeonscreen\Google2fa\Http\Middleware
 */
class Google2fa
{
    private const PREVENT_BROWSER_CACHE_HEADERS = [
        "Expires" => "Thu, 19 Nov 1981 08:52:00 GMT", //Date in the past
        "Cache-Control" => "no-store, no-cache, must-revalidate", //HTTP/1.1
        "Pragma" => "no-cache", //HTTP 1.0
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws \PragmaRX\Google2FA\Exceptions\InsecureCallException
     */
    public function handle($request, Closure $next)
    {
        if ($request->route()->getName() == 'nova.logout') {
            return $next($request);
        }

        $response = $this->doHandle($request, $next);

        return $this->preventBrowserCaching($response);
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    private function doHandle(Request $request, Closure $next)
    {
        if ($emailDomain = config('lifeonscreen2fa.user_email_domain')) {
            if (!strpos($request->user()->email, '@' . $emailDomain)) {
                return $next($request);
            }
        }

        if ( ! config('lifeonscreen2fa.enabled') || (config('lifeonscreen2fa.optional') && ($request->user()->user2fa === null || auth()->user()->user2fa->google2fa_enable === 0))) {
            return $next($request);
        }
        if (
            $request->path() === 'los/2fa/confirm'
            || $request->path() === 'los/2fa/authenticate'
            || $request->path() === 'los/2fa/register'
            || $request->path() === 'los/2fa/recovery'
        ) {
            return $next($request);
        }
        $authenticator = app(Google2FAAuthenticator::class)->boot($request);
        if (auth()->guest() || $authenticator->isAuthenticated()) {
            return $next($request);
        }
        if (empty(auth()->user()->user2fa) || auth()->user()->user2fa->google2fa_enable === 0) {
            $google2fa = new G2fa();
            $recovery = new Recovery();
            $secretKey = $google2fa->generateSecretKey();
            $data['recovery'] = $recovery
                ->setCount(config('lifeonscreen2fa.recovery_codes.count'))
                ->setBlocks(config('lifeonscreen2fa.recovery_codes.blocks'))
                ->setChars(config('lifeonscreen2fa.recovery_codes.chars_in_block'))
                ->toArray();

            $user2faModel = config('lifeonscreen2fa.models.user2fa');
            $user2faModel::where('user_id', auth()->user()->id)->delete();

            $user2fa = new $user2faModel();
            $user2fa->user_id = auth()->user()->id;
            $user2fa->google2fa_secret = $secretKey;
            $user2fa->recovery = json_encode($data['recovery']);
            $user2fa->save();

            return response(view('google2fa::recovery', $data));
        }

        return response(view('google2fa::authenticate'));
    }

    /**
     * Set headers to NOT cache a page, used to prevent seeing a 2fa auth form
     * when user clicks on the back button multiple times after logging out.
     *
     * @param mixed $response
     * @return mixed
     */
    private function preventBrowserCaching($response)
    {
        // Required to allow file downloads in nova
        $symfonyRequest = StreamedResponse::class;

        foreach (self::PREVENT_BROWSER_CACHE_HEADERS as $key => $value) {
            if ($response instanceof $symfonyRequest) {
                $response->headers->set($key, $value);
            } else {
                $response->header($key, $value);
            }
        }

        return $response;
    }
}
