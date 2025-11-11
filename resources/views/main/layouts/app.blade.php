<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'Mini LMS')</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        @stack('styles')
    </head>
    <body>
        <!-- 상단 네비게이션 바 -->
        <nav class="navbar navbar-light bg-body-tertiary border-bottom sticky-top">
            <div class="container py-2">
                <!-- 모바일 헤더: 좌 브랜드, 우 햄버거 -->
                <div class="d-flex d-lg-none w-100 justify-content-between align-items-center">
                    <a class="navbar-brand m-0" href="/">Mini LMS</a>
                    <div class="d-flex align-items-center gap-2">
                        <a class="btn btn-link text-body p-0" href="#" aria-label="Notifications"><i class="bi bi-bell"></i></a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMenu" aria-controls="mobileMenu" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                    </div>
                </div>

                <!-- PC 헤더: 좌 브랜드, 중앙 검색, 우 사용자/로그인 -->
                <div class="d-none d-lg-flex w-100 align-items-center">
                    <a class="navbar-brand fw-bold me-3" href="/">Mini LMS</a>
                    <div class="flex-grow-1 d-flex justify-content-center">
                        <form class="d-flex w-50" role="search">
                            <input class="form-control me-2" type="search" placeholder="What do you want to learn?" aria-label="Search">
                            {{-- <button class="btn btn-outline-primary" type="submit">Search</button> --}}
                        </form>
                    </div>
                    <ul class="list-unstyled d-flex ms-auto mb-0 align-items-center">
                      @auth
                        <li class="nav-item me-2">
                          <a class="nav-link position-relative" href="#" aria-label="Notifications"><i class="bi bi-bell"></i></a>
                        </li>
                        <li class="nav-item dropdown">
                          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ auth()->user()->name }}
                          </a>
                          <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">프로필</a></li>
                            <li><a class="dropdown-item" href="#">강의 목록</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                              <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">로그아웃</button>
                              </form>
                            </li>
                          </ul>
                        </li>
                      @else
                        <li class="nav-item">
                          <a class="nav-link" href="{{ route('login') }}">Log In</a>
                        </li>
                        <li class="nav-item ms-2">
                          <a class="btn btn-primary btn-sm" href="{{ route('register') }}">Join for Free</a>
                        </li>
                      @endauth
                    </ul>
                </div>

            </div>
        </nav>

    <!-- 모바일: 햄버거 클릭 시 노출되는 별도 리스트(헤더와 분리) -->
    <div class="collapse d-lg-none border-top" id="mobileMenu">
      <div class="container">
        <div class="p-3 bg-white border-bottom">
          @auth
            <div class="list-group">
              <a href="#" class="list-group-item list-group-item-action">프로필</a>
              <a href="#" class="list-group-item list-group-item-action">강의 목록</a>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="list-group-item list-group-item-action text-start">로그아웃</button>
              </form>
            </div>
          @else
            <div class="list-group">
              <a href="{{ route('login') }}" class="list-group-item list-group-item-action">로그인</a>
              <a href="#" class="list-group-item list-group-item-action">Join</a>
            </div>
          @endauth
          {{-- TODO: 필요시 여기(모바일 메뉴)에 더 많은 링크/카테고리 추가 --}}
        </div>
      </div>
    </div>

        <!-- 메인 콘텐츠 영역 -->
        <main class="py-4">
            @yield('content')
        </main>

        <!-- 푸터 -->
        <footer class="border-top py-4 mt-auto">
            <div class="container text-center text-muted small">
                &copy; 2024 Mini LMS. All Rights Reserved.
            </div>
        </footer>

        <!-- Bootstrap JS -->
        <!-- 버전 변경 또는 레포 정지 대응을 위해 로컬로 전환할 것 -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        @stack('scripts')
    </body>
    </html>
