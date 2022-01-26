<?php

namespace Lifeonscreen\Google2fa;

use Exception;
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
            $this->twoFactorAuthStillValid();
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
}
