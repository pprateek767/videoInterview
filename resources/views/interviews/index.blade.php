@extends('layouts.app')

@section('title','Interviews')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Interviews</h1>
            <p class="mt-1 text-sm text-gray-600">Create interviews, add questions, and review candidate submissions.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <!-- Search -->
            <form method="GET" action="{{ route('interviews.index') }}" class="relative">
                <input name="q" value="{{ request('q') }}" placeholder="Search interviews..."
                    class="block w-64 pl-10 pr-4 py-2 border border-gray-200 rounded-lg shadow-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" />
                <svg class="w-4 h-4 absolute left-3 top-2.5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z" />
                </svg>
            </form>

            <!-- Create Interview (show only for admin/reviewer) -->
            @if(auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isReviewer()))
            <a href="{{ route('interviews.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <!-- plus icon -->
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create Interview
            </a>
            @endif
        </div>
    </div>

    <!-- List -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        @forelse($interviews as $i)
        <div class="bg-white border border-gray-100 rounded-lg shadow-sm overflow-hidden">
            <div class="p-4">
                <div class="flex items-start justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">{{ $i->title }}</h2>
                        <p class="mt-1 text-sm text-gray-500 line-clamp-3">{{ $i->description ?? 'No description' }}</p>
                    </div>
                    <div class="ml-4 flex-shrink-0 text-sm text-gray-500">
                        <div class="text-right">
                            <span class="block text-xs">Questions</span>
                            <span class="mt-1 inline-block text-lg font-medium text-indigo-600">{{ $i->questions_count
                                ?? $i->questions->count() }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        @if(auth()->user()->isCandidate())
                        <a href="{{ route('candidate.interview',$i) }}"
                            class="inline-flex items-center gap-2 px-3 py-1.5 border border-gray-200 rounded-md text-sm text-gray-700 hover:bg-gray-50">
                            Attempt
                        </a>
                        @endif
                    </div>

                    <div class="flex items-center gap-2">
                        @if(auth()->user()->isAdmin() || auth()->user()->isReviewer())
                        <a href="{{ route('interviews.edit', $i) }}" title="Edit"
                            class="inline-flex items-center gap-2 px-3 py-1.5 border border-gray-200 rounded-md text-sm text-gray-700 hover:bg-gray-50">
                            Edit
                        </a>

                        <a href="{{ route('submissions.list', $i) }}" title="Submissions"
                            class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-gray-200 rounded-md text-sm text-gray-700 hover:bg-gray-50">
                            Submissions
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <div
                class="border-t border-gray-100 px-4 py-3 bg-gray-50 flex items-center justify-between text-xs text-gray-500">
                <div>
                    Created by <span class="text-gray-700 font-medium">{{ optional($i->creator)->name ?? 'Unknown'
                        }}</span>
                </div>
                <div>
                    <time datetime="{{ $i->created_at }}">{{ $i->created_at->diffForHumans() }}</time>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-1 md:col-span-2 lg:col-span-3">
            <div class="text-center py-16 bg-white border border-dashed border-gray-200 rounded-lg">
                <p class="text-lg font-semibold text-gray-700">No interviews found</p>
                <p class="mt-2 text-sm text-gray-500">Create your first interview to get started.</p>
                @if(auth()->user()->isAdmin() || auth()->user()->isReviewer())
                <a href="{{ route('interviews.create') }}"
                    class="mt-4 inline-block px-4 py-2 bg-indigo-600 text-white rounded-md">Create Interview</a>
                @endif
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $interviews->links() }}
    </div>
</div>

<!-- Small JS: confirmation for destructive actions (if you add delete links later) -->
@push('scripts')
<script>
    document.addEventListener('click', function(e){
        const el = e.target.closest('[data-confirm]');
        if(!el) return;
        const msg = el.getAttribute('data-confirm') || 'Are you sure?';
        if(!confirm(msg)) {
            e.preventDefault();
            e.stopPropagation();
        }
    });
</script>
@endpush

@endsection
