<?php

namespace Lifeonscreen\Google2fa;

use Laravel\Nova\Tool;
use PragmaRX\Google2FALaravel\Google2FA as Google2FALaravelGoogle2FA;
use PragmaRX\Google2FAQRCode\Google2FA as G2fa;
use PragmaRX\Recovery\Recovery;
use Illuminate\Support\Facades\Request;

class Google2fa extends Tool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \PragmaRX\Google2FA\Exceptions\InsecureCallException
     */
    public function confirm()
    {
        if (app(Google2FAAuthenticator::class)->isAuthenticated()) {
            auth()->user()->user2fa->google2fa_enable = 1;
            auth()->user()->user2fa->save();

            return response()->redirectTo(config('nova.path'));
        }

        $google2fa = new G2fa();

        $google2fa_url = $google2fa->getQRCodeInline(
            config('app.name'),
            auth()->user()->email,
            auth()->user()->user2fa->google2fa_secret
        );

        $data['google2fa_url'] = $google2fa_url;
        $data['error'] = 'Secret is invalid.';

        return view('google2fa::register', $data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \PragmaRX\Google2FA\Exceptions\InsecureCallException
     */
    public function register()
    {
        $google2fa = new G2fa();

        $google2fa_url = $google2fa->getQRCodeInline(
            config('app.name'),
            auth()->user()->email,
            auth()->user()->user2fa->google2fa_secret
        );

        $data['google2fa_url'] = $google2fa_url;

        return view('google2fa::register', $data);

    }

    private function isRecoveryValid($recover, $recoveryHashes)
    {
        return in_array($recover, $recoveryHashes);
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function authenticate()
    {
        if ($recover = Request::get('recover')) {
            if ($this->isRecoveryValid($recover, json_decode(decrypt(auth()->user()->user2fa->recovery), true)) === false) {
                $data['error'] = 'Recovery key is invalid.';

                return view('google2fa::authenticate', $data);
            }

            // When the user uses a recovery code, we're going to replace that single code with
            // a new one. There's no need to reconfigure the Authenticator app.

            $newCode = (new Recovery())
                ->setCount(1)
                ->setBlocks(config('lifeonscreen2fa.recovery_codes.blocks'))
                ->setChars(config('lifeonscreen2fa.recovery_codes.chars_in_block'))
                ->toArray()[0];

            auth()->user()->user2fa->forceFill([
                'recovery' => encrypt(str_replace(
                    $recover,
                    $newCode,
                    decrypt(auth()->user()->user2fa->recovery)
                )),
            ])->save();

            // If the user has authenticated with a recovery code,
            // we can go ahead and authorize their session.

            (new Google2FALaravelGoogle2FA(Request::instance()))->login();

            return response()->redirectTo(config('nova.path'));
        }

        if (app(Google2FAAuthenticator::class)->isAuthenticated()) {
            return response()->redirectTo(config('nova.path'));
        }

        $data['error'] = 'One time password is invalid.';

        return view('google2fa::authenticate', $data);
    }
}
