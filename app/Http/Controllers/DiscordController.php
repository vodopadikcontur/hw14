<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class DiscordController
{
    public function callback(Request $request)
    {
        $response = Http::asForm()
            ->post('https://discord.com/api/v10/oauth2/token', [
                'client_id' => config('oauth.discord.client_id'),
                'client_secret' => config('oauth.discord.client_secret'),
                'grant_type' => 'authorization_code',
                'code' => $request->get('code'),
                'redirect_uri' => config('oauth.discord.callback_uri')

            ]);

        $token = $response->json('access_token');
        $tokenType = $response->json('token_type');

        $response = Http::withHeaders(['Authorization' => $tokenType . ' ' . $token])
            ->get('https://discord.com/api/users/@me');

        //dd($response->body());

        $user = User::query()
            ->where('email', '=', $response->json('username'))
            ->first();

        if ($user === null) {
            $user = User::create([
                'username' => $response->json('username'),
                'avatar' => $response->json('avatar'),
                'email' => $response->json('email')
            ]);
            Auth::login($user);

            return redirect('/');
        }

    }
}
