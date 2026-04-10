<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ItemCategoriesController;
use App\Http\Controllers\ItemStocksController;

Route::get('/', function () {
    return view('/welcome');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function (Request $request) {

    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect('/dashboard');
    }

    return back()->with('error', 'Email atau password salah');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    });

    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    });

});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
})->name('logout');

Route::middleware('auth')->group(function () {
    Route::resource('/category', ItemCategoriesController::class)->except(['create', 'edit', 'show']);
});

Route::middleware('auth')->group(function () {
    Route::resource('/item', ItemStocksController::class)->except(['create', 'edit', 'show']);
});

Route::get('/item/export', [ItemStocksController::class, 'export'])->name('item.export');