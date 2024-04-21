<?php

namespace Modules\LeaderBoard\Helpers;

class LeaderboardTranslationHelper
{
    public static function en(): array
    {
        return [
            'max_users_exceeded' => 'all :number users won already',
            'user_won' => 'User won',
        ];
    }
}
