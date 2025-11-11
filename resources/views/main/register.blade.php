<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>회원가입 | Mini LMS</title>
	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<!-- Bootstrap Icons (선택) -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
	<div class="container py-5">
		<div class="row justify-content-center">
			<div class="col-12 col-md-8 col-lg-5">
				<div class="card shadow-sm">
					<div class="card-body p-4">
						<div class="d-flex align-items-center justify-content-between mb-3">
							<h1 class="h5 mb-0">회원가입</h1>
							<a class="text-decoration-none" href="/">
								<i class="bi bi-house-door me-1"></i>홈으로
							</a>
						</div>

						<form method="POST" action="{{ route('register') }}">
							@csrf
							@if($errors->has('register'))
								<div class="alert alert-danger py-2" role="alert">{{ $errors->first('register') }}</div>
							@endif
							<div class="mb-3">
								<label for="name" class="form-label">이름</label>
								<input type="text" class="form-control" id="name" name="name" placeholder="홍길동" required autofocus value="{{ old('name') }}">
							</div>
							<div class="mb-3">
								<label for="email" class="form-label">이메일</label>
								<input type="email" class="form-control" id="email" name="email" placeholder="you@example.com" required value="{{ old('email') }}">
							</div>
							<div class="mb-3">
								<label for="password" class="form-label">비밀번호</label>
								<input type="password" class="form-control" id="password" name="password" required>
							</div>

							@error('name')
								<div class="alert alert-danger py-2" role="alert">{{ $message }}</div>
							@enderror
							@error('email')
								<div class="alert alert-danger py-2" role="alert">{{ $message }}</div>
							@enderror
							@error('password')
								<div class="alert alert-danger py-2" role="alert">{{ $message }}</div>
							@enderror
              
							<button type="submit" class="btn btn-primary w-100">회원가입</button>
						</form>

						<div class="text-center mt-3">
							<span class="text-muted small">이미 계정이 있으신가요?</span>
							<a href="{{ route('login') }}" class="small text-decoration-none">로그인</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Bootstrap JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
