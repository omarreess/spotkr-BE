<?php

namespace Modules\Auth\Strategies;

interface Verifiable
{
    public function verifyCode($handle, $code);

    public function sendCode($handle);

    public function forgetPassword($handle);

    public function resetPassword($handle, $code, $newPassword);
}
