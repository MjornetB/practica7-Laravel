<x-app-layout>
    <form method="POST" action="{{ route('articles.update', $article->id) }}">
        @csrf
        @method('PATCH')
        <input type="text" name="title" value="{{ old('title', $article->title) }}" class="input-class" required>
        @error('title')
        <p class="error-class">{{ $message }}</p>
        @enderror
        <input type="submit" value="Actualizar" class="submit-class">
    </form>
</x-app-layout>
