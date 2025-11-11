* 시나리오

** Entities

관리자 Admin
  - 강사의 프로그램 승인 등록 처리
  - 강사의 쿠폰 발급 횟수 제한

업체 Company
  - 예정 >>> 강사의 소속

강사 Manager
  - 강의 등록 및 프로그램 구성
  - 학생 생성주기 통계 관리

학생 Student
  - 프로그램 구매, 강의 수강
  - 예정 >>> 소속 지정 ( 학원 || 학교 )

강의 Course
  - 프로그램 구성
  - 대표 이미지 업로드

프로그램 Course
  - 첨부파일 관리
  - Url or Video 분기

쿠폰 Coupon
  - 다중 적용 가능
  - 기간 제한 존재 ( 시작, 종료일 )
  - 다중 적용 시, 사칙연산 순
  - 프로그램 대상으로 사용 가능
  - 발급 주체: 관리자, 강사

결제 Payment
  - Aggregate Root
  - 결제 항목 PaymentItem 기준: Course 단위(포함 Course 집합) 결제
  - 쿠폰 적용 AppliedCoupon (다중 적용, 순서 정책 반영)
  - 상태: Pending | Paid | Failed | Canceled | Refunded
  - 결제 성공 시 도메인 이벤트 PaymentCompleted 발행 → Enrollment 생성 트리거

결제 이력 PaymentHistory
  - Payment 상태/금액/게이트웨이 거래ID/사유 등의 변경 이력 저장
  - Append-only 감사 로그, 리스크/환불 분쟁 추적

수강 Enrollment
  - 학생(Student) ↔ 강의(Course) 매핑 엔티티
  - 생성 트리거: PaymentCompleted (Course 구매 시 포함된 Course 전개 후 생성)
  - 속성 예: enrolledAt, expiresAt, status(Active|Canceled|Refunded), sourcePaymentId

수강 이력 EnrollmentHistory
  - Enrollment 상태 변경 이력(등록, 취소, 환불, 만료)
  - Append-only, 규정 준수/감사 목적


** Vo