@extends('admin.layouts.app')

@section('title', '프로그램 관리')

@section('content')
<div class="container mx-auto p-8">
	<h1 class="text-4xl font-bold mb-8 text-gray-800">프로그램 관리</h1>
	
	<!-- 상단 섹션: 버튼 및 검색 필드 -->
	<div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
		<!-- 프로그램 생성 버튼 -->
		<a href="{{ route('admin.Course.create') }}" class="w-full md:w-auto bg-blue-600 text-white font-bold py-2 px-6 rounded-md shadow-md hover:bg-blue-700 transition-colors text-center">
			+ 프로그램 생성
		</a>
		<!-- 일괄 삭제 버튼 -->
		<button id="bulk-delete-btn" class="w-full md:w-auto bg-red-600 text-white font-bold py-2 px-6 rounded-md shadow-md hover:bg-red-700 transition-colors text-center">
			선택 삭제
		</button>
    @can('is-admin')
      <button id="bulk-approval-btn" class="w-full md:w-auto bg-red-600 text-white font-bold py-2 px-6 rounded-md shadow-md hover:bg-red-700 transition-colors text-center">
        선택 승인
      </button>
      <button id="bulk-rejection-btn" class="w-full md:w-auto bg-red-600 text-white font-bold py-2 px-6 rounded-md shadow-md hover:bg-red-700 transition-colors text-center">
        선택 반려
      </button>
    @endcan
		<!-- 프로그램 검색 -->
		<div class="w-full md:w-1/3 relative">
			<input type="text" name="searchCourseKeyword" placeholder="제목 검색..." class="w-full pl-10 pr-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
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
							<input type="checkbox" id="check-all">
						</th>
						<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
							ID
						</th>
						<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
							대표 이미지
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
					@foreach ($Courses as $Course)
					<tr>
						<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
							<input type="checkbox" class="row-check" value="{{ $Course->id }}">
						</td>
						<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->index+1 }}</td>
						<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
							@php
							$media = $Course->getMedia('*')->first();
							@endphp
							@if($media && $media->isImage())
							<img src="{{ $media->getUrl() }}" alt="대표 이미지" style="width: 80px; height: auto; border-radius: 8px;">
							@elseif($media && $media->isVideo())
							<video src="{{ $media->getUrl() }}" style="width: 80px; height: auto; border-radius: 8px;" controls></video>
							@elseif($media)
							<a href="{{ $media->getUrl() }}" target="_blank">파일 보기</a>
							@else
							<span class="text-gray-400">없음</span>
							@endif                                              
						</td>
						<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $Course->name }}</td>
						<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($Course->total_price) }} 원</td>
						<td class="px-6 py-4 whitespace-nowrap">
							<span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
								{{ $Course->approval_status }}
							</span>
						</td>
						<td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
							<a href="{{ route('admin.Course.edit', $Course->id) }}" class="text-indigo-600 hover:text-indigo-900">수정</a>
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
		const searchInput = document.querySelector('input[name="searchCourseKeyword"]');
		const tableBody = document.querySelector('table tbody');
		const originalRows = Array.from(tableBody.children);
		
		searchInput.addEventListener('input', async function () {
			const keyword = searchInput.value;
			
			if (keyword.length > 0) {
				try {
					const url = `{{ route('admin.Course.search') }}?keyword=${encodeURIComponent(keyword)}`;
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
					const Courses = result.data;
					
					tableBody.innerHTML = '';
					Courses.forEach((Course, idx) => {
						const tr = document.createElement('tr');
						tr.innerHTML = `
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                              <input type="checkbox" class="row-check" value="${Course.id}">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${idx + 1}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${Course.name}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${Course.description}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-500">${Course.status}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="/admin/Course/${Course.id}/edit" class="text-indigo-600 hover:text-indigo-900">수정</a>
                            </td>
                        `;
						tableBody.appendChild(tr);
					});
				} catch (error) {
					alert('검색 중 오류가 발생했습니다.');
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
		
    document.getElementById('bulk-approval-btn').addEventListener('click', async function() {
      const checked = Array.from(document.querySelectorAll('.row-check:checked')).map(cb => cb.value);
      if (checked.length === 0) {
        alert('승인할 프로그램을 선택하세요.');
        return;
      }
      if (!confirm('정말 선택한 프로그램을 승인하시겠습니까?')) return;
      
      try {
        const response = await fetch("{{ route('admin.Course.approvalMany') }}", {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({ Course_ids: checked, approval_status: 1 })
        });
        const result = await response.json();
        if (result.status) {
          console.error(result.message || '검색 실패');
          return;
        }
        // 체크된 행 삭제
        // document.querySelectorAll('.row-check:checked').forEach(cb => cb.closest('tr').remove());
        location.reload();
        
      } catch (e) {
        alert('네트워크 오류가 발생했습니다.');
      }
    });

    document.getElementById('bulk-rejection-btn').addEventListener('click', async function() {
      const checked = Array.from(document.querySelectorAll('.row-check:checked')).map(cb => cb.value);
      if (checked.length === 0) {
        alert('반려할 프로그램을 선택하세요.');
        return;
      }
      if (!confirm('정말 선택한 프로그램을 반려하시겠습니까?')) return;
      
      try {
        const response = await fetch("{{ route('admin.Course.rejectionMany') }}", {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({ Course_ids: checked, approval_status: -1 })
        });
        const result = await response.json();
        if (result.status) {
          console.error(result.message || '검색 실패');
          return;
        }
        // 체크된 행 삭제
        // document.querySelectorAll('.row-check:checked').forEach(cb => cb.closest('tr').remove());
        location.reload();
        
      } catch (e) {
        alert('네트워크 오류가 발생했습니다.');
      }
    });

		// 일괄 삭제 버튼 이벤트
		document.getElementById('bulk-delete-btn').addEventListener('click', async function() {
			const checked = Array.from(document.querySelectorAll('.row-check:checked')).map(cb => cb.value);
			if (checked.length === 0) {
				alert('삭제할 강사를 선택하세요.');
				return;
			}
			if (!confirm('정말 선택한 강사를 삭제하시겠습니까?')) return;
			
			try {
				const response = await fetch("{{ route('admin.manager.destroyMany') }}", {
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
				
			} catch (e) {
				alert('네트워크 오류가 발생했습니다.');
			}
		});
	});  
	document.addEventListener('DOMContentLoaded', function () {
		document.querySelectorAll('.delete-Course-btn').forEach(button => {
			button.addEventListener('click', async function () {
				if (confirm('정말 이 프로그램을 삭제하시겠습니까?')) {
					const CourseId = this.getAttribute('data-Course-id');
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
						const result = await response.json();
						if (result.status) {
							console.error(result.message || '삭제 실패');
							return;
						}
						window.location.href = "{{ route('admin.Course.index') }}";
					} catch (error) {
						console.error('Error:', error);
						alert('네트워크 오류가 발생했습니다.');
					}
				}
			});
		});
	});
</script>
