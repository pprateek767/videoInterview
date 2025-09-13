@extends('layouts.app')
@section('title','Edit Interview')
@section('content')
<div x-data="reviewModal()" x-cloak>
    <div class="bg-white border rounded shadow p-4">
        <h2 class="text-lg font-semibold">Submissions for {{ $interview->title }}</h2>
        <div class="mt-4 space-y-4">

            @foreach($submissions as $s)

            <div class="border rounded p-4 flex gap-4 items-start">
                <div class="w-48">
                    <video src="{{ Storage::url($s->video_path) }}" controls class="w-full rounded"></video>
                </div>
                <div class="flex-1">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="font-medium">Q: {{ $s->question->question_text }}</div>
                            <div class="text-sm text-gray-500">Candidate: {{ $s->candidate->name }}</div>
                            <div class="text-xs text-gray-400 mt-1">Submitted {{ $s->created_at->diffForHumans() }}
                            </div>
                        </div>
                        @if(!$s->review || !$s->review->score)
                        <button @click="openReview({{ $s->id }}, '{{ addslashes($s->candidate->name) }}')"
                            class="px-3 py-1.5 bg-indigo-600 text-white rounded">
                            Review
                        </button>
                        @endif
                    </div>

                    <div class="mt-3">
                        @foreach($s->reviews as $r)
                        <div class="p-2 bg-gray-50 rounded mb-2">
                            <div class="text-sm"><strong>{{ $r->reviewer->name }}</strong> — Score: {{ $r->score }}
                            </div>
                            <div class="text-xs text-gray-600 mt-1">{{ $r->comment }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $submissions->links() }}
        </div>
    </div>

    <div x-cloak>

        <!-- Modal -->
        <div x-show="open" x-transition class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">

            <div @click.away="close()" class="bg-white rounded-lg w-full max-w-lg p-6">
                <h3 class="font-semibold text-lg">Review submission</h3>
                <div class="mt-2 text-sm text-gray-600">
                    Candidate: <span x-text="candidateName"></span>
                </div>

                <form :action="`/submissions/${submissionId}/review`" method="POST" class="mt-4">
                    @csrf
                    <div>
                        <label class="text-sm">Score (0-10)</label>
                        <input type="number" name="score" min="0" max="10" required
                            class="w-20 block mt-1 rounded border-gray-200" />
                    </div>
                    <div class="mt-3">
                        <label class="text-sm">Comment</label>
                        <textarea name="comment" rows="4" class="block w-full mt-1 rounded border-gray-200"></textarea>
                    </div>
                    <div class="mt-4 flex justify-end gap-2">
                        <button type="button" @click="close()" class="px-3 py-2 border rounded">Cancel</button>
                        <button type="submit" class="px-3 py-2 bg-indigo-600 text-white rounded">Save Review</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


</div>
@push('scripts')
<script>
    function reviewModal() {
            return {
                open: false,              // 👈 closed by default
                submissionId: null,
                candidateName: '',
                openReview(id, name) {
                    this.submissionId = id;
                    this.candidateName = name;
                    this.open = true;     // 👈 only opens on button click
                },
                close() {
                    this.open = false;    // 👈 closes when Cancel clicked or background clicked
                    this.submissionId = null;
                    this.candidateName = '';
                }
            }
        }
</script>
@endpush
@endsection
