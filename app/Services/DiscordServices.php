<?php

namespace App\Services;

class DiscordServices
{
    public static function link(): string
    {
        $parametes = [
            'response_type' => 'code',
            'client_id' => config('oauth.discord.client_id'),
            'scope' => 'identify email',
          'redirect_uri' => config('oauth.discord.callback_uri'),
        ];

        return 'https://discord.com/api/oauth2/authorize?' . http_build_query($parametes);
    }
}
