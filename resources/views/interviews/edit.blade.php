@extends('layouts.app')
@section('title','Edit Interview')
@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white border rounded shadow p-6">
        <h2 class="text-lg font-semibold mb-2">{{ $interview->title }}</h2>
        <p class="text-sm text-gray-500 mb-4">{{ $interview->description }}</p>

        <h3 class="font-medium mb-2">Questions</h3>
        <div class="space-y-3">
            @foreach($interview->questions as $q)
            <div class="p-3 border border-gray-100 rounded flex items-start justify-between gap-3">
                <div>
                    <div class="text-sm font-medium">Q{{ $q->order }}. {{ Str::limit($q->question_text, 120) }}</div>
                    <div class="text-xs text-gray-500 mt-1">Created {{ $q->created_at->diffForHumans() }}</div>
                </div>
                <div class="flex items-center gap-2">
                    <form method="POST" action="{{ route('questions.destroy', [$interview, $q]) }}">
                        @csrf @method('DELETE')
                        <button class="text-sm text-red-600 hover:underline">Delete</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <aside class="bg-white border rounded shadow p-6">
        <h3 class="font-medium mb-2">Add Question</h3>
        <form method="POST" action="{{ route('questions.store', $interview) }}">
            @csrf
            <div>
                <textarea name="question_text" rows="4" required
                    class="block w-full rounded-md border-gray-200 shadow-sm"></textarea>
                <x-input-error :messages="$errors->get('question_text')" />
            </div>
            <div class="mt-3 flex justify-end">
                <button class="px-3 py-2 bg-indigo-600 text-white rounded-md">Add Question</button>
            </div>
        </form>
    </aside>
</div>
@endsection
