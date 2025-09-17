
@extends('admin.layouts.app')

@section('title', '챕터 수정')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container mx-auto p-8">
  <h1 class="text-4xl font-bold mb-8 text-gray-800">챕터 수정</h1>
  <div class="bg-white rounded-lg shadow-md p-6">
    <form action="{{ route('admin.chapter.update', $chapter->id) }}" method="POST" id="chapterForm">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label class="form-label">프로그램</label>
        <select name="program_id" class="form-select" required>
          <option value="" disabled>프로그램을 선택하세요</option>
          @foreach ($programs as $program)
          <option value="{{ $program->id }}" @if(old('program_id', $chapter->program_id) == $program->id) selected @endif>
            {{ $program->name }}
          </option>
          @endforeach
        </select>
        @error('program_id')
        <div class="text-danger small">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">시작일시</label>
        <input type="datetime-local" name="start" class="form-control" value="{{ old('start', $chapter->start) }}" required>
        @error('start')
        <div class="text-danger small">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">종료일시</label>
        <input type="datetime-local" name="end" class="form-control" value="{{ old('end', $chapter->end) }}" required>
        @error('end')
        <div class="text-danger small">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">수업 요일 (예: 월,수,금)</label>
        <input type="text" name="week_days" class="form-control" value="{{ old('week_days', $chapter->week_days) }}" required>
        @error('week_days')
        <div class="text-danger small">{{ $message }}</div>
        @enderror
      </div>
      
      <div class="mb-3">
        <label class="form-label">상태</label>
        <select name="status" class="form-select">
          <option value="0" @if(old('status', $chapter->getRawOriginal('status'))=="0") selected @endif>비활성화</option>
          <option value="1" @if(old('status', $chapter->getRawOriginal('status'))=="1") selected @endif>활성화</option>
        </select>
        @error('status')
        <div class="text-danger small">{{ $message }}</div>
        @enderror
      </div>

      <div class="d-flex justify-content-end mt-4">
        <button type="submit" class="btn btn-primary mr-4">챕터 수정</button>
        <a href="{{ route('admin.chapter.index') }}" class="btn btn-secondary">취소</a>
      </div>
    </form>
  </div>
</div>
@endsection

