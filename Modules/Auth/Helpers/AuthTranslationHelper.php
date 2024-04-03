<?php

namespace Modules\Auth\Helpers;

class AuthTranslationHelper
{
    public static function en(): array
    {
        return [
            'user_verification_sent' => 'and user verification sent',
            'password_reset_successfully' => 'Password reset successfully',
            'user_have_no_store' => 'User must have a store',
            'user_registered_title' => 'New user registered',
            'user_registered_body' => 'New user registered named :name',
            'wrong_credentials' => 'Wrong Credentials',
            'user_already_verified' => 'User already verified',
            'user' => 'User',
            'resend_verify_code' => 'Verification code sent successfully',
            'handle' => 'Handle',
            'invalid_verify_code' => 'Invalid code for that handle',
            'expired_verify_code' => 'Verification code is expired',
            'verified' => 'User has been verified',
            'forgot_password_sent' => 'Forgot password code has been sent',
            'one_time_password_sent' => 'One time password has been sent',
            'invalid_credentials' => 'Invalid credentials',
        ];
    }

    public static function ar(): array
    {
        return [
            'user_verification_sent' => 'وتم ارسال ايميل التفعيل',
            'password_reset_successfully' => 'تم تعيين كلمه السر بنجاح',
            'user_have_no_store' => 'المستخدم الحالي لا ينتمي لاي متجر',
            'user_registered_title' => 'سجل مستخدم جديد',
            'user_registered_body' => ':name ينتظر اتخاذ إجراء في تسجيل دخوله !',
            'wrong_credentials' => 'بيانات الدخول خاطئة',
            'user_already_verified' => 'المستخدم مفعل بالفعل',
            'user' => 'المستخدم',
            'resend_verify_code' => 'تم ارسال كود التفعيل بنجاح',
            'handle' => 'المعرف',
            'invalid_verify_code' => 'كود التفعيل غير صحيح',
            'expired_verify_code' => 'انتهت صلاحية كود التفعيل',
            'verified' => 'تم تفعيل المستخدم بنجاح',
            'forgot_password_sent' => 'تم ارسال كود نسيت كلمة السر',
            'one_time_password_sent' => 'تم ارسال كود مرة واحدة بنجاح',
            'invalid_credentials' => 'بيانات الدخول غير صحيحة',
        ];
    }

    public static function fr(): array
    {
        return [
            'user_verification_sent' => 'et l\'utilisateur a reçu une vérification',
            'password_reset_successfully' => 'Réinitialisation du mot de passe réussie',
            'user_have_no_store' => 'L\'utilisateur doit avoir un magasin',
            'user_registered_title' => 'Nouvel utilisateur enregistré',
            'user_registered_body' => 'Nouvel utilisateur enregistré nommé :name',
            'wrong_credentials' => 'Mauvaises informations d\'identification',
            'user_already_verified' => 'Utilisateur déjà vérifié',
            'user' => 'Utilisateur',
            'resend_verify_code' => 'Code de vérification envoyé avec succès',
            'handle' => 'Manipuler',
            'invalid_verify_code' => 'Code invalide pour cette poignée',
            'expired_verify_code' => 'Le code de vérification a expiré',
            'verified' => 'L\'utilisateur a été vérifié',
            'forgot_password_sent' => 'Le code de mot de passe oublié a été envoyé',
            'one_time_password_sent' => 'Un mot de passe unique a été envoyé',
            'invalid_credentials' => 'Informations d\'identification invalides',
        ];
    }
}
