<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Artículos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Artículos
                        de {{ Auth::user()->name }}</h1>
                    <!-- Articles i les opcions de edit and delete -->
                    <ul>
                        @forelse($articles as $article)
                            <li>
                                {{$article->id}} - {{ $article->title }}
                                <form method="POST" action="{{ route('articles.destroy', $article->id) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">❌</button>
                                </form>
                                <a href="{{ route('articles.edit', $article->id) }}" class="text-indigo-600 hover:text-indigo-900">🖊️</a>
                            </li>
                        @empty
                            <li>No hay artículos para mostrar</li>
                        @endforelse
                    </ul>
                    <!-- Paginació i selecció de resultats per pàgina -->
                    {{ $articles->withQueryString()->links() }}
                    <form action="{{ url()->current() }}" method="GET">
                        <select name="perPage" onchange="this.form.submit()">
                            <option value="10"{{ request('perPage') == 10 ? ' selected' : '' }}>10</option>
                            <option value="20"{{ request('perPage') == 20 ? ' selected' : '' }}>20</option>
                            <option value="30"{{ request('perPage') == 30 ? ' selected' : '' }}>30</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Control de artículos
                        de {{ Auth::user()->name }}</h1>
                    <!-- Formulari per a crear un article -->
                    <label>Crea un artículo</label>
                    <form method="POST" action="{{ route('articles.store') }}">
                        @csrf
                        <input type="text" name="article" placeholder="Artículo"
                               class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-gray-700 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 disabled:bg-gray-100 disabled:text-gray-500 disabled:border-gray-200"
                               required>
                        @if ($errors->has('article'))
                            <div class="alert alert-danger">
                                {{ $errors->first('article') }}
                            </div>
                        @endif
                        <input type="submit" name="enviaArticle" value="Añadir"
                               class="mt-3 px-6 py-2 font-semibold bg-opacity-100 bg-gray-800 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-opacity-50 shadow-lg">
                    </form>
                </div>
            </div>
        </div>
</x-app-layout>
