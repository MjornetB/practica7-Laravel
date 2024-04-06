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
// Ruta per defecte a la creació del projecte, la podriem eliminar pero en aquest cas no molesta.
Route::get('/welcome', function () {
    return view('welcome');
});

// Ruta per defecte a la creació del projecte, la podriem eliminar pero en aquest cas no molesta, he afegit un easter egg.
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Ruta arrel de la web, la primera que es carrega quan es carrega la web, depenent de si l'usuari esta logat o no, es redirigeix a una vista o una altra.
Route::get('/', [ArticleController::class, 'index']);

// Ruta per a la vista index, accesible per anònims.
Route::get('/index', [ArticleController::class, 'index'])->middleware('guest')->name('index');
// Ruta per a la vista indexLogat, accesible per usuaris logats.
Route::get('/indexLogat', [ArticleController::class, 'index'])->middleware('auth')->name('indexLogat');

//Ruta per l'oAuth de Google
Route::get('/login-google', function () {
    return Socialite::driver('google')->redirect();
})->name('login-google');

//Ruta per el callback de Google
Route::get('/google-callback', function () {
    $googleUser = Socialite::driver('google')->user(); //Obté les dades de l'usuari de Google

    // Busca l'usuari per el seu correu electrònic
    $user = User::where('email', $googleUser->email)->first();

    if ($user) {
        // Si l'usuari ja existeix, verifica si té un ID de proveïdor (Google)
        if (empty($user->provider_id)) {
            // Si no té ID de proveïdor, actualitza l'usuari amb la informació de Google
            $user->update([
                'avatar' => $googleUser->avatar,
                'provider' => 'google',
                'provider_id' => $googleUser->id,
            ]);
        }

        // Inicia sessió amb l'usuari existent
        Auth::login($user);
    } else {
        // Si l'usuari no existeix, crea un nou compte amb les dades de Google
        $userNew = User::create([
            'name' => $googleUser->name,
            'email' => $googleUser->email,
            'avatar' => $googleUser->avatar,
            'provider' => 'google',
            'provider_id' => $googleUser->id,
        ]);

        // Inicia sesió amb el nou compte
        Auth::login($userNew);
    }
    //Redirigeix a la vista indexLogat
    return redirect()->route('indexLogat');
});

//Ruta per l'oAuth de GitHub
Route::get('/login-github', function () {
    return Socialite::driver('github')->redirect();
})->name('login-github');

//Ruta per el callback de GitHub
Route::get('/github-callback', function () {
    $githubUser = Socialite::driver('github')->user(); //Obté les dades de l'usuari de GitHub

    // Busca l'usuari per el seu correu electrònic
    $user = User::where('email', $githubUser->email)->first();

    if ($user) {
        // Si l'usuari ja existeix, verifica si té un ID de proveïdor (GitHub)
        if (empty($user->provider_id)) {
            // Si no té ID de proveïdor, actualitza l'usuari amb la informació de GitHub
            $user->update([
                'avatar' => $githubUser->avatar,
                'provider' => 'github',
                'provider_id' => $githubUser->id,
            ]);
        }

        // Inicia sesió amb l'usuari existent
        Auth::login($user);
    } else {
        // Si l'usuari no existeix, crea un nou compte amb les dades de GitHub
        $userNew = User::create([
            'name' => $githubUser->name,
            'email' => $githubUser->email,
            'avatar' => $githubUser->avatar,
            'provider' => 'github',
            'provider_id' => $githubUser->id,
        ]);

        // Inicia sesió amb el nou compte
        Auth::login($userNew);
    }

    return redirect()->route('indexLogat');
});
//Routing del perfil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
//Routing dels articles, les operacions CRUD
Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store')->middleware('auth');
Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit')->middleware('auth');
Route::patch('/articles/{article}', [ArticleController::class, 'update'])->name('articles.update')->middleware('auth');
Route::delete('/articles/{id}', [ArticleController::class, 'destroy'])->name('articles.destroy')->middleware('auth');


require __DIR__.'/auth.php';
