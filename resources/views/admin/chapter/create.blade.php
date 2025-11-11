@extends('admin.layouts.app')

@section('title', '챕터 생성')

@push('styles')
<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container mx-auto p-8">
  <h1 class="text-4xl font-bold mb-8 text-gray-800">챕터 생성</h1>
  
  <div class="bg-white rounded-lg shadow-md p-6">
  <form action="{{ route('admin.curriculum.store') }}" method="POST" id="curriculumForm">
      @csrf
      

      <div class="mb-3">
        <label class="form-label">프로그램</label>
        <select name="Course_id" class="form-select" required>
          <option value="" disabled selected>프로그램을 선택하세요</option>
          @if(isset($Courses))
          @foreach ($Courses as $Course)
          <option value="{{ $Course->id }}" @if(old('Course_id') == $Course->id) selected @endif>
            {{ $Course->name }}
          </option>
          @endforeach
          @endif
        </select>
        @error('Course_id')
        <div class="text-danger small">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">시작일시</label>
        <input type="datetime-local" name="start" class="form-control" value="{{ old('start') }}" required>
        @error('start')
        <div class="text-danger small">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">종료일시</label>
        <input type="datetime-local" name="end" class="form-control" value="{{ old('end') }}" required>
        @error('end')
        <div class="text-danger small">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">수업 요일 (예: 월,수,금)</label>
        <input type="text" name="week_days" class="form-control" value="{{ old('week_days') }}" required>
        @error('week_days')
        <div class="text-danger small">{{ $message }}</div>
        @enderror
      </div>
      
      <div class="mb-3">
        <label class="form-label">상태</label>
        <select name="status" class="form-select">
          <option value="1" @if(old('status')=="1") selected @endif>활성</option>
          <option value="0" @if(old('status')=="0") selected @endif>비활성</option>
        </select>
        @error('status')
        {{ old('status')}}
        {{ $errors->first('status') }}
        {{-- {{ dd($errors->all()) }} --}}
        <div class="text-danger small">{{ $message }}</div>
        @enderror
      </div>

  <!-- 승인 상태는 챕터에는 필요 없음 -->
      <div class="d-flex justify-content-end mt-4">
  <button type="submit" class="btn btn-primary mr-4">챕터 생성</button>
  <a href="{{ route('admin.curriculum.index') }}" class="btn btn-secondary">취소</a>
      </div>
    </form>
  </div>
</div>
@endsection
