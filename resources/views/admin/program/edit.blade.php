@extends('admin.layouts.app')

@section('title', '프로그램 정보 수정')

@push('styles')
<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container mx-auto p-8">
  <h1 class="text-4xl font-bold mb-8 text-gray-800">프로그램 정보 수정</h1>
  <div class="bg-white rounded-lg shadow-md p-6">
    <form action="{{ route('admin.program.update', $program->id) }}" method="POST" id="programForm">
      @csrf
      @method('PUT')
      <div class="mb-3">
        <label class="form-label">카테고리</label>
        <input type="text" name="category" class="form-control" value="{{ old('category', $program->category) }}" required>
        @error('category')
        <div class="text-danger small">{{ $message }}</div>
        @enderror
      </div>
      <div class="mb-3">
        <label class="form-label">프로그램명</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $program->name) }}" required>
        @error('name')
        <div class="text-danger small">{{ $message }}</div>
        @enderror
      </div>
      <div class="mb-3">
        <label class="form-label">설명</label>
        <textarea name="description" class="form-control" rows="2">{{ old('description', $program->description) }}</textarea>
        @error('description')
        <div class="text-danger small">{{ $message }}</div>
        @enderror
      </div>
      <div class="mb-3">
        <label class="form-label">매니저</label>
        <select name="manager_id" class="form-select" required>
          <option value="" disabled>강사를 선택하세요</option>
          @foreach ($managers as $manager)
          <option value="{{ $manager->id }}" @if(old('manager_id', $program->manager_id) == $manager->id) selected @endif>
            {{ $manager->user->name }} ({{ $manager->user->email }})
          </option>
          @endforeach
        </select>
        @error('manager_id')
        <div class="text-danger small">{{ $message }}</div>
        @enderror
      </div>
      <div class="mb-3">
        <label class="form-label">총 주차</label>
        <input type="number" name="total_week" class="form-control" value="{{ old('total_week', $program->total_week) }}">
        @error('total_week')
        <div class="text-danger small">{{ $message }}</div>
        @enderror
      </div>
      <div class="mb-3">
        <label class="form-label">정원</label>
        <input type="number" name="limit_count" class="form-control" value="{{ old('limit_count', $program->limit_count) }}">
        @error('limit_count')
        <div class="text-danger small">{{ $message }}</div>
        @enderror
      </div>
      <div class="mb-3">
        <label class="form-label">총 금액</label>
        <input type="number" name="total_price" class="form-control" value="{{ old('total_price', $program->total_price) }}">
        @error('total_price')
        <div class="text-danger small">{{ $message }}</div>
        @enderror
      </div>
      <div class="mb-3">
        <label class="form-label">상태</label>
        <select name="status" class="form-select">
          <option value="1" @if(old('status', $program->status)=="1") selected @endif>활성</option>
          <option value="0" @if(old('status', $program->status)=="0") selected @endif>비활성</option>
        </select>
        @error('status')
        <div class="text-danger small">{{ $message }}</div>
        @enderror
      </div>
      <div class="mb-3">
        <label class="form-label">승인 상태</label>
        <select name="approval_status" class="form-select">
          <option value="1" @if(old('approval_status', $program->approval_status)=="1") selected @endif>승인</option>
          <option value="2" @if(old('approval_status', $program->approval_status)=="2") selected @endif>승인 대기</option>
          <option value="3" @if(old('approval_status', $program->approval_status)=="3") selected @endif>승인 거부</option>
        </select>
        @error('approval_status')
        <div class="text-danger small">{{ $message }}</div>
        @enderror
      </div>
      <!-- 챕터 리스트 테이블 -->
      <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <label class="form-label mb-0">챕터 목록</label>
          <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addChapterModal">챕터 추가</button>
        </div>
        <table class="table table-bordered" id="chapterTable">
          <thead>
            <tr>
              <th>시작일</th>
              <th>종료일</th>
              <th>요일</th>
              <th>상태</th>
              <th>삭제</th>
            </tr>
          </thead>
          <tbody id="chapters-table-body">
            <!-- JS로 챕터 목록 렌더링 -->
          </tbody>
        </table>
      </div>
      <input type="hidden" name="chapters" id="chapters_hidden">
      <div class="d-flex justify-content-end mt-4">
        <button type="submit" class="btn btn-primary mr-4">프로그램 정보 수정</button>
        <a href="{{ route('admin.program.index') }}" class="btn btn-secondary">취소</a>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<!-- Bootstrap 5 JS and Popper -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
