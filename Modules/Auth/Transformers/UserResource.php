<?php

namespace Modules\Auth\Transformers;

use App\Helpers\ResourceHelper;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Auth\Enums\AuthEnum;
use Modules\LeaderBoard\Transformers\AchievementResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            AuthEnum::UNIQUE_COLUMN => $this->whenHas(AuthEnum::UNIQUE_COLUMN),
            'status' => $this->whenHas('status'),
            'email' => $this->whenHas('email'),
            'username' => $this->whenHas('username'),
            'rolesIds' => $this->whenHas('rolesIds'),
            'achievements_sum_gained_points' => $this->whenHas('achievements_sum_gained_points', fn() => (int)$this->achievements_sum_gained_points),
            'avatar' => $this->whenNotNull(
                ResourceHelper::getFirstMediaOriginalUrl(
                    $this,
                    AuthEnum::AVATAR_RELATIONSHIP_NAME,
                    'user.png'
                )
            ),
            'type' => $this->whenHas('type'),
            'gained_points' => $this->whenHas('orders_count'),
            'is_winner' => $this->whenHas('last_winning_time', function(){
               return Carbon::parse($this->last_winning_time)->isBetween(
                   now()->firstOfMonth(),
                   now()->lastOfMonth(),
               );
            }),
            'token' => $this->whenHas('token'),
            'bio' => $this->whenHas('bio'),
            'social_links' => $this->whenHas('social_links'),
            'achievements' => AchievementResource::collection($this->whenLoaded('achievements')),
            $this->mergeWhen($this->relationLoaded('roles'), function () {
                $role = $this->roles->first();
                $permissions = [];

                if ($role?->relationLoaded('permissions')) {
                    foreach ($role->permissions as $permission) {
                        $permissions[] = $permission->name;
                    }
                }

                return [
                    'permissions' => $this->when((bool) $permissions, $permissions),
                ];
            }),
        ];
    }
}
