<!doctype html>
<html lang="ko">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>프로그램 등록 요청</title>
	<style>
		/* 이메일 클라이언트 호환을 위해 최소 인라인/내장 스타일 사용 */
		body { margin:0; padding:0; background:#f6f7f9; color:#222; }
		.wrapper { width:100%; padding:24px 0; }
		.container { max-width:640px; margin:0 auto; background:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.06); }
		.header { padding:20px 24px; background:#0d6efd; color:#fff; }
		.header h1 { margin:0; font-size:20px; line-height:1.4; }
		.content { padding:24px; font-size:14px; line-height:1.7; }
		.content h2 { margin:0 0 12px; font-size:18px; color:#111; }
		.muted { color:#6c757d; font-size:12px; }
		.preview { margin-top:16px; text-align:center; }
		.preview img { max-width:100%; height:auto; border-radius:6px; border:1px solid #e9ecef; }
		.footer { padding:16px 24px; background:#fafbfc; color:#6c757d; font-size:12px; text-align:center; }
	</style>
	<!--[if mso]>
	<style>
		.container { width:640px !important; }
	</style>
	<![endif]-->
	</head>
<body>
	<div class="wrapper">
		<div class="container">
			<div class="header">
				<h1>프로그램 등록</h1>
			</div>
			<div class="content">

				<h2>{{ $manager ?? '담당자' }}님의 프로그램 등록 요청입니다.</h2>
				<p>
					프로그램명: <strong>{{ $title ?? '제목 미정' }}</strong>
				</p>

				@if(!empty($attachmentPath))
					<div class="preview">
						<p class="muted">첨부 이미지 미리보기</p>
						<img src="{{ $message->embed($attachmentPath) }}" alt="첨부 이미지">
					</div>
				@endif

				<p style="margin-top:20px;">관리자 페이지에서 상세 내용을 검토하고 승인/반려를 진행해 주세요.</p>
			</div>
			<div class="footer">
				본 메일은 발신 전용입니다. 문의가 필요하시면 관리자에게 연락해 주세요.
			</div>
		</div>
	</div>
</body>
</html>
