@extends('admin.layouts.app')

@section('title', '프로그램 정보 상세')

@section('content')
<div class="container mx-auto p-8">
    <h1 class="text-4xl font-bold mb-8 text-gray-800">프로그램 정보 상세</h1>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <!-- 프로그램명 표시 -->
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">프로그램명</label>
            <p class="w-full px-4 py-2 border rounded-md bg-gray-100 text-gray-800">{{ $program->name }}</p>
        </div>

        <!-- 담당 강사 표시 -->
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">담당 강사</label>
            <p class="w-full px-4 py-2 border rounded-md bg-gray-100 text-gray-800">{{ $program->teacher->user->name }}</p>
        </div>
        
        <!-- 상태 표시 -->
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">상태</label>
            <p class="w-full px-4 py-2 border rounded-md bg-gray-100 text-gray-800">
                @if ($program->status == 'active')
                    활성화
                @else
                    비활성화
                @endif
            </p>
        </div>
        
        <!-- 버튼 그룹 -->
        <div class="flex items-center justify-start mt-6">
            <a href="{{ route('admin.program.edit', $program->id) }}" class="bg-blue-600 text-white font-bold py-2 px-4 rounded-md hover:bg-blue-700 transition-colors">
                정보 수정
            </a>
            <a href="{{ route('admin.program.index') }}" class="inline-block bg-gray-500 text-white font-bold py-2 px-4 rounded-md hover:bg-gray-600 transition-colors ml-4">
                목록으로
            </a>
        </div>
    </div>
</div>
@endsection
