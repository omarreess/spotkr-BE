<?php

namespace Modules\Auth\Actions\Register;

use App\Exceptions\ValidationErrorsException;
use App\Models\User;
use Closure;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Modules\Auth\Enums\UserTypeEnum;
use Modules\Auth\Factories\VerifyUserFactory;
use Modules\Auth\Strategies\Verifiable;
use Modules\FcmNotification\Enums\NotificationTypeEnum;
use Modules\FcmNotification\Notifications\FcmNotification;
use Modules\Wallet\Entities\Wallet;

class BaseRegisterAction
{
    /**
     * @throws \Throwable
     * @throws ValidationErrorsException
     */
    public function handle(array $data, Verifiable $verifiable,  ?Closure $closure = null)
    {
        $errors = [];

        try {
            return DB::transaction(function () use ($data, $verifiable, $closure, &$errors) {
                $user = User::create($data);

                if ($closure) {
                    $closure($user, $errors, $data);
                }

                $verifiable->sendCode($user);

                return $user;
            });
        }
        catch (ValidationErrorsException $e) {
            throw $e;
        }
        catch (Exception $e) {
            $errors['operation_failed'] = $e->getMessage();

            throw new ValidationErrorsException($errors);
        }
    }
}