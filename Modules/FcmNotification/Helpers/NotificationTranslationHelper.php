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
            'order_finished_title' => 'Order finished',
            'order_finished_body' => 'Order #:id has been finished, tell us you experience !',
            'order_canceled_title' => 'Order canceled',
            'order_canceled_body' => 'Order #:id has been canceled',
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
            'order_finished_title' => 'تم إنهاء الطلب',
            'order_finished_body' => 'تم إنهاء الطلب #:id، أخبرنا عن تجربتك!',
            'order_canceled_title' => 'تم إلغاء الطلب',
            'order_canceled_body' => 'تم إلغاء الطلب #:id',
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
            'order_finished_title' => 'Commande terminée',
            'order_finished_body' => 'La commande #:id a été terminée, dites-nous votre expérience !',
            'order_canceled_title' => 'Commande annulée',
            'order_canceled_body' => 'La commande #:id a été annulée',
        ];
    }
}
