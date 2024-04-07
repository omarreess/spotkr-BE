<?php

namespace Modules\FcmNotification\Entities;

use App\Models\User;
use App\Traits\PaginationTrait;
use Illuminate\Notifications\DatabaseNotification;

/**
 * Modules\FcmNotification\Entities\NotificationModel
 *
 * @property string $id
 * @property string $type
 * @property string $notifiable_type
 * @property string $notifiable_id
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $notifiable
 *
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection<int, static> all($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationModel formatResult()
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection<int, static> get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationModel paginatedCollection()
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationModel query()
 * @method static Builder|DatabaseNotification read()
 * @method static Builder|DatabaseNotification unread()
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationModel whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationModel whereNotifiableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationModel whereNotifiableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationModel whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationModel whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationModel whereUserNotifiableType()
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection<int, static> all($columns = ['*'])
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection<int, static> get($columns = ['*'])
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection<int, static> all($columns = ['*'])
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection<int, static> get($columns = ['*'])
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection<int, static> all($columns = ['*'])
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection<int, static> get($columns = ['*'])
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection<int, static> all($columns = ['*'])
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection<int, static> get($columns = ['*'])
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection<int, static> all($columns = ['*'])
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection<int, static> get($columns = ['*'])
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection<int, static> all($columns = ['*'])
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection<int, static> get($columns = ['*'])
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection<int, static> all($columns = ['*'])
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection<int, static> get($columns = ['*'])
 *
 * @mixin \Eloquent
 */
class NotificationModel extends DatabaseNotification
{
    use PaginationTrait;

    public function scopeWhereUserNotifiableType($query)
    {
        return $query->whereNotifiableType(User::class);
    }
}
