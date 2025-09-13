@extends('layouts.app')
@section('title', $interview->title ?? 'Interview')
@section('content')
<div class="bg-white border rounded shadow p-6">
    <div class="flex items-start justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold">{{ $interview->title }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ $interview->description }}</p>
        </div>
        <div class="text-sm text-gray-500">Questions: {{ $interview->questions->count() }}</div>
    </div>

    <div x-data="interviewPlayer()" class="mt-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left: question / recorder -->
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-gray-50 border border-gray-100 rounded p-4">
                    <h3 class="font-medium">Question <span x-text="currentIndex + 1"></span> of <span
                            x-text="questions.length"></span></h3>
                    <p class="mt-2 text-gray-700" x-text="currentQuestionText"></p>
                </div>

                <div class="bg-white border rounded p-4">
                    <div class="w-full max-w-xl mx-auto">
                        <!-- Live camera preview -->
                        <video id="preview" class="w-full rounded bg-black" autoplay playsinline muted></video>

                        <!-- Controls -->
                        <div class="mt-3 flex items-center gap-3">
                            <button x-bind:disabled="isRecording || hasSubmitted(currentQuestion.id)" @click="start()"
                                class="px-3 py-2 bg-red-600 text-white rounded-md"
                                x-text="isRecording ? 'Recording...' : 'Start'"></button>

                            <button x-bind:disabled="!isRecording" @click="stop()"
                                class="px-3 py-2 bg-gray-200 rounded-md">Stop</button>

                            <div class="ml-auto text-sm text-gray-600">Time: <span x-text="formatTime(timer)"></span>
                            </div>
                        </div>

                        <!-- Preview + Submit -->
                        <div class="mt-3 space-y-2" x-show="previewMode && !hasSubmitted(currentQuestion.id)">
                            <div>
                                <div class="text-sm text-gray-600 mb-1">Preview your answer:</div>
                                <video :src="lastPreview" controls class="w-full rounded"></video>
                            </div>
                            <div class="flex justify-end gap-2">
                                <button @click="discard()" class="px-3 py-2 border rounded">Discard</button>
                                <button @click="submitAnswer()"
                                    class="px-3 py-2 bg-indigo-600 text-white rounded">Submit Answer</button>
                            </div>
                        </div>

                        <!-- Success message -->
                        <div class="mt-3 text-green-600 font-medium" x-show="hasSubmitted(currentQuestion.id)">
                            Answer submitted ✅
                        </div>

                        <!-- Upload progress -->
                        <div x-show="uploading" class="mt-3 w-full bg-gray-100 rounded overflow-hidden">
                            <div :style="{ width: uploadProgress + '%' }" class="h-2 bg-indigo-600"></div>
                            <div class="text-xs text-gray-600 mt-1">Uploading... <span x-text="uploadProgress"></span>%
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="flex gap-2">
                    <button @click="prev()" class="px-3 py-2 border rounded disabled:opacity-50"
                        :disabled="currentIndex===0">Previous</button>
                    <button @click="next()" class="px-3 py-2 border rounded disabled:opacity-50"
                        :disabled="currentIndex===questions.length-1">Next</button>
                    <div class="ml-auto text-sm text-gray-500">Progress: <strong
                            x-text="(currentIndex+1) + '/' + questions.length"></strong></div>
                </div>
            </div>

            <!-- Right: question list -->
            <aside class="bg-white border rounded p-4">
                <h4 class="font-medium mb-3">Questions</h4>
                <ul class="space-y-2">
                    @foreach($interview->questions as $idx => $q)
                    <li class="flex items-center gap-3">
                        <button @click="goTo({{ $idx }})"
                            class="flex-1 text-left px-3 py-2 rounded hover:bg-gray-50 flex justify-between items-center"
                            :class="{'bg-indigo-50 border-l-4 border-indigo-600': currentIndex==={{ $idx }}}">
                            <div class="text-sm font-medium">Q{{ $idx+1 }}. {{ Str::limit($q->question_text,60) }}</div>
                            <!-- ✅ checkmark if submitted -->
                            <span x-show="submittedQuestions.includes({{ $q->id }})" class="text-green-600">✔</span>
                        </button>
                    </li>
                    @endforeach
                </ul>
            </aside>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function interviewPlayer(){
  return {
    questions: @json($interview->questions->map(fn($q)=>['id'=>$q->id,'text'=>$q->question_text])),
    currentIndex: 0,
    mediaStream: null,
    mediaRecorder: null,
    recordedChunks: [],
    isRecording: false,
    timer: 0,
    timerInterval: null,
    uploading: false,
    uploadProgress: 0,
    lastPreview: null,
    previewMode: false,
    submittedQuestions: [],

    get currentQuestion(){ return this.questions[this.currentIndex] ?? null },
    get currentQuestionText(){ return this.currentQuestion ? this.currentQuestion.text : '' },

    formatTime(sec){
      const m = Math.floor(sec/60).toString().padStart(2,'0');
      const s = Math.floor(sec%60).toString().padStart(2,'0');
      return `${m}:${s}`;
    },

    hasSubmitted(qid){ return this.submittedQuestions.includes(qid); },

    async initStream(){
      if(this.mediaStream) return;
      try {
        this.mediaStream = await navigator.mediaDevices.getUserMedia({ video:true, audio:true });
        document.getElementById('preview').srcObject = this.mediaStream;
      } catch(e){
        alert('Could not access camera/mic. Please allow permissions.');
      }
    },

    async start(){
      await this.initStream();
      this.recordedChunks = [];
      this.lastPreview = null;
      this.previewMode = false;
      const options = { mimeType: 'video/webm; codecs=vp8,opus' };
      this.mediaRecorder = new MediaRecorder(this.mediaStream, options);
      this.mediaRecorder.ondataavailable = (e)=> { if(e.data.size>0) this.recordedChunks.push(e.data); };
      this.mediaRecorder.onstop = ()=> this.onStopped();
      this.mediaRecorder.start();
      this.isRecording = true;
      this.timer = 0;
      this.timerInterval = setInterval(()=> this.timer++, 1000);
    },

    stop(){
      if(!this.mediaRecorder) return;
      this.mediaRecorder.stop();
      this.isRecording = false;
      clearInterval(this.timerInterval);
    },

    async onStopped(){
      const blob = new Blob(this.recordedChunks, { type: 'video/webm' });
      this.lastPreview = URL.createObjectURL(blob);
      this.previewMode = true;
    },

    discard(){
      this.lastPreview = null;
      this.previewMode = false;
    },

    async submitAnswer(){
      if(!this.lastPreview) return alert("No recording to submit");
      const blob = new Blob(this.recordedChunks, { type: 'video/webm' });
      const fd = new FormData();
      fd.append('video', blob, `answer_${this.currentQuestion.id}.webm`);
      fd.append('duration_sec', this.timer);

      this.uploading = true;
      this.uploadProgress = 0;

      const token = document.querySelector('meta[name="csrf-token"]').content;
      try {
        const res = await fetch(`/interview/{{ $interview->id }}/question/${this.currentQuestion.id}/submit`, {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': token },
          body: fd
        });
        if(!res.ok) throw new Error('Upload failed');
        await res.json();
        this.uploadProgress = 100;
        this.submittedQuestions.push(this.currentQuestion.id); // mark submitted
        this.previewMode = false;
        setTimeout(()=> this.uploading=false, 700);
      } catch(err){
        console.error(err);
        alert('Upload failed. Try again.');
        this.uploading = false;
      }
    },

    prev(){ if(this.currentIndex>0) this.currentIndex--; },
    next(){ if(this.currentIndex < this.questions.length-1) this.currentIndex++; },
    goTo(i){ this.currentIndex = i; this.initStream(); }
  }
}
</script>
@endpush
@endsection
