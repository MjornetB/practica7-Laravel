<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
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
}
