<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Models\Food;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/food/{id}', function ($id) {
    // Lấy thông tin món ăn kèm theo quán, size và topping
    $food = Food::with(['restaurant', 'sizes', 'toppings'])->findOrFail($id);
    return view('food.detail', compact('food'));
})->name('food.detail');