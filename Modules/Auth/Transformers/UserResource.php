<?php

namespace Modules\Auth\Transformers;

use App\Helpers\ResourceHelper;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Auth\Enums\AuthEnum;

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
            'avatar' => $this->whenNotNull(
                ResourceHelper::getMedia(
                    AuthEnum::AVATAR_COLLECTION_NAME,
                    $this,
                    AuthEnum::AVATAR_RELATIONSHIP_NAME,
                    'user.png'
                )
            ),
            'type' => $this->whenHas('type'),
            'token' => $this->whenHas('token'),
            'bio' => $this->whenHas('bio'),
            'social_links' => $this->whenHas('social_links'),
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
