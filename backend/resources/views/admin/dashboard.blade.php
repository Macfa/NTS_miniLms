@extends('admin.layouts.app')

@section('title', '관리자 대시보드')

@section('content')
<div class="container mx-auto p-8">
    <h1 class="text-4xl font-bold mb-8 text-gray-800">임시 관리자 대시보드</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- 대시보드 카드: 오늘 결제 건수 -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <span class="text-2xl font-semibold text-gray-700">오늘 결제</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c1.657 0 3 .895 3 2s-1.343 2-3 2-3-.895-3-2 1.343-2 3-2zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <!-- 대시보드 카드: 총 강좌 수 -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <span class="text-2xl font-semibold text-gray-700">총 강좌</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M12 14l9-5-9-5-9 5 9 5z" />
                    <path d="M12 14l6.16-3.422a4.99 4.99 0 00-.63-4.043l-5.53-3.132a5.003 5.003 0 00-6.16.63zM12 14l-6.16-3.422a4.99 4.99 0 01.63-4.043l5.53-3.132a5.003 5.003 0 016.16.63z" />
                    <path d="M21 12l-9-5-9 5" />
                    <path d="M21 12v6l-9 5-9-5v-6l9 5 9-5z" />
                </svg>
            </div>
        </div>

        <!-- 대시보드 카드: 총 학생 수 -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <span class="text-2xl font-semibold text-gray-700">총 학생</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h2a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h2m3-1h5m-5 0v1m5-1v1m-2-4v1m-1-4h1" />
                </svg>
            </div>
        </div>

        <!-- 대시보드 카드: 총 강사 수 -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <span class="text-2xl font-semibold text-gray-700">총 강사</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- 최근 결제 내역 -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">최근 결제 내역</h2>
            <div class="overflow-x-auto">
              ...
            </div>
        </div>

        <!-- 인기 강좌 순위 -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">인기 강좌 순위</h2>
            ...
        </div>
    </div>
</div>
@endsection
