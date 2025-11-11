@extends('main.layouts.app')

@section('content')
<div class="container py-4">
  <div class="row g-4">
    <!-- 메인 컬럼 -->
    <div class="col-lg-8 col-xl-9">
      <!-- 히어로 섹션 -->
      <section class="bg-dark text-white p-4 p-md-5 rounded-3 mb-4">
        <div class="mb-2 small text-white-50">
          <span class="me-2">Design</span>
          <i class="bi bi-slash-lg opacity-50"></i>
          <span class="ms-2">3D Modeling</span>
        </div>
        <h1 class="display-6 fw-bold mb-3">Learn Blender 3D by Following Along</h1>
        <p class="mb-4">Follow along and create things to get familiar with Blender and enjoy it. Upgrade your Blender skills step by step through short examples.</p>

        <!-- 평점/리뷰/수강생 수 -->
        <div class="d-flex flex-wrap align-items-center gap-3 mb-2">
          <div class="text-warning">
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-half"></i>
          </div>
          <div>(4.9) <span class="text-white-50">9 reviews</span></div>
          <div class="text-white-50">351 learners</div>
        </div>

        <!-- 강사 -->
        <div class="d-flex align-items-center gap-2 mb-3">
          <i class="bi bi-person-circle"></i>
          <span>codemod</span>
        </div>

        <!-- 태그 -->
        <div class="d-flex flex-wrap gap-2">
          <span class="badge bg-secondary">실습 중심</span>
          <span class="badge bg-secondary">무료</span>
          <span class="badge bg-secondary">예제</span>
          <span class="badge bg-secondary">Blender</span>
          <span class="badge bg-secondary">3d-modelling</span>
        </div>
      </section>

      <!-- 탭 메뉴 -->
      <ul class="nav nav-tabs" id="courseTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="about-tab" data-bs-toggle="tab" data-bs-target="#about" type="button" role="tab" aria-controls="about" aria-selected="true">About</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="curriculum-tab" data-bs-toggle="tab" data-bs-target="#curriculum" type="button" role="tab" aria-controls="curriculum" aria-selected="false">Curriculum</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">Reviews</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="community-tab" data-bs-toggle="tab" data-bs-target="#community" type="button" role="tab" aria-controls="community" aria-selected="false">Community</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="news-tab" data-bs-toggle="tab" data-bs-target="#news" type="button" role="tab" aria-controls="news" aria-selected="false">News</button>
        </li>
      </ul>

      <!-- 탭 콘텐츠 -->
      <div class="tab-content py-4" id="courseTabsContent">
        <div class="tab-pane fade show active" id="about" role="tabpanel" aria-labelledby="about-tab">
          <p class="mb-0">코스에 대한 간단한 소개 섹션입니다. 실제 콘텐츠/마크다운/이미지는 추후 채워 넣으세요.</p>
        </div>
        <div class="tab-pane fade" id="curriculum" role="tabpanel" aria-labelledby="curriculum-tab">
          <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <span>Introduction to Blender</span><span class="text-muted small">12m</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <span>Basic Modeling</span><span class="text-muted small">23m</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <span>Lighting & Rendering</span><span class="text-muted small">31m</span>
            </li>
          </ul>
        </div>
        <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
          <div class="row g-3">
            <div class="col-md-6">
              <div class="card h-100">
                <div class="card-body">
                  <div class="d-flex align-items-center gap-2 mb-2 text-warning">
                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                  </div>
                  <p class="mb-0">This collection has all the perfect lectures for learning Blender basics. Very helpful.</p>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card h-100">
                <div class="card-body">
                  <div class="d-flex align-items-center gap-2 mb-2 text-warning">
                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                  </div>
                  <p class="mb-0">Thank you, it was very helpful.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="community" role="tabpanel" aria-labelledby="community-tab">
          <p class="mb-0">커뮤니티 섹션 자리. Q&A / 공지 / 토론 등.</p>
        </div>
        <div class="tab-pane fade" id="news" role="tabpanel" aria-labelledby="news-tab">
          <p class="mb-0">업데이트/뉴스 영역.</p>
        </div>
      </div>

      <!-- 리뷰 섹션 (추가 블록) -->
      <section class="mt-4">
        <h3 class="h5 mb-3">Reviews from Early Learners</h3>
        <div class="row g-3">
          <div class="col-md-6">
            <div class="card h-100">
              <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-2 text-warning">
                  <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star"></i>
                </div>
                <p class="mb-0">This course was easy to follow and very practical.</p>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card h-100">
              <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-2 text-warning">
                  <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star"></i>
                </div>
                <p class="mb-0">Great starter content for Blender beginners.</p>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <!-- 사이드바 -->
    <div class="col-lg-4 col-xl-3">
      <aside class="position-sticky" style="top: 1rem;">
        <div class="card shadow-sm">
          <div class="card-body">
            <div class="fs-4 fw-bold mb-3">Free</div>
            <div class="d-flex gap-2 mb-3">
              <button class="btn btn-success flex-fill" onclick="buyNow({{ $course->id }})">구매</button>
              <button class="btn btn-outline-success flex-fill" onclick="addToCart({{ $course->id }})">장바구니</button>
            </div>
            <div class="d-flex gap-2 mb-3">
              <button class="btn btn-outline-secondary flex-fill"><i class="bi bi-folder me-1"></i>Folder</button>
              <button class="btn btn-outline-secondary flex-fill"><i class="bi bi-share me-1"></i>Share</button>
              <button class="btn btn-outline-secondary flex-fill"><i class="bi bi-heart me-1"></i>93</button>
            </div>
            <dl class="row small mb-0">
              <dt class="col-5 text-muted">Instructor</dt>
              <dd class="col-7">codemod</dd>

              <dt class="col-5 text-muted">Curriculum</dt>
              <dd class="col-7">20 lectures, 1 missions</dd>

              <dt class="col-5 text-muted">Running time</dt>
              <dd class="col-7">7hr 9min</dd>

              <dt class="col-5 text-muted">Course period</dt>
              <dd class="col-7">Unlimited</dd>

              <dt class="col-5 text-muted">Certificate</dt>
              <dd class="col-7">Not provided</dd>

              <dt class="col-5 text-muted">Level</dt>
              <dd class="col-7">Beginner - Basic - Intermediate</dd>
            </dl>
          </div>
        </div>
      </aside>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
async function addToCart(courseId){
  try {
    if(confirm('장바구니 담으시겠습니까 ?')) {
      const json = await fetch('/cart/add', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ course_id: courseId })
      })
      const msg = '장바구니에 담았습니다. 이동하시겠습니까?';
      if(confirm(msg)){
        window.location.href = '/cart';
      }
    }
  } catch(e){
    console.error(e);
    alert('장바구니 담기 중 오류가 발생했습니다.');
  }
}

async function buyNow(courseId){
  try {
    alert("단기정보 저장 로직 구현 중");
    return false;
    const json = await fetch('/cart/buy', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ course_id: courseId })
    })
    // 즉시 이동
    window.location.href = json.redirect || '/cart';
  } catch(e){
    console.error(e);
    alert('구매 처리 중 오류가 발생했습니다.');
  }
}
</script>
@endpush
