#!/bin/sh
# miniLms 통합 개발 서버 실행 스크립트 (strict 모드)
# 두 명령 중 하나라도 실패하면 즉시 종료
set -e

echo "[React] 개발 서버 실행... (백그라운드)"
npm --prefix resources/react/main run dev &
REACT_PID=$!

echo "[Laravel] 개발 서버 실행..."
sh -c 'composer run dev'

# 라라벨 dev 서버가 종료되면 React dev도 종료
kill $REACT_PID 2>/dev/null || true
