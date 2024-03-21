<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->check()) {
            $articles = Article::where('user_id', auth()->id())->paginate(10);
            return view('indexLogat', compact('articles'));
        } else {
            $articles = Article::paginate(10);
            return view('index', compact('articles'));
        }
    }

    public function edit($id)
    {
        $article = Article::find($id);

        if ($article->user_id != auth()->id()) {
            return redirect()->route('indexLogat')->with('error', 'No puedes editar este artículo.');
        }
        return view('edit', compact('article'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'article' => 'required|string|max:255',
        ], [
            'article.required' => 'El artículo no puede estar vacío.',
            'article.max' => 'El artículo no puede superar los 255 caracteres.',
        ]);

        $article = Article::where('id', $id)->where('user_id', auth()->id())->first();

        if ($article) {
            $article->title = $validatedData['article'];
            $article->save();
            return redirect()->route('indexLogat')->with('success', 'Artículo actualizado correctamente.');
        } else {
            return back()->with('error', 'No se pudo actualizar el artículo.');
        }
    }

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
