import React from 'react';

const announcements = [
  {
    id: 1,
    message: '[공지] 2025년 2학기 신규 프로그램 오픈! 수강신청은 9월 20일부터 가능합니다.',
    contact: '문의: 02-1234-5678, 이메일: info@edulms.com',
  },
];

const Courses = [
  { id: 1, name: 'AI 기초', total_price: 120000, total_week: 8, status: 1 },
  { id: 2, name: '웹 개발 입문', total_price: 90000, total_week: 6, status: 1 },
  { id: 3, name: '데이터 분석', total_price: 150000, total_week: 10, status: 0 },
  { id: 4, name: '파이썬 중급', total_price: 110000, total_week: 7, status: 0 },
];

function CourseCard({ Course, color }) {
  return (
    <div className="col-md-6 col-lg-3 mb-4">
      <div className={`card h-100 border-${color} shadow-sm`}>
        <div className="card-body">
          <h5 className={`card-title text-${color}`}>{Course.name}</h5>
          <div className="mb-2">비용: <span className="fw-bold">{Course.total_price.toLocaleString()}원</span></div>
          <div className="mb-2">기간: {Course.total_week}주</div>
        </div>
      </div>
    </div>
  );
}

export default function MainPage() {
  const active = Courses.filter(p => p.status === 1);
  const pending = Courses.filter(p => p.status === 0);

  return (
    <div className="container py-4">
      <h1 className="mb-4 fw-bold text-primary">교육 프로그램 안내</h1>

      {/* 공지사항 */}
      <div className="mb-5">
        {announcements.map(a => (
          <div key={a.id} className="alert alert-warning fw-bold">
            <i className="bi bi-megaphone"></i> {a.message}<br />
            <span className="text-muted">※ {a.contact}</span>
          </div>
        ))}
      </div>

      {/* 운영 중인 프로그램 */}
      <h3 className="mb-3">현재 운영 중인 프로그램</h3>
      <div className="row mb-5">
        {active.length ? active.map(p => <CourseCard key={p.id} Course={p} color="primary" />) : (
          <div className="col-12"><div className="alert alert-info">운영 중인 프로그램이 없습니다.</div></div>
        )}
      </div>

      {/* 대기중인 프로그램 */}
      <h3 className="mb-3">대기중인 프로그램</h3>
      <div className="row">
        {pending.length ? pending.map(p => <CourseCard key={p.id} Course={p} color="secondary" />) : (
          <div className="col-12"><div className="alert alert-info">대기중인 프로그램이 없습니다.</div></div>
        )}
      </div>
    </div>
  );
}
