<?php

namespace Lifeonscreen\Google2fa;

use Laravel\Nova\Tool;
use Lifeonscreen\Google2fa\Models\User2fa;
use PragmaRX\Google2FA\Google2FA as G2fa;
use PragmaRX\Recovery\Recovery;
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
        if (empty($secret)) {
            return false;
        }

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
        $google2fa->setAllowInsecureCallToGoogleApis(true);

        $google2fa_url = $google2fa->getQRCodeGoogleUrl(
            config('app.name'),
            auth()->user()->email,
            auth()->user()->user2fa->google2fa_secret
        );

        $data['google2fa_url'] = $google2fa_url;
        $data['error'] = 'Secret is invalid.';

        return view('google2fa::register', $data);
    }

    public function register()
    {
        $google2fa = new G2fa();
        $google2fa->setAllowInsecureCallToGoogleApis(true);

        $google2fa_url = $google2fa->getQRCodeGoogleUrl(
            config('app.name'),
            auth()->user()->email,
            auth()->user()->user2fa->google2fa_secret
        );

        $data['google2fa_url'] = $google2fa_url;

        return view('google2fa::register', $data);

    }

    public function authenticate()
    {
        if ($recover = Request::get('recover')) {
            if (in_array($recover, json_decode(auth()->user()->user2fa->recovery, true)) === false) {
                $data['error'] = 'Recovery key is invalid.';

                return view('google2fa::authenticate', $data);
            }

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
        if ($this->is2FAValid()) {
            $authenticator = app(Google2FAAuthenticator::class);
            $authenticator->login();

            return response()->redirectTo('/nova');
        }
        $data['error'] = 'One time password is invalid.';

        return view('google2fa::authenticate', $data);
    }
}
