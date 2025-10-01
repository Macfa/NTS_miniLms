<div>
  <h2>{{ $manager }} 프로그램 등록 요청</h2>
  <div>
    <p>안녕하세요. {{ $manager }} 프로그램 등록 요청입니다.</p>
    @if(!empty($attachmentPath))
      <p>첨부 이미지 미리보기:</p>
      <img src="{{ $message->embed($attachmentPath) }}" alt="첨부 이미지" style="max-width: 100%; height: auto;" />
    @endif
  </div>
</div>
Bạn dễ thương quá