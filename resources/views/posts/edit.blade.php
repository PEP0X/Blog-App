<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Post') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg transform transition-all duration-300 hover:shadow-md">
            <div class="p-6">
                <form method="POST" action="{{ route('posts.update', $post) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="title" :value="__('Title')" class="text-gray-700" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition duration-150 ease-in-out" :value="old('title', $post->title)" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('title')" />
                    </div>

                    <div>
                        <x-input-label for="cover_image" :value="__('Cover Image')" class="text-gray-700" />
                        @if($post->cover_image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $post->cover_image) }}" alt="Current cover image" class="w-32 h-32 object-cover rounded">
                            </div>
                        @endif
                        <input type="file" name="cover_image" id="cover_image" accept="image/jpeg,image/png,image/jpg,image/gif" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition duration-150 ease-in-out">
                        <p class="text-gray-600 text-xs mt-1">Upload a new cover image (JPEG, PNG, JPG, GIF) up to 2MB</p>
                        <x-input-error class="mt-2" :messages="$errors->get('cover_image')" />
                    </div>

                    <div>
                        <x-input-label for="content" :value="__('Content')" class="text-gray-700" />
                        <textarea id="content" name="content" rows="10" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition duration-150 ease-in-out" required>{{ old('content', $post->content) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('content')" />
                    </div>

                    <div>
                        <x-input-label for="tags" :value="__('Tags')" class="text-gray-700" />
                        <div class="mt-2 space-y-2">
                            @foreach($tags as $tag)
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}" 
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        {{ in_array($tag->id, old('tags', $post->tags->pluck('id')->toArray())) ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-600">{{ $tag->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('tags')" />
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ __('Update Post') }}
                        </x-primary-button>
                        <a href="{{ route('posts.show', $post) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout> 