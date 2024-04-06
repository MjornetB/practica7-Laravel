<x-app-layout>
    <div class="flex justify-center items-center min-h-screen bg-gray-100">
        <div class="w-3/4 md:w-1/2 lg:w-2/5 xl:w-1/3 bg-white rounded-lg shadow-xl p-6">
            <h2 class="text-2xl font-bold mb-4 text-center">Editar Artículo</h2>
            <form method="POST" action="{{ route('articles.update', $article->id) }}">
                @csrf
                @method('PATCH')
                <div class="mb-6">
                    <input type="text" name="title" value="{{ old('title', $article->title) }}"
                           class="w-full p-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600" required>
                    @error('title')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-center">
                    <input type="submit" value="Actualizar" class="mt-3 px-6 py-2 font-semibold bg-opacity-100 bg-gray-800 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-opacity-50 shadow-lg">
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
