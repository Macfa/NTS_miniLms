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
    <form action="{{ route('admin.program.store') }}" method="POST" id="programForm">
      @csrf
      
      <div class="mb-3">
        <label class="form-label">카테고리</label>
        <input type="text" name="category" class="form-control" value="{{ old('category') }}" required>
        @error('category')
        <div class="text-danger small">{{ $message }}</div>
        @enderror
      </div>
      
      <div class="mb-3">
  <label class="form-label">챕터명</label>
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        @error('name')
        <div class="text-danger small">{{ $message }}</div>
        @enderror
      </div>
      
      <div class="mb-3">
        <label class="form-label">설명</label>
        <textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea>
        @error('description')
        <div class="text-danger small">{{ $message }}</div>
        @enderror
      </div>
      
      <div class="mb-3">
        <label class="form-label">매니저</label>
        <select name="manager_id" class="form-select" required>
          <option value="" disabled selected>강사를 선택하세요</option>
          @if(isset($managers))
          @foreach ($managers as $manager)
          <option value="{{ $manager->user->id }}" @if(old('manager_id') == $manager->user->id) selected @endif>
            {{ $manager->user->name }} ({{ $manager->user->email }})
          </option>
          @endforeach
          @endif
        </select>
        @error('manager_id')
        <div class="text-danger small">{{ $message }}</div>
        @enderror
      </div>
      
      <div class="mb-3">
        <label class="form-label">총 주차</label>
        <input type="number" name="total_week" class="form-control" value="{{ old('total_week') }}">
        @error('total_week')
        <div class="text-danger small">{{ $message }}</div>
        @enderror
      </div>
      
      <div class="mb-3">
        <label class="form-label">정원</label>
        <input type="number" name="limit_count" class="form-control" value="{{ old('limit_count') }}">
        @error('limit_count')
        <div class="text-danger small">{{ $message }}</div>
        @enderror
      </div>
      
      <div class="mb-3">
        <label class="form-label">총 금액</label>
        <input type="number" name="total_price" class="form-control" value="{{ old('total_price') }}">
        @error('total_price')
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
        <div class="text-danger small">{{ $message }}</div>
        @enderror
      </div>

      @can('is-admin')
<div class="mb-3">
  <label class="form-label">승인 상태</label>
  <select name="approval_status" class="form-select">
    <option value="1" @if(old('approval_status', $program->approval_status ?? '')=="1") selected @endif>승인</option>
    <option value="2" @if(old('approval_status', $program->approval_status ?? '')=="2") selected @endif>승인 대기</option>
    <option value="3" @if(old('approval_status', $program->approval_status ?? '')=="3") selected @endif>승인 거부</option>
  </select>
  @error('approval_status')
  <div class="text-danger small">{{ $message }}</div>
  @enderror
</div>
@endcan
      <div class="d-flex justify-content-end mt-4">
  <button type="submit" class="btn btn-primary mr-4">챕터 생성</button>
        <a href="{{ route('admin.program.index') }}" class="btn btn-secondary">취소</a>
      </div>
    </form>
  </div>
</div>
@endsection
