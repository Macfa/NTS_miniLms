<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>접근 권한 없음 (403)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white rounded-lg shadow-xl p-10 text-center max-w-lg mx-auto">
        <h1 class="text-6xl font-bold text-gray-800 mb-4">403</h1>
        <p class="text-2xl font-semibold text-gray-700 mb-4">접근 권한이 없습니다.</p>
        <p class="text-gray-500 mb-8">
            죄송합니다. 이 페이지에 접근할 수 있는 권한이 없습니다.
            관리자에게 문의하거나, 필요한 경우 다른 페이지로 이동해주세요.
        </p>
        <div class="space-x-4">
            <a href="{{ url()->previous() }}" class="inline-block bg-indigo-600 text-white font-bold py-2 px-6 rounded-md shadow-md hover:bg-indigo-700 transition-colors">
                이전 페이지로 돌아가기
            </a>
            <a href="/" class="inline-block bg-gray-500 text-white font-bold py-2 px-6 rounded-md shadow-md hover:bg-gray-600 transition-colors">
                홈으로
            </a>
        </div>
    </div>
</body>
</html>
