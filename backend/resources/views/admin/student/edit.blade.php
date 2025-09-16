@extends('admin.layouts.app')

@section('title', '회원 정보 수정')

@section('content')
<div class="container mx-auto p-8">
    <h1 class="text-4xl font-bold mb-8 text-gray-800">회원 정보 수정</h1>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.student.update', $student->user_id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- 역할 필드를 숨겨서 학생으로 고정 -->
            <input type="hidden" name="role" value="student">

            <!-- 이름 입력 필드 -->
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">이름</label>
                <input type="text" id="name" name="name" value="{{ old('name', $student->user->name) }}" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- 이메일 입력 필드 -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">이메일</label>
                <input type="email" id="email" name="email" value="{{ old('email', $student->user->email) }}" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- 비밀번호 입력 필드 -->
            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">비밀번호</label>
                <input type="password" id="password" name="password" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-gray-500 text-xs mt-1">비밀번호를 변경하려면 입력하세요.</p>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">비밀번호 확인</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('password_confirmation')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- 상태 선택 필드 -->
            <div class="mb-6">
                <label for="status" class="block text-gray-700 text-sm font-bold mb-2">상태</label>
                <select id="status" name="status" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="1" @if(old('status', $student->user->status) == 1) selected @endif>활성화</option>
                    <option value="0" @if(old('status', $student->user->status) == 0) selected @endif>비활성화</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- 버튼 그룹 -->
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-4 rounded-md hover:bg-blue-700 transition-colors">
                    정보 수정
                </button>
                <a href="{{ route('admin.student.index') }}" class="inline-block bg-gray-500 text-white font-bold py-2 px-4 rounded-md hover:bg-gray-600 transition-colors">
                    취소
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
