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
        
        <!-- 회원 검색 -->
        <div class="w-full md:w-1/3 relative">
            <input type="text" placeholder="이름 또는 이메일 검색..." class="w-full pl-10 pr-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
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
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->index+1 }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $student->user->name }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $student->user->email }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-green-500">{{ $student->status_text }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                          <a href="{{ route('admin.student.edit', $student) }}" class="text-indigo-600 hover:text-indigo-900">수정</a>
                          <!-- 삭제 버튼 (JavaScript에서 이 버튼에 이벤트를 연결) -->
                          <button id="delete-btn"
                            data-user-id="{{ $student->user_id }}"
                            data-route="{{ route('admin.student.destroy', $student->user_id) }}"
                            class="ml-4 text-red-600 hover:text-red-900">
                              삭제
                          </button>
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
        const deleteBtn = document.getElementById('delete-btn');

        deleteBtn.addEventListener('click', async function () {
            if (confirm('정말 이 학생을 삭제하시겠습니까?')) {
                const userId = this.getAttribute('data-user-id');
                const route = this.getAttribute('data-route');

                try {
                    const response = await fetch(route, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': {{ csrf_token() }}
                        }
                    });

                    if (response.ok) {
                        // 성공적으로 삭제되면 목록 페이지로 리다이렉트
                        window.location.href = "{{ route('admin.student.index') }}";
                    } else {
                        // 삭제 실패 시 에러 메시지
                        alert('삭제에 실패했습니다.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('네트워크 오류가 발생했습니다.');
                }
            }
        });
    });
</script>
{{-- @endpush --}}