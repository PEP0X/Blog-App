<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $post->title }}
        </h2>
        @can('update', $post)
            <x-slot name="actions">
                <div class="flex space-x-2">
                    <a href="{{ route('posts.edit', $post) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                    <form method="POST" action="{{ route('posts.destroy', $post) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150" onclick="return confirm('Are you sure you want to delete this post?')">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete
                        </button>
                    </form>
                </div>
            </x-slot>
        @endcan
    </x-slot>

    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg">
        @if($post->cover_image)
            <div class="relative w-full aspect-[16/9] overflow-hidden">
                <img src="{{ asset('storage/' . $post->cover_image) }}" alt="{{ $post->title }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 hover:scale-105">
            </div>
        @endif
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold">
                    {{ substr($post->user->name, 0, 1) }}
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">{{ $post->user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $post->created_at->format('M d, Y') }}</p>
                </div>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $post->title }}</h1>
            @if($post->tags->isNotEmpty())
                <div class="flex flex-wrap gap-2 mb-6">
                    @foreach($post->tags as $tag)
                        <a href="{{ route('posts.index', ['tag' => $tag->slug]) }}"
                           class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 hover:bg-indigo-200 transition-colors duration-300">
                            {{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            @endif
            <div class="prose max-w-none">
                {!! nl2br(e($post->content)) !!}
            </div>

            <!-- Reactions -->
            <div class="mt-8 border-t pt-6">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="flex -space-x-2">
                        @foreach($post->reactions->take(5) as $reaction)
                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-sm font-semibold">
                                {{ substr($reaction->user->name, 0, 1) }}
                            </div>
                        @endforeach
                        @if($post->reactions->count() > 5)
                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-sm font-semibold">
                                +{{ $post->reactions->count() - 5 }}
                            </div>
                        @endif
                    </div>
                    <div class="flex space-x-2">
                        @php
                            $reactionCounts = $post->reaction_counts;
                            $userReaction = $post->reactions->where('user_id', auth()->id())->first();
                        @endphp
                        <button onclick="react('{{ $post->id }}', 'post', 'like')"
                                class="flex items-center space-x-1 px-3 py-1 rounded-full {{ $userReaction?->type === 'like' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600' }} hover:bg-indigo-100 hover:text-indigo-600 transition-colors duration-200">
                            <span>👍</span>
                            <span>{{ $reactionCounts['like'] ?? 0 }}</span>
                        </button>
                        <button onclick="react('{{ $post->id }}', 'post', 'love')"
                                class="flex items-center space-x-1 px-3 py-1 rounded-full {{ $userReaction?->type === 'love' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600' }} hover:bg-indigo-100 hover:text-indigo-600 transition-colors duration-200">
                            <span>❤️</span>
                            <span>{{ $reactionCounts['love'] ?? 0 }}</span>
                        </button>
                        <button onclick="react('{{ $post->id }}', 'post', 'wow')"
                                class="flex items-center space-x-1 px-3 py-1 rounded-full {{ $userReaction?->type === 'wow' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600' }} hover:bg-indigo-100 hover:text-indigo-600 transition-colors duration-200">
                            <span>😮</span>
                            <span>{{ $reactionCounts['wow'] ?? 0 }}</span>
                        </button>
                        <button onclick="react('{{ $post->id }}', 'post', 'clap')"
                                class="flex items-center space-x-1 px-3 py-1 rounded-full {{ $userReaction?->type === 'clap' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600' }} hover:bg-indigo-100 hover:text-indigo-600 transition-colors duration-200">
                            <span>👏</span>
                            <span>{{ $reactionCounts['clap'] ?? 0 }}</span>
                        </button>
                        <button onclick="react('{{ $post->id }}', 'post', 'encourage')"
                                class="flex items-center space-x-1 px-3 py-1 rounded-full {{ $userReaction?->type === 'encourage' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600' }} hover:bg-indigo-100 hover:text-indigo-600 transition-colors duration-200">
                            <span>💪</span>
                            <span>{{ $reactionCounts['encourage'] ?? 0 }}</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="mt-8 border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Comments</h3>

                <!-- Comment Form -->
                <form action="{{ route('comments.store', $post) }}" method="POST" class="mb-6">
                    @csrf
                    <div class="flex space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="flex-1">
                            <textarea name="content" rows="2" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Write a comment..."></textarea>
                            <button type="submit" class="mt-2 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Post Comment
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Comments List -->
                <div class="space-y-6">
                    @foreach($post->comments()->with(['user', 'replies.user', 'reactions'])->latest()->get() as $comment)
                        <div class="flex space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold">
                                    {{ substr($comment->user->name, 0, 1) }}
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $comment->user->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                                        </div>
                                        @can('delete', $comment)
                                            <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                    <p class="mt-2 text-gray-700">{{ $comment->content }}</p>

                                    <!-- Comment Reactions -->
                                    <div class="mt-2 flex space-x-2">
                                        @php
                                            $commentReactionCounts = $comment->reaction_counts;
                                            $userCommentReaction = $comment->reactions->where('user_id', auth()->id())->first();
                                        @endphp
                                        <button onclick="react('{{ $comment->id }}', 'comment', 'like')"
                                                class="flex items-center space-x-1 px-2 py-1 rounded-full {{ $userCommentReaction?->type === 'like' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600' }} hover:bg-indigo-100 hover:text-indigo-600 transition-colors duration-200">
                                            <span>👍</span>
                                            <span>{{ $commentReactionCounts['like'] ?? 0 }}</span>
                                        </button>
                                        <button onclick="react('{{ $comment->id }}', 'comment', 'love')"
                                                class="flex items-center space-x-1 px-2 py-1 rounded-full {{ $userCommentReaction?->type === 'love' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600' }} hover:bg-indigo-100 hover:text-indigo-600 transition-colors duration-200">
                                            <span>❤️</span>
                                            <span>{{ $commentReactionCounts['love'] ?? 0 }}</span>
                                        </button>
                                    </div>

                                    <!-- Reply Form -->
                                    <form action="{{ route('comments.reply', $comment) }}" method="POST" class="mt-4">
                                        @csrf
                                        <div class="flex space-x-4">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold text-sm">
                                                    {{ substr(auth()->user()->name, 0, 1) }}
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <textarea name="content" rows="1" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" placeholder="Write a reply..."></textarea>
                                                <button type="submit" class="mt-2 inline-flex items-center px-3 py-1 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                    Reply
                                                </button>
                                            </div>
                                        </div>
                                    </form>

                                    <!-- Replies List -->
                                    @if($comment->replies->isNotEmpty())
                                        <div class="mt-4 space-y-4">
                                            @foreach($comment->replies as $reply)
                                                <div class="flex space-x-4">
                                                    <div class="flex-shrink-0">
                                                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold text-sm">
                                                            {{ substr($reply->user->name, 0, 1) }}
                                                        </div>
                                                    </div>
                                                    <div class="flex-1">
                                                        <div class="bg-gray-50 rounded-lg p-3">
                                                            <div class="flex justify-between items-start">
                                                                <div>
                                                                    <p class="font-medium text-gray-900 text-sm">{{ $reply->user->name }}</p>
                                                                    <p class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</p>
                                                                </div>
                                                            </div>
                                                            <p class="mt-1 text-gray-700 text-sm">{{ $reply->content }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        function react(id, type, reaction) {
            const reactableType = type === 'post' ? 'App\\Models\\Post' : 'App\\Models\\Comment';
            const currentButton = event.currentTarget;
            const isActive = currentButton.classList.contains('bg-indigo-100');

            console.log('Reacting:', {
                id,
                type,
                reaction,
                reactableType,
                isActive
            });

            // Add loading state
            currentButton.disabled = true;
            currentButton.classList.add('opacity-50');

            if (isActive) {
                // Remove reaction
                console.log('Removing reaction...');
                fetch('{{ route('reactions.destroy') }}', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        reactable_type: reactableType,
                        reactable_id: id
                    })
                })
                .then(response => {
                    console.log('Destroy response:', response);
                    return response.json();
                })
                .then(data => {
                    console.log('Destroy data:', data);
                    if (data.success) {
                        currentButton.classList.remove('bg-indigo-100', 'text-indigo-600');
                        currentButton.classList.add('bg-gray-100', 'text-gray-600');
                        const countSpan = currentButton.querySelector('span:last-child');
                        countSpan.textContent = parseInt(countSpan.textContent) - 1;

                        // Add animation
                        currentButton.classList.add('transform', 'scale-95');
                        setTimeout(() => {
                            currentButton.classList.remove('transform', 'scale-95');
                        }, 200);
                    }
                })
                .catch(error => {
                    console.error('Destroy Error:', error);
                })
                .finally(() => {
                    currentButton.disabled = false;
                    currentButton.classList.remove('opacity-50');
                });
            } else {
                // Add reaction
                console.log('Adding reaction...');
                fetch('{{ route('reactions.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        type: reaction,
                        reactable_type: reactableType,
                        reactable_id: id
                    })
                })
                .then(response => {
                    console.log('Store response:', response);
                    return response.json();
                })
                .then(data => {
                    console.log('Store data:', data);
                    if (data.success) {
                        currentButton.classList.remove('bg-gray-100', 'text-gray-600');
                        currentButton.classList.add('bg-indigo-100', 'text-indigo-600');
                        const countSpan = currentButton.querySelector('span:last-child');
                        countSpan.textContent = parseInt(countSpan.textContent) + 1;

                        // Add animation
                        currentButton.classList.add('transform', 'scale-110');
                        setTimeout(() => {
                            currentButton.classList.remove('transform', 'scale-110');
                        }, 200);
                    }
                })
                .catch(error => {
                    console.error('Store Error:', error);
                })
                .finally(() => {
                    currentButton.disabled = false;
                    currentButton.classList.remove('opacity-50');
                });
            }
        }
    </script>
</x-app-layout>
