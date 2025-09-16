#!/bin/sh
# miniLms 통합 개발 서버 실행 스크립트 (strict 모드)
set -e

echo "[React] 프론트엔드 개발 서버 실행... (백그라운드)"
npm --prefix frontend run dev &
REACT_PID=$!

echo "[Laravel] 백엔드 개발 서버 실행..."
cd backend
php artisan serve

# Laravel dev 서버가 종료되면 React dev도 종료
kill $REACT_PID 2>/dev/null || true