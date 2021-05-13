<?php

namespace Lifeonscreen\Google2fa;

use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Laravel\Nova\Tool;
use PragmaRX\Google2FAQRCode\Google2FA as G2fa;
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
     * @return bool
     */
    protected function is2FAValid()
    {
        $secret = Request::get('secret');
        if (empty($secret)) {
            return false;
        }

        $google2fa = new G2fa();

        return $google2fa->verifyKey(auth()->user()->user2fa->google2fa_secret, $secret);
    }

    protected function getQRCode()
    {
        $google2fa = new G2fa();

        $google2fa_url = $google2fa->getQRCodeInline(
            config('lifeonscreen2fa.company'),
            auth()->user()->email,
            auth()->user()->user2fa->google2fa_secret
        );

        return $google2fa_url;

//        return base64_encode($writer->writeString($google2fa_url));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \PragmaRX\Google2FA\Exceptions\InsecureCallException
     */
    public function confirm()
    {
        if ($this->is2FAValid()) {
            auth()->user()->user2fa->google2fa_enable = 1;
            auth()->user()->user2fa->save();

            app(Google2FAAuthenticator::class)->login();

            return response()->redirectTo(config('nova.path'));
        }

        $data['qrcode_image'] = $this->getQRCode();
        $data['secret_code'] = auth()->user()->user2fa->google2fa_secret;
        $data['displaySecretCode'] = config('lifeonscreen2fa.display_secret_code');
        $data['error'] = 'Secret is invalid.';

        return view('google2fa::register', $data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \PragmaRX\Google2FA\Exceptions\InsecureCallException
     */
    public function register()
    {
        $data['qrcode_image'] = $this->getQRCode();
        $data['secret_code'] = auth()->user()->user2fa->google2fa_secret;
        $data['displaySecretCode'] = config('lifeonscreen2fa.display_secret_code');

        return view('google2fa::register', $data);
    }

    private function isRecoveryValid($recover, $recoveryHashes)
    {
        foreach ($recoveryHashes as $recoveryHash) {
            if ($recover === $recoveryHash) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function authenticate()
    {
        $data = [];
        if ($recover = Request::get('recover')) {
            if ($this->isRecoveryValid($recover, json_decode(auth()->user()->user2fa->recovery, true)) === false) {
                $data['error'] = 'Recovery key is invalid.';

                return view('google2fa::authenticate', $data);
            }

            return $this->showRecoveryView($data);
        }
        if ($this->is2FAValid()) {
            $authenticator = app(Google2FAAuthenticator::class);
            $authenticator->login();

            return response()->redirectTo(config('nova.path'));
        }
        $data['error'] = 'One time password is invalid.';

        return view('google2fa::authenticate', $data);
    }

    public function showRecoveryView($data = [])
    {
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
}
