@extends('main.layouts.app')

@section('content')
<div class="container py-4">
  <div class="row g-4">
    <!-- 메인 컬럼 -->
    <div class="col-lg-8 col-xl-9">
      <!-- 헤더/타이틀 -->
      <section class="bg-dark text-white p-4 p-md-5 rounded-3 mb-4">
        <div class="mb-2 small text-white-50">Your Learning Cart</div>
        <h1 class="display-6 fw-bold mb-3">수강 바구니</h1>
        <p class="mb-0">결제 전 담아둔 코스 목록입니다. 쿠폰 적용 및 체크아웃을 진행하세요.</p>
      </section>

      <!-- 카트 아이템 목록 -->
      <div class="card mb-4 shadow-sm">
        <div class="card-header bg-white fw-semibold">담은 코스</div>
        <ul class="list-group list-group-flush" id="cart-items">
          <!-- 예시 아이템 (설명용) 실제 데이터 루프 적용 예정) -->
          @foreach($cart_items as $item)
            <li class="list-group-item d-flex align-items-center justify-content-between">
              <div class="d-flex align-items-center gap-3">
                <div class="ratio ratio-16x9" style="width: 120px;">
                  <div class="bg-secondary rounded" style="background-size: cover; background-position: center;"></div>
                </div>
                <div>
                  <div class="fw-semibold">{{ $item->course->title }}</div>
                  <div class="small text-muted">강사 {{ $item->course->user->name }}</div>
                  {{-- <div class="small text-muted">강사 {{ $item->course->user->name }} · 7hr 9min · Beginner</div> --}}
                  {{-- <div class="small mt-1">
                    <span class="badge bg-secondary">무료</span>
                    <span class="badge bg-secondary">실습 중심</span>
                  </div> --}}
                </div>
              </div>
              <div class="text-end" style="min-width: 140px;">
                <div class="fw-bold">₩{{ $item->course->total_price }}</div>
                <form method="POST" action="#" class="mt-2">
                  <!-- @csrf 실제 구현 시 추가 -->
                  <button type="submit" class="btn btn-sm btn-outline-danger">제거</button>
                </form>
              </div>
            </li>          
          @endforeach
          <li class="list-group-item d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
              <div class="ratio ratio-16x9" style="width: 120px;">
                <div class="bg-secondary rounded" style="background-size: cover; background-position: center;"></div>
              </div>
              <div>
                <div class="fw-semibold">Blender 입문 따라하기</div>
                <div class="small text-muted">강사 codemod · 7hr 9min · Beginner</div>
                <div class="small mt-1">
                  <span class="badge bg-secondary">무료</span>
                  <span class="badge bg-secondary">실습 중심</span>
                </div>
              </div>
            </div>
            <div class="text-end" style="min-width: 140px;">
              <div class="fw-bold">₩0</div>
              <form method="POST" action="#" class="mt-2">
                <!-- @csrf 실제 구현 시 추가 -->
                <button type="submit" class="btn btn-sm btn-outline-danger">제거</button>
              </form>
            </div>
          </li>
          <li class="list-group-item d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
              <div class="ratio ratio-16x9" style="width: 120px;">
                <div class="bg-secondary rounded" style="background-size: cover; background-position: center;"></div>
              </div>
              <div>
                <div class="fw-semibold">Laravel 서비스 레이어</div>
                <div class="small text-muted">강사 codemod · 3hr 40min · Intermediate</div>
                <div class="small mt-1">
                  <span class="badge bg-secondary">아키텍처</span>
                  <span class="badge bg-secondary">DDD</span>
                </div>
              </div>
            </div>
            <div class="text-end" style="min-width: 140px;">
              <div class="fw-bold">₩49,000</div>
              <form method="POST" action="#" class="mt-2">
                <!-- @csrf 실제 구현 시 추가 -->
                <button type="submit" class="btn btn-sm btn-outline-danger">제거</button>
              </form>
            </div>
          </li>
          <!-- /예시 아이템 -->
        </ul>
      </div>

      <!-- 쿠폰 적용 영역 -->
      <div class="card mb-4 shadow-sm">
        <div class="card-header bg-white fw-semibold">쿠폰 적용</div>
        <div class="card-body">
          <form method="POST" action="#" class="row g-3 align-items-center">
            <!-- @csrf 실제 구현 시 추가 -->
            <div class="col-md-5">
              <label for="coupon" class="form-label small text-muted">쿠폰 코드</label>
              <input type="text" id="coupon" name="coupon" class="form-control" placeholder="예: SAVE10" />
            </div>
            <div class="col-md-3">
              <label class="form-label small text-muted d-block">&nbsp;</label>
              <button type="submit" class="btn btn-outline-primary w-100">적용</button>
            </div>
            <div class="col-md-4">
              <div class="small text-muted">유효한 쿠폰 적용 시 합계가 즉시 갱신됩니다.</div>
            </div>
          </form>
        </div>
      </div>

      <!-- 결제 정보/합계 -->
      <div class="card mb-4 shadow-sm">
        <div class="card-header bg-white fw-semibold">결제 예정 금액</div>
        <div class="card-body">
          <table class="table table-sm mb-3">
            <tbody class="align-middle">
              <tr>
                <th scope="row" class="text-muted">상품금액</th>
                <td class="text-end">₩49,000</td>
              </tr>
              <tr>
                <th scope="row" class="text-muted">쿠폰 할인</th>
                <td class="text-end text-success">- ₩4,900</td>
              </tr>
              <tr class="table-active">
                <th scope="row" class="text-muted">결제 합계</th>
                <td class="text-end fw-bold">₩44,100</td>
              </tr>
            </tbody>
          </table>
          <div class="d-flex gap-2">
            <a href="#" class="btn btn-outline-secondary">계속 둘러보기</a>
            <form method="POST" action="#" class="ms-auto">
              <!-- @csrf 실제 구현 시 추가 -->
              <button type="submit" class="btn btn-success px-4">체크아웃 진행</button>
            </form>
          </div>
        </div>
      </div>

      <!-- 안내/도움말 -->
      <div class="alert alert-info small" role="alert">
        세션 기반 카트 아이템 루프, 가격 집계 서비스, 쿠폰 검증(만료/적용범위), 금액 계산 VO
      </div>
    </div>

    <!-- 사이드바 -->
    <div class="col-lg-4 col-xl-3">
      <aside class="position-sticky" style="top: 1rem;">
        <div class="card shadow-sm mb-4">
          <div class="card-body">
            <h5 class="fw-semibold mb-3">결제 진행 안내</h5>
            <ul class="list-unstyled small mb-0">
              <li class="mb-2"><i class="bi bi-check-circle text-success me-1"></i> 수강 시작은 결제 완료 후 즉시</li>
              <li class="mb-2"><i class="bi bi-check-circle text-success me-1"></i> 쿠폰은 1회 1개만 적용</li>
              <li class="mb-2"><i class="bi bi-check-circle text-success me-1"></i> 무료 코스는 자동 0원 처리</li>
              <li class="mb-2"><i class="bi bi-check-circle text-success me-1"></i> 가상 결제 게이트웨이로 테스트</li>
            </ul>
          </div>
        </div>
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="fw-semibold mb-3">지원</h5>
            <p class="small mb-2">이상이 있으면 test@test.com 로 문의하세요.</p>
            <a class="btn btn-outline-secondary btn-sm w-100" href="#">문의하기</a>
          </div>
        </div>
      </aside>
    </div>
  </div>
</div>
@endsection
