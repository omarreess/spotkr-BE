<?php

namespace Modules\FcmNotification\Transformers;

use App\Helpers\DateHelper;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\FcmNotification\Notifications\FcmNotification;

class NotificationResource extends JsonResource
{
    public function toArray($request)
    {
        $data = $this->data;

        $title = $data['title'];
        $body = $data['body'];
        $image = $data['image'];
        $shouldTranslate = $data['should_translate'] ?? [];
        $translatedAttributes = $data['translated_attributes'] ?? [];

        unset(
            $data['should_translate'],
            $data['translated_attributes'],
            $data['title'],
            $data['body'],
            $data['image'],
        );

        return [
            'id' => $this->id,
            'title' => FcmNotification::translatedKey(
                'title',
                $title,
                shouldTranslate: $shouldTranslate,
                translatedAttributes: $translatedAttributes
            ),
            'image' => $image ? asset($image) : null,
            'created_at' => DateHelper::dateDiffForHumans($this->created_at),
            'seen' => ! is_null($this->read_at),
            'body' => FcmNotification::translatedKey(
                'body',
                $body,
                shouldTranslate: $shouldTranslate,
                translatedAttributes: $translatedAttributes
            ),
            'data' => $data['data'],
        ];
    }
}
