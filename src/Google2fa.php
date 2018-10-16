<?php

namespace Lifeonscreen\Google2fa;

use Laravel\Nova\Tool;
use PragmaRX\Google2FA\Google2FA as G2fa;
use Request;

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
     * Build the view that renders the navigation links for the tool.
     *
     * @return \Illuminate\View\View
     */
    public function renderNavigation()
    {
        return view('google2fa::navigation');
    }

    protected function is2FAValid()
    {
        $secret = Request::get('secret');

        $google2fa = new G2fa();
        $google2fa->setAllowInsecureCallToGoogleApis(true);

        return $google2fa->verifyKey(auth()->user()->user2fa->google2fa_secret, $secret);
    }

    public function confirm()
    {
        if ($this->is2FAValid()) {
            auth()->user()->user2fa->google2fa_enable = 1;
            auth()->user()->user2fa->save();
            $authenticator = app(Google2FAAuthenticator::class);
            $authenticator->login();

            return response()->redirectTo('/nova');
        }

        $google2fa = new G2fa();
        $secretKey = $google2fa->generateSecretKey();
        $google2fa->setAllowInsecureCallToGoogleApis(true);

        $google2fa_url = $google2fa->getQRCodeGoogleUrl(
            config('app.name'),
            auth()->user()->email,
            $secretKey
        );

        $data['google2fa_url'] = $google2fa_url;
        $data['error'] = 'Secret is invalid.';

        return view('google2fa::register', $data);

    }

    public function authenticate()
    {
        if ($this->is2FAValid()) {
            $authenticator = app(Google2FAAuthenticator::class);
            $authenticator->login();

            return response()->redirectTo('/nova');
        }
        $data['error'] = 'One time password is invalid.';

        return view('google2fa::authenticate', $data);
    }
}
