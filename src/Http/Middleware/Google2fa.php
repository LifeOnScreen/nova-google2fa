<?php

namespace Lifeonscreen\Google2fa\Http\Middleware;

use Closure;
use Lifeonscreen\Google2fa\Google2FAAuthenticator;
use Lifeonscreen\Google2fa\Models\User2fa;
use PragmaRX\Google2FA\Google2FA as G2fa;
use PragmaRX\Recovery\Recovery;

/**
 * Class Google2fa
 * @package Lifeonscreen\Google2fa\Http\Middleware
 */
class Google2fa
{
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
        if ($request->path() === 'los/2fa/confirm' || $request->path() === 'los/2fa/authenticate'
            || $request->path() === 'los/2fa/register') {
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
            $data['recovery'] = $recovery = $recovery
                ->setCount(8)
                ->setBlocks(3)
                ->setChars(16)
                ->toArray();

            User2fa::where('user_id', auth()->user()->id)->delete();
            User2fa::insert([
                'user_id'          => auth()->user()->id,
                'google2fa_secret' => $secretKey,
                'recovery'         => json_encode($data['recovery']),
            ]);

            return response(view('google2fa::recovery', $data));
        }

        return response(view('google2fa::authenticate'));
    }
}