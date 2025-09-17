<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '학원 관리 시스템')</title>
    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="flex flex-col min-h-screen">
        <!-- 헤더 및 네비게이션 바 -->
        <header class="bg-gray-800 text-white p-4 shadow-md">
            <div class="container mx-auto flex justify-between items-center">
                <a href="/admin/dashboard" class="text-2xl font-bold rounded-lg">Mini LMS</a>
                <nav>
                  <a href="{{ route('admin.dashboard') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">대시보드</a>
                  <a href="{{ route('admin.student.index') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">회원 관리</a>
                  @can('is-admin')
                  <a href="{{ route('admin.manager.index') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">강사 관리</a>
                  @endcan
                  <a href="{{ route('admin.program.index') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">프로그램 관리</a>
                  <a href="{{ route('admin.chapter.index') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">챕터 관리</a>
                  {{-- <a href="{{ route('admin.payment.index') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">결제 관리</a> --}}

                    <!-- 로그아웃 버튼 -->
                    <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">로그아웃</button>
                    </form>
                </nav>
            </div>
        </header>

        <!-- 메인 콘텐츠 영역 -->
        <main class="flex-grow p-4">
            @yield('content')
        </main>

        <!-- 푸터 -->
        <footer class="bg-gray-800 text-gray-400 p-4 text-center text-sm mt-auto">
            &copy; 2024 Mini LMS. All Rights Reserved.
        </footer>
    </div>
    @stack('scripts')
</body>
</html>
