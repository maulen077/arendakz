<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        // Получите текущего пользователя
        $user = $request->user();

        // Получите профиль пользователя
        $profile = $user->profile;

        // Верните профиль пользователя в качестве ответа
        return response()->json([
            'profile' => $profile,
        ]);
    }

    public function update(Request $request)
    {
        // Получите текущего пользователя
        $user = $request->user();

        // Получите профиль пользователя
        $profile = $user->profile;

        // Обновите поля профиля пользователя
        $profile->update([
            'type' => $request->input('type'),
            'city' => $request->input('city'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
        ]);

        // Верните обновленный профиль пользователя в качестве ответа
        return response()->json([
            'profile' => $profile,
        ]);
    }
}
