<?php

namespace Modules\FcmNotification\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotificationResource;

class FcmNotification extends Notification
{
    use Queueable;

    private static array $shouldTranslate;

    private static array $translatedAttributes;

    public function __construct(
        private readonly string $title,
        private readonly string $body,
        private readonly ?string $image = null,
        private readonly array $additionalData = [],
        array $shouldTranslate = [],
        array $translatedAttributes = [],
    ) {
        self::$shouldTranslate = $shouldTranslate;
        self::$translatedAttributes = $translatedAttributes;
    }

    public function via($notifiable)
    {
        return ['database', FcmChannel::class];
    }

    public function toFcm($notifiable)
    {
        return (new FcmMessage(notification: new FcmNotificationResource(
            title: self::translatedKey('title', $this->title),
            body: self::translatedKey('body', $this->body),
            image: asset($this->image)
        )))
            ->data($this->additionalData + ['id' => $this->id]);
    }

    public function toDatabase(): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'image' => $this->image,
            'data' => $this->additionalData,
            'should_translate' => self::$shouldTranslate,
            'translated_attributes' => self::$translatedAttributes,
        ];
    }

    public static function translatedKey(
        string $key,
        string $value,
        ?string $translatedKeyName = null,
        ?array $shouldTranslate = null,
        ?array $translatedAttributes = null,
    ): string {
        $translatedKeyName = $translatedKeyName ?: $value;
        $shouldTranslate = is_null($shouldTranslate)
            ? self::$shouldTranslate
            : $shouldTranslate;

        $translatedAttributes = is_null($translatedAttributes) ?
            self::$translatedAttributes
            : $translatedAttributes;

        return isset($shouldTranslate[$key]) && $shouldTranslate[$key]
            ? translate_word(
                $translatedKeyName,
                $translatedAttributes[$translatedKeyName] ?? []
            )
            : $value;
    }
}
