<?php

namespace Modules\FcmNotification\Helpers;

class NotificationTranslationHelper
{
    public static function en(): array
    {
        return [
            'notification' => 'Notification',
            'notifications' => 'Notifications',
            'activity_status_change_title' => 'Activity status changed !',
            'activity_status_change_body' => 'Activity :name status has been changed',
            'activity_created_title' => 'New activity has been created !',
            'activity_created_body' => 'Activity :name has been created, take an action now !',
            'order_created_title' => 'New order created',
            'order_created_body' => 'Pending order #:id',
        ];
    }

    public static function ar(): array
    {
        return [
            'you_have_new_message' => 'رسالة جديدة',
            'notification' => 'الإشعار',
            'notifications' => 'الأشعارات',
            'activity_status_change_title' => 'تغيير حالة النشاط!',
            'activity_status_change_body' => 'تم تغيير حالة النشاط :name',
            'activity_created_title' => 'تم إنشاء نشاط جديد!',
            'activity_created_body' => 'تم إنشاء النشاط :name، قم باتخاذ إجراء الآن!',
            'order_created_title' => 'تم إنشاء طلب جديد',
            'order_created_body' => 'طلب معلق #:id',
        ];
    }

    public static function fr(): array
    {
        return [
            'you_have_new_message' => 'Nouveau message',
            'notification' => 'Notification',
            'notifications' => 'Notifications',
            'activity_status_change_title' => "Changement de statut d'activité !",
            'activity_status_change_body' => "Le statut de l'activité :name a été modifié",
            'activity_created_title' => 'Une nouvelle activité a été créée !',
            'activity_created_body' => "L'activité :name a été créée, agissez maintenant !",
            'order_created_title' => 'Nouvelle commande créée',
            'order_created_body' => 'Commande en attente #:id',
        ];
    }
}
