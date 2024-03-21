<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/', [ArticleController::class, 'index']);

Route::get('/index', [ArticleController::class, 'index'])->middleware('guest')->name('index');
Route::get('/indexLogat', [ArticleController::class, 'index'])->middleware('auth')->name('indexLogat');

Route::get('/login-google', function () {
    return Socialite::driver('google')->redirect();
})->name('login-google');

Route::get('/google-callback', function () {
    $googleUser = Socialite::driver('google')->user();

    // Busca al usuario por su correo electrónico
    $user = User::where('email', $googleUser->email)->first();

    if ($user) {
        // Si el usuario ya existe, verifica si tiene un ID de proveedor (Google)
        if (empty($user->provider_id)) {
            // Si no tiene ID de proveedor, actualiza el usuario con la información de Google
            $user->update([
                'avatar' => $googleUser->avatar,
                'provider' => 'google',
                'provider_id' => $googleUser->id,
            ]);
        }

        // Inicia sesión con el usuario existente
        Auth::login($user);
    } else {
        // Si el usuario no existe, crea una nueva cuenta
        $userNew = User::create([
            'name' => $googleUser->name,
            'email' => $googleUser->email,
            'avatar' => $googleUser->avatar,
            'provider' => 'google',
            'provider_id' => $googleUser->id,
        ]);

        // Inicia sesión con la nueva cuenta
        Auth::login($userNew);
    }

    return redirect()->route('indexLogat');
});

Route::get('/login-github', function () {
    return Socialite::driver('github')->redirect();
})->name('login-github');

Route::get('/github-callback', function () {
    $githubUser = Socialite::driver('github')->user();

    // Busca al usuario por su correo electrónico
    $user = User::where('email', $githubUser->email)->first();

    if ($user) {
        // Si el usuario ya existe, verifica si tiene un ID de proveedor (GitHub)
        if (empty($user->provider_id)) {
            // Si no tiene ID de proveedor, actualiza el usuario con la información de GitHub
            $user->update([
                'avatar' => $githubUser->avatar,
                'provider' => 'github',
                'provider_id' => $githubUser->id,
            ]);
        }

        // Inicia sesión con el usuario existente
        Auth::login($user);
    } else {
        // Si el usuario no existe, crea una nueva cuenta
        $userNew = User::create([
            'name' => $githubUser->name,
            'email' => $githubUser->email,
            'avatar' => $githubUser->avatar,
            'provider' => 'github',
            'provider_id' => $githubUser->id,
        ]);

        // Inicia sesión con la nueva cuenta
        Auth::login($userNew);
    }

    return redirect()->route('indexLogat');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store')->middleware('auth');
Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit')->middleware('auth');
Route::patch('/articles/{article}', [ArticleController::class, 'update'])->name('articles.update')->middleware('auth');
Route::delete('/articles/{id}', [ArticleController::class, 'destroy'])->name('articles.destroy')->middleware('auth');


require __DIR__.'/auth.php';
