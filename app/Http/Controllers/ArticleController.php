<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


//Controlador que s'encarrega de gestionar els articles, totes les seves operacions CRUD.
class ArticleController extends Controller
{
    //Retorna la vista index amb els articles paginats, depenent de si l'usuari esta logat o no, es redirigeix a una vista o una altra.
    public function index(Request $request)
    {
        $perPage = $request->query('perPage', 10); // variable que guarda el nombre d'articles per pàgina, s'obté apartir de un select
        if (auth()->check()) {
            $articles = Article::where('user_id', auth()->id())->paginate($perPage);
            return view('indexLogat', compact('articles'));
        } else {
            $articles = Article::paginate($perPage);
            return view('index', compact('articles'));
        }
    }
    // Retorna la vista per editar, incorpora un guardia per evitar que un usuari editi un article que no sigui seu.
    public function edit($id)
    {
        $article = Article::find($id);

        if ($article->user_id != auth()->id()) {
            return redirect()->route('indexLogat')->with('error', 'No puedes editar este artículo.');
        }
        return view('edit', compact('article'));
    }
    // Actualitza l'article, incorpora uns validadors per evitar que l'article sigui buit o superi els 255 caràcters.
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
        ], [
            'title.required' => 'El artículo no puede estar vacío.', //missatge d'error si el camp està buit
            'title.max' => 'El artículo no puede superar los 255 caracteres.', //missatge d'error si el camp supera els 255 caràcters
        ]);

        $article = Article::where('id', $id)->where('user_id', auth()->id())->first(); //busca l'article per id i per usuari

        if ($article) {
            $article->title = $validatedData['title'];
            $article->save();
            return redirect()->route('indexLogat')->with('success', 'Artículo actualizado correctamente.');
        } else {
            return back()->with('error', 'No se pudo actualizar el artículo.');
        }
    }
    // Elimina l'article, incorpora un guardia per evitar que un usuari elimini un article que no sigui seu.
    public function destroy($id)
    {
        $article = Article::where('id', $id)->where('user_id', auth()->id())->first();

        if ($article) {
            $article->delete();
            return redirect()->route('indexLogat')->with('success', 'Artículo eliminado correctamente.');
        } else {
            return back()->with('error', 'No se pudo eliminar el artículo.');
        }
    }
    // Guarda l'article, incorpora uns validadors per evitar que l'article sigui buit o superi els 255 caràcters.
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'article' => 'required|string|max:255',
        ], [
            'article.required' => 'El artículo no puede estar vacío.',
            'article.max' => 'El artículo no puede superar los 255 caracteres.',
        ]);


        $article = new Article();
        $article->title = $validatedData['article'];
        $article->user_id = Auth::id();
        $article->save();

        return redirect()->route('indexLogat');
    }

}
