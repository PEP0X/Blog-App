<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create Post') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg transform transition-all duration-300 hover:shadow-md">
            <div class="p-6">
                <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="title" :value="__('Title')" class="text-gray-700" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition duration-150 ease-in-out" :value="old('title')" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('title')" />
                    </div>

                    <div>
                        <x-input-label for="cover_image" :value="__('Cover Image')" class="text-gray-700" />
                        <input type="file" name="cover_image" id="cover_image" accept="image/jpeg,image/png,image/jpg,image/gif" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition duration-150 ease-in-out" onchange="previewImage(this)">
                        <input type="hidden" name="cropped_image" id="cropped_image">
                        <p class="text-gray-600 text-xs mt-1">Upload a cover image (JPEG, PNG, JPG, GIF) up to 5MB</p>
                        <x-input-error class="mt-2" :messages="$errors->get('cover_image')" />
                    </div>

                    <!-- Image Preview and Cropper Modal -->
                    <div id="imagePreviewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
                        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-3xl shadow-lg rounded-md bg-white">
                            <div class="mt-3">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Crop Image</h3>
                                <div class="mt-2">
                                    <img id="imagePreview" src="" class="max-w-full">
                                </div>
                                <div class="flex justify-end mt-4 space-x-3">
                                    <button type="button" onclick="cancelCrop()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                        Cancel
                                    </button>
                                    <button type="button" onclick="applyCrop()" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        Apply
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <x-input-label for="content" :value="__('Content')" class="text-gray-700" />
                        <textarea id="content" name="content" rows="10" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition duration-150 ease-in-out" required>{{ old('content') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('content')" />
                    </div>

                    <div>
                        <x-input-label for="tags" :value="__('Tags')" class="text-gray-700" />
                        <div class="mt-2 space-y-2">
                            @foreach($tags as $tag)
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}" 
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-600">{{ $tag->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('tags')" />
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            {{ __('Create Post') }}
                        </x-primary-button>
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
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

    <script>
        let cropper;
        const modal = document.getElementById('imagePreviewModal');
        const image = document.getElementById('imagePreview');

        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    image.src = e.target.result;
                    modal.classList.remove('hidden');
                    
                    if (cropper) {
                        cropper.destroy();
                    }
                    
                    cropper = new Cropper(image, {
                        aspectRatio: 16 / 9,
                        viewMode: 2,
                        autoCropArea: 1,
                        responsive: true,
                        restore: false,
                        modal: true,
                        guides: true,
                        highlight: true,
                        cropBoxMovable: true,
                        cropBoxResizable: true,
                        toggleDragModeOnDblclick: false,
                    });
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function cancelCrop() {
            modal.classList.add('hidden');
            document.getElementById('cover_image').value = '';
            if (cropper) {
                cropper.destroy();
            }
        }

        function applyCrop() {
            if (cropper) {
                const canvas = cropper.getCroppedCanvas({
                    width: 1200,
                    height: 675
                });
                
                document.getElementById('cropped_image').value = canvas.toDataURL('image/jpeg');
                modal.classList.add('hidden');
                cropper.destroy();
            }
        }
    </script>
</x-app-layout> 