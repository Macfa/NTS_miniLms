@extends('admin.layouts.app')

@section('title', '프로그램 관리')

@section('content')
<div class="container mx-auto p-8">
    <h1 class="text-4xl font-bold mb-8 text-gray-800">프로그램 관리</h1>

    <!-- 상단 섹션: 버튼 및 검색 필드 -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
        <!-- 프로그램 생성 버튼 -->
        <a href="{{ route('admin.program.create') }}" class="w-full md:w-auto bg-blue-600 text-white font-bold py-2 px-6 rounded-md shadow-md hover:bg-blue-700 transition-colors text-center">
            + 프로그램 생성
        </a>
        
        <!-- 프로그램 검색 -->
        <div class="w-full md:w-1/3 relative">
            <input type="text" placeholder="제목 검색..." class="w-full pl-10 pr-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- 하단 섹션: 프로그램 목록 테이블 -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ID
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            프로그램명
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            가격
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            승인 상태
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">작업</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  @foreach ($programs as $program)
                  <tr>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->index+1 }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $program->name }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($program->total_price) }} 원</td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                          {{ $program->approval_status }}
                        </span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                          <a href="{{ route('admin.program.edit', $program->id) }}" class="text-indigo-600 hover:text-indigo-900">수정</a>
                          <!-- 삭제 버튼 (JavaScript에서 이 버튼에 이벤트를 연결) -->
                          <button class="delete-program-btn ml-4 text-red-600 hover:text-red-900"
                            data-program-id="{{ $program->id }}"
                            data-route="{{ route('admin.program.destroy', $program->id) }}">
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-program-btn').forEach(button => {
            button.addEventListener('click', async function () {
                if (confirm('정말 이 프로그램을 삭제하시겠습니까?')) {
                    const programId = this.getAttribute('data-program-id');
                    const route = this.getAttribute('data-route');
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    try {
                        const response = await fetch(route, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            }
                        });

                        if (response.ok) {
                            window.location.href = "{{ route('admin.program.index') }}";
                        } else {
                            alert('삭제에 실패했습니다.');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('네트워크 오류가 발생했습니다.');
                    }
                }
            });
        });
    });
</script>
<meta name="csrf-token" content="{{ csrf_token() }}">
