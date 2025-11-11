<div class="col-12 col-md-6 col-xl-3">
    <div class="card h-100">
        <img src="{{ $image }}" class="card-img-top" alt="{{ $title }} Card">
        <div class="card-body">
            <h5 class="card-title">{{ $title }}</h5>
            <p class="card-text small text-muted">{{ $description }}</p>

            <div class="d-flex flex-wrap gap-2 mt-2">
                <span class="badge text-bg-primary">Excel</span>
                <span class="badge text-bg-secondary">SQL</span>
                <span class="badge text-bg-success">Tableau</span>
            </div>
            <div class="mt-2">
              <a href="{{ route('course.show', $id) }}" class="btn btn-primary">View Course</a>
            </div>
        </div>
    </div>
</div>