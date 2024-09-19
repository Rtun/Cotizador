<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiToken extends Model
{
    protected $table = 'api_tokens';
    protected $fillable = ['key','token', 'expiration'];
    public $timestamps = false; // Desactivar timestamps automáticos

    public static function isTokenExpired()
    {
        // Verificar si el token ha expirado
        $token = self::latest('expiration')->first();
        if(!$token) {
            return true;
        }
        return Carbon::now()->greaterThan($token->expiration);
    }

    public static function getToken($key)
    {
        // Retornar el token más reciente
        return self::latest('expiration')->where('key', $key)->first()->token;
    }

    public static function updateToken($key, $newToken, $expiration)
    {
        // Actualizar el token en la base de datos
        return self::create([
            'key' => $key,
            'token' => $newToken,
            'expiration' => $expiration,
        ]);
    }
}
