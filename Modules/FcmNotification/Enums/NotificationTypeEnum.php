<?php

namespace Modules\FcmNotification\Enums;

enum NotificationTypeEnum
{
    const ACTIVITY_CREATED = 'activity_created';

    const ACTIVITY_UPDATED = 'activity_updated';

    const ACTIVITY_STATUS_CHANGED = 'activity_status_changed';

    const ORDER_CREATED = 'order_created';

    const ORDER_COMPLETED = 'order_finished';

    const ORDER_CANCELED = 'order_canceled';
}