@endpush

<!-- 챕터 추가 모달 -->
<x-modal id="addChapterModal" title="챕터" button_id="addChapterBtn">
  <form id="chapterForm">
    <div class="mb-3">
      <label class="form-label">시작일/시간</label>
      <input type="datetime-local" id="chapterStart" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">종료일/시간</label>
      <input type="datetime-local" id="chapterEnd" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">요일</label>
      <input type="text" id="chapterWeekDays" class="form-control" placeholder="예: 월,수,금" required>
    </div>
    <div class="mb-3">
      <label class="form-label">상태</label>
      <select id="chapterStatus" class="form-select">
        <option value="1">활성</option>
        <option value="0">비활성</option>
      </select>
    </div>
  </form>  
</x-modal>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const addChapterBtn = document.getElementById('addChapterBtn');
    const chaptersTableBody = document.getElementById('chapters-table-body');
    const chapterForm = document.getElementById('chapterForm');
    const programForm = document.getElementById('programForm');
    const chaptersHidden = document.getElementById('chapters_hidden');
    // 기존 챕터 데이터 렌더링 (수정용)
    let chapters = [];
    @if(isset($program->chapters))
    chapters = @json($program->chapters);
    // 필요한 필드만 JS에서 추출
    chapters = chapters.map(function(c) {
      return {
        start: c.start,
        end: c.end,
        week_days: c.week_days,
        status: c.status
      };
    });
    @endif
    updateChaptersTable();
    addChapterBtn.addEventListener('click', function() {
      let start = document.getElementById('chapterStart').value;
      let end = document.getElementById('chapterEnd').value;
      function toDateTimeFormat(val) {
        if (!val) return '';
        let [date, time] = val.split('T');
        if (!date || !time) return val;
        if (time.length === 5) time += ':00';
        return `${date} ${time}`;
      }
      start = toDateTimeFormat(start);
      end = toDateTimeFormat(end);
      let week_days = document.getElementById('chapterWeekDays').value;
      const status = document.getElementById('chapterStatus').value;
      if (!start || !end || !week_days) {
        alert('모든 필드를 입력하세요.');
        return;
      }
      const weekMap = { '월': 0, '화': 1, '수': 2, '목': 3, '금': 4, '토': 5, '일': 6 };
      week_days = week_days
      .split(',')
      .map(day => weekMap[day.trim()] !== undefined ? weekMap[day.trim()] : day.trim())
      .filter(v => v !== '')
      .join(',');
      const newChapter = {
        start: start,
        end: end,
        week_days: week_days,
        status: status
      };
      chapters.push(newChapter);
      updateChaptersTable();
      chapterForm.reset();
      const modalElement = document.getElementById('addChapterModal');
      const modal = bootstrap.Modal.getInstance(modalElement);
      modal.hide();
    });
    function updateChaptersTable() {
      chaptersTableBody.innerHTML = '';
      chapters.forEach((chapter, index) => {
        const row = document.createElement('tr');
        row.className = 'text-gray-600';
        const startCell = document.createElement('td');
        startCell.textContent = chapter.start;
        const endCell = document.createElement('td');
        endCell.textContent = chapter.end;
        const weekDaysCell = document.createElement('td');
        weekDaysCell.textContent = chapter.week_days;
        const statusCell = document.createElement('td');
        statusCell.textContent = chapter.status == 1 ? '활성' : '비활성';
        const deleteCell = document.createElement('td');
        const delBtn = document.createElement('button');
        delBtn.type = 'button';
        delBtn.className = 'btn btn-danger btn-sm';
        delBtn.textContent = '삭제';
        delBtn.onclick = function() {
          chapters.splice(index, 1);
          updateChaptersTable();
        };
        deleteCell.appendChild(delBtn);
        row.appendChild(startCell);
        row.appendChild(endCell);
        row.appendChild(weekDaysCell);
        row.appendChild(statusCell);
        row.appendChild(deleteCell);
        chaptersTableBody.appendChild(row);
      });
      chaptersHidden.value = JSON.stringify(chapters);
    }
    programForm.addEventListener('submit', function() {
      chaptersHidden.value = JSON.stringify(chapters);
    });
  });
</script>
