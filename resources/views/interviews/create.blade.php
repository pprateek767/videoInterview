@extends('layouts.app')
@section('title','Create Interview')
@section('content')
<div class="bg-white border border-gray-100 rounded-lg shadow p-6">
    <h2 class="text-xl font-semibold mb-4">Create New Interview</h2>

    <form action="{{ route('interviews.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Title</label>
                <input name="title" value="{{ old('title') }}" required
                    class="mt-1 block w-full rounded-md border-gray-200 shadow-sm">
                <x-input-error :messages="$errors->get('title')" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" rows="4"
                    class="mt-1 block w-full rounded-md border-gray-200 shadow-sm">{{ old('description') }}</textarea>
                <x-input-error :messages="$errors->get('description')" />
            </div>

            <div class="flex items-center gap-3 justify-end">
                <a href="{{ route('interviews.index') }}" class="text-sm text-gray-600">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md">Create Interview</button>
            </div>
        </div>
    </form>
</div>
@endsection
