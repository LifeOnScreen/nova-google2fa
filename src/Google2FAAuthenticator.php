<?php

namespace Lifeonscreen\Google2fa;

use Exception;
use Carbon\Carbon;
use PragmaRX\Google2FALaravel\Support\Authenticator;

/**
 * Class Google2FAAuthenticator
 * @package Lifeonscreen\Google2fa
 */
class Google2FAAuthenticator extends Authenticator
{
    protected function canPassWithoutCheckingOTP()
    {
        
        return
            !$this->isEnabled() ||
            $this->noUserIsAuthenticated() ||
            $this->twoFactorAuthStillValid() ||
            $this->google2FAValidUntillNow();
    }

    /**
     * @return mixed
     * @throws Exception
     */
    protected function getGoogle2FASecretKey()
    {
        $secret = $this->getUser()->user2fa->{$this->config('otp_secret_column')};
        if (is_null($secret) || empty($secret)) {
            throw new Exception('Secret key cannot be empty.');
        }

        return $secret;
    }

    protected function google2FAValidUntillNow()
    {
        $user2faTimeout = env('GOOGLE_2FA_TIMEOUT', (31*24*60*60));
        $user2faValidUntill = is_object($this->getUser()->user2fa->updated_at) ? Carbon::parse($this->getUser()->user2fa->updated_at->timestamp)->addSeconds($user2faTimeout) : null;
        $user2faValid = is_object($user2faValidUntill) && Carbon::now()->timestamp < $user2faValidUntill->timestamp;

        return $user2faValid;
    }
}