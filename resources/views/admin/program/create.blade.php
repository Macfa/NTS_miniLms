@extends('admin.layouts.app')

@section('title', '프로그램 생성')

@push('styles')
<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container mx-auto p-8">
  <h1 class="text-4xl font-bold mb-8 text-gray-800">프로그램 생성</h1>
  
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
        <label class="form-label">프로그램명</label>
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
        <button type="submit" class="btn btn-primary mr-4">프로그램 생성</button>
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
    // 모달 '등록' 버튼을 가져옵니다.
    const addChapterBtn = document.getElementById('addChapterBtn');
    const chaptersTableBody = document.getElementById('chapters-table-body');
    const chapterForm = document.getElementById('chapterForm');
    const programForm = document.getElementById('programForm');
    const chaptersHidden = document.getElementById('chapters_hidden');
    
    // 챕터 데이터를 저장할 배열을 초기화합니다.
    let chapters = [];
    
    addChapterBtn.addEventListener('click', function() {
      // 기존 코드에서 title/description 대신 실제 입력값 사용
      let start = document.getElementById('chapterStart').value;
      let end = document.getElementById('chapterEnd').value;

      // yyyy-MM-ddTHH:mm → yyyy-MM-dd HH:mm:ss 변환 함수
      function toDateTimeFormat(val) {
        if (!val) return '';
        // Safari 등 일부 브라우저는 초가 붙을 수 있음
        let [date, time] = val.split('T');
        if (!date || !time) return val;
        if (time.length === 5) time += ':00'; // HH:mm → HH:mm:00
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
      
      // 요일 한글 → 숫자 변환 (0:월, 1:화, 2:수, 3:목, 4:금, 5:토, 6:일)
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
    
    // 챕터 목록을 테이블에 그리는 함수
    function updateChaptersTable() {
      chaptersTableBody.innerHTML = '';
      chapters.forEach((chapter, index) => {
        const row = document.createElement('tr');
        row.className = 'text-gray-600';
        // 시작일, 종료일, 요일, 상태, 삭제 버튼
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
      // 항상 최신 chapters 배열을 히든에 반영
      chaptersHidden.value = JSON.stringify(chapters);
    }
    
    // 폼 제출 시 chapters 배열을 히든에 JSON으로 저장
    programForm.addEventListener('submit', function() {
      chaptersHidden.value = JSON.stringify(chapters);
    });
  });
</script>
{{-- @endsection --}}
