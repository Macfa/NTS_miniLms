@extends('admin.layouts.app')

@section('title', '회원 관리')

@section('content')
<div class="container mx-auto p-8">
    <h1 class="text-4xl font-bold mb-8 text-gray-800">회원 관리</h1>

    <!-- 상단 섹션: 버튼 및 검색 필드 -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
        <!-- 회원 생성 버튼 -->
        <a href="{{ route('admin.student.create') }}" class="w-full md:w-auto bg-blue-600 text-white font-bold py-2 px-6 rounded-md shadow-md hover:bg-blue-700 transition-colors text-center">
            + 회원 생성
        </a>
        <!-- 일괄 삭제 버튼 -->
        <button id="bulk-delete-btn" class="w-full md:w-auto bg-red-600 text-white font-bold py-2 px-6 rounded-md shadow-md hover:bg-red-700 transition-colors text-center">
            선택 삭제
        </button>
        <!-- 회원 검색 -->
        <div class="w-full md:w-1/3 relative">
            <input type="text" name="searchStudentKeyword" placeholder="이름 또는 이메일 검색..." class="w-full pl-10 pr-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- 하단 섹션: 회원 목록 테이블 -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" id="check-all">
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ID
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            이름
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            이메일
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            상태
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">작업</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  @foreach ($students as $student)
                  <tr>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                          <input type="checkbox" class="row-check" value="{{ $student->user_id }}">
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->index+1 }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $student->user->name }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $student->user->email }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-green-500">{{ $student->user->status }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                          <a href="{{ route('admin.student.edit', $student) }}" class="text-indigo-600 hover:text-indigo-900">수정</a>
                      </td>
                  </tr>
                  @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


{{-- 푸터에 추가할 예정 --}}
{{-- @push('scripts') --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.querySelector('input[name="searchStudentKeyword"]');
        const tableBody = document.querySelector('table tbody');
        const originalRows = Array.from(tableBody.children);

        searchInput.addEventListener('input', async function () {
            const keyword = searchInput.value;

            if (keyword.length > 0) {
                try {
                    const url = `{{ route('admin.student.search') }}?keyword=${encodeURIComponent(keyword)}`;
                    const response = await fetch(url, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    const result = await response.json();
                    if (result.status) {
                        console.error(result.message || '검색 실패');
                        return;
                    }
                    const students = result.data;
                    tableBody.innerHTML = '';
                    students.forEach((student, idx) => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                              <input type="checkbox" class="row-check" value="${student.user_id}">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${idx + 1}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${student.user.name}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${student.user.email}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-500">${student.user.status}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="/admin/student/${student.id}/edit" class="text-indigo-600 hover:text-indigo-900">수정</a>
                            </td>
                        `;
                        tableBody.appendChild(tr);
                    });
                } catch (error) {
                    console.error('검색 중 오류가 발생했습니다.');
                }
            } else {
                // 검색어가 없으면 원래 테이블로 복원
                tableBody.innerHTML = '';
                originalRows.forEach(row => tableBody.appendChild(row.cloneNode(true)));
            }
        });

        // 체크박스 전체 선택/해제
        document.getElementById('check-all').addEventListener('change', function() {
            const checked = this.checked;
            document.querySelectorAll('.row-check').forEach(cb => cb.checked = checked);
        });

        // 일괄 삭제 버튼 이벤트
        document.getElementById('bulk-delete-btn').addEventListener('click', async function() {
            const checked = Array.from(document.querySelectorAll('.row-check:checked')).map(cb => cb.value);
            if (checked.length === 0) {
                console.error('삭제할 학생을 선택하세요.');
                return;
            }
            if (!confirm('정말 선택한 학생을 삭제하시겠습니까?')) return;
            try {
                const response = await fetch("{{ route('admin.student.destroyMany') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ user_ids: checked })
                });
                const result = await response.json();
                if (result.status) {
                    console.error(result.message || '검색 실패');
                    return;
                }
                // 체크된 행 삭제
                document.querySelectorAll('.row-check:checked').forEach(cb => cb.closest('tr').remove());
                // console.log(result.message);
            } catch (e) {
                console.error('네트워크 오류', e);
            }
        });
    });
</script>
{{-- @endpush --}}