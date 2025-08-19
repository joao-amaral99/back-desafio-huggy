<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HuggyController extends Controller
{
    public function handleHuggyCallback(Request $request)
    {
        $code = $request->get('code');
        if (!$code) {
            return response()->json(['error' => 'Código de autorização não encontrado.'], 400);
        }

        $clientId = env('HUGGY_CLIENT_ID');
        $clientSecret = env('HUGGY_CLIENT_SECRET');
        $redirectUri = env('HUGGY_REDIRECT_URI');

        $response = Http::asForm()->post('https://auth.huggy.app/oauth/access_token', [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $redirectUri,
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
        ]);

        if (!$response->ok()) {
            return response()->json([
                'error' => 'Erro ao obter token da Huggy',
                'status' => $response->status(),
                'details' => $response->body(),
            ], 500);
        }

        $accessToken = $response->json()['access_token'] ?? null;
        if (!$accessToken) {
            return response()->json(['error' => 'Token de acesso não recebido.'], 500);
        }

        return response()->view('auth.huggy-popup-close', ['token' => $accessToken]);
    }

    public function redirectToHuggy()
    {
        $clientId = env('HUGGY_CLIENT_ID');
        $redirectUri = urlencode(env('HUGGY_REDIRECT_URI'));
        $url = "https://auth.huggy.app/oauth/authorize?scope=install_app%20read_agent_profile&response_type=code&redirect_uri={$redirectUri}&client_id={$clientId}";

        return redirect($url);
    }
}
