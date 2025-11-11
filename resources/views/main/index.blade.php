@extends('main.layouts.app')

@section('content')
    <div class="container">
        <!-- 섹션 헤더 -->
        <div class="py-4 text-center">
            <h2 class="h4 mb-0">많은 강사들의 다양한 코스를 확인해보세요</h2>
        </div>
        <!-- 메인 그리드: 커리어 경로 카드 -->
        <div class="row g-4 mt-2">
          @foreach($courses as $course)
            <x-course-card id="{{ $course->id }}" title="{{ $course->title }}" image="{{ $course->image }}" description="{{ $course->description }}"></x-course-card>
          @endforeach
          <x-course-card title="Data Scientist" image="https://via.placeholder.com/640x360?text=Data+Scientist" description="Build predictive models using Python, R, and machine learning techniques."></x-course-card>

          <x-course-card title="Data Engineer" image="https://via.placeholder.com/640x360?text=Data+Engineer" description="Design and maintain data pipelines using SQL, Spark, and cloud platforms."></x-course-card>

          <x-course-card title="Machine Learning Engineer" image="https://via.placeholder.com/640x360?text=Machine+Learning+Engineer" description="Develop and deploy machine learning models using TensorFlow, PyTorch, and MLOps practices."></x-course-card>

          <x-course-card title="Business Analyst" image="https://via.placeholder.com/640x360?text=Business+Analyst" description="Bridge business needs and technical solutions using data analysis and visualization."></x-course-card>

          <x-course-card title="Cloud Data Engineer" image="https://via.placeholder.com/640x360?text=Cloud+Data+Engineer" description="Build and manage data solutions on cloud platforms like AWS, GCP, or Azure."></x-course-card>
    </div>
@endsection
