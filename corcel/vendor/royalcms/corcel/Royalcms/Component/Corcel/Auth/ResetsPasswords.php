<?php

namespace Royalcms\Component\Corcel\Auth;

use RC_Auth;
use Royalcms\Component\Corcel\Services\PasswordService;
use Royalcms\Component\Contracts\Auth\CanResetPassword;

/**
 * Trait ResetsPasswords
 *
 * @package Royalcms\Component\Corcel\Auth
 */
trait ResetsPasswords
{
    /**
     * Reset the given user's password.
     *
     * @param CanResetPassword $user
     * @param string $password
     */
    protected function resetPassword(CanResetPassword $user, $password)
    {
        $user->user_pass = (new PasswordService())->makeHash($password);
        $user->save();

        RC_Auth::guard($this->getGuard())
            ->login($user);
    }
}
