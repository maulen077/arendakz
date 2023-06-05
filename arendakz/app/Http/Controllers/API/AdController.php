<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Ad;

class AdController extends Controller
{
    public function index(Request $request)
    {
        $query = Ad::query();

        // Фильтр по статусу
        if ($request->has('status')) {
            $status = $request->input('status');

            if ($status === 'active') {
                $query->where('status', Ad::STATUS_ACTIVE);
            } elseif ($status === 'inactive') {
                $query->where('status', Ad::STATUS_INACTIVE);
            } elseif ($status === 'pending') {
                $query->where('status', Ad::STATUS_PENDING);
            } elseif ($status === 'rejected') {
                $query->where('status', Ad::STATUS_REJECTED);
            }
        }

        // Фильтр по категории
        if ($request->has('category_id')) {
            $categoryID = $request->input('category_id');
            $query->where('category_id', $categoryID);
        }

        // Фильтр по дате публикации
        if ($request->has('published_date')) {
            $publishedDate = $request->input('published_date');
            $query->whereDate('created_at', $publishedDate);
        }

        // Поиск по названию объявления
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where('title', 'like', "%{$searchTerm}%");
        }

        $ads = $query->get();

        return response()->json(['ads' => $ads], 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'category_id' => 'required',
            // Добавьте правила валидации для остальных полей
        ]);

        $validatedData['status'] = Ad::STATUS_PENDING;

        $ad = Ad::create($validatedData);

        return response()->json(['ad' => $ad], 201);
    }

    public function show(string $id)
    {
        $ad = Ad::findOrFail($id);

        return response()->json(['ad' => $ad], 200);
    }

    public function update(Request $request, string $id)
    {
        $ad = Ad::findOrFail($id);

        $validatedData = $request->validate([
            'title' => 'required',
            'category_id' => 'required',
            // Добавьте правила валидации для остальных полей
        ]);

        $ad->update($validatedData);

        return response()->json(['ad' => $ad], 200);
    }

    public function destroy(string $id)
    {
        $ad = Ad::findOrFail($id);

        $ad->delete();

        return response()->json(null, 204);
    }

}
