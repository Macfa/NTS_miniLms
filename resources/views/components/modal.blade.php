@props(['id', 'title', 'button_id'])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="{{ $id }}Label">{{ $title }}</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="닫기"></button>
			</div>
			<div class="modal-body">
				{{ $slot }}
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
				<button type="button" class="btn btn-primary" id="{{ $button_id }}">등록</button>
			</div>
		</div>
	</div>
</div>