<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Builders\UserBuilder;
use App\Traits\PaginationTrait;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Auth\Traits\HasVerifyTokens;
use Modules\Auth\Traits\UserRelations;
use Modules\Payment\Entities\Card;
use Modules\Payment\Traits\StripePaymentTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 *
 *
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property string|null $email
 * @property string|null $username
 * @property \Illuminate\Support\Carbon|null $phone_verified_at
 * @property string $type
 * @property bool $status
 * @property mixed|null $password
 * @property string|null $bio
 * @property array|null $social_links
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $social_provider
 * @property string|null $fcm_token
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static UserBuilder|User formatResult()
 * @method static UserBuilder|User newModelQuery()
 * @method static UserBuilder|User newQuery()
 * @method static UserBuilder|User onlyAllowedUsers()
 * @method static UserBuilder|User paginatedCollection()
 * @method static UserBuilder|User query()
 * @method static UserBuilder|User searchByForeignKey(string $foreignKeyColumn, ?string $value = null)
 * @method static UserBuilder|User searchable(array $columns = [], array $translatedKeys = [], string $handleKeyName = 'handle')
 * @method static UserBuilder|User whereBio($value)
 * @method static UserBuilder|User whereCreatedAt($value)
 * @method static UserBuilder|User whereEmail($value)
 * @method static UserBuilder|User whereFcmToken($value)
 * @method static UserBuilder|User whereId($value)
 * @method static UserBuilder|User whereIsAdmin()
 * @method static UserBuilder|User whereIsThirdParty()
 * @method static UserBuilder|User whereName($value)
 * @method static UserBuilder|User wherePassword($value)
 * @method static UserBuilder|User wherePhone($value)
 * @method static UserBuilder|User wherePhoneVerifiedAt($value)
 * @method static UserBuilder|User whereRememberToken($value)
 * @method static UserBuilder|User whereSocialLinks($value)
 * @method static UserBuilder|User whereSocialProvider($value)
 * @method static UserBuilder|User whereStatus($value)
 * @method static UserBuilder|User whereType($value)
 * @method static UserBuilder|User whereUpdatedAt($value)
 * @method static UserBuilder|User whereUsername($value)
 * @method static UserBuilder|User whereValidType(bool $inMobile)
 * @mixin \Eloquent
 */
class User extends Authenticatable implements HasMedia
{
    use HasFactory,
        Notifiable,
        HasApiTokens,
        UserRelations,
        HasVerifyTokens,
        PaginationTrait,
        Searchable,
        StripePaymentTrait,
        InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'type',
        'status',
        'username',
        'bio',
        'social_links',
        'social_provider',
        'country_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'phone_verified_at' => 'datetime',
            'password' => 'hashed',
            'social_links' => 'array',
            'status' => 'boolean',
        ];
    }

    public function newEloquentBuilder($query): UserBuilder
    {
        return new UserBuilder($query);
    }

    public function routeNotificationForFcm(): ?string
    {
        return $this->fcm_token;
    }

    public function receivesBroadcastNotificationsOn(): string
    {
        return 'notifications.users.'.$this->id;
    }

    public function creditCards(): HasMany
    {
        return $this->hasMany(Card::class);
    }
}
