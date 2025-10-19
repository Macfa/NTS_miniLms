프로젝트 시나리오

주의
- 클래스 다이어그램 원본이 미제공 상태이므로, 아래는 가정 기반 예시 템플릿입니다. 대괄호[…]의 명칭을 실제 클래스/모듈 이름으로 치환하세요.
- 목적: 기능 흐름 정리와 책임 분리를 명확히 하여 컨트롤러-서비스-리포지토리 계층과 도메인 모델의 경계를 정리

역할/엔티티 매핑(다이어그램과 1:1 대응)
- 사용자/권한: [User], [Role], [AuthService]
- 강좌/구성: [Course], [Section], [Lecture]
- 등록/진도: [Enrollment], [ProgressTracker]
- 과제/제출/평가: [Assignment], [Submission], [Grade], [Rubric]
- 퀴즈/문항: [Quiz], [Question], [Choice], [QuizResult]
- 알림/이벤트: [Notification], [EventBus]
- 결제(선택): [Order], [Payment], [Refund]
- 저장소: [UserRepository], [CourseRepository], [EnrollmentRepository], [AssignmentRepository], [QuizRepository], [NotificationRepository]

공통 아키텍처 흐름
- Controller -> Service(도메인 규칙) -> Repository(영속) -> Domain(Entity/ValueObject)
- 트랜잭션 경계: Service
- 도메인 이벤트: 상태 변화 시 Event 발행 -> Notification/비동기 핸들러 구독

주요 시나리오

1) 회원가입/로그인
- 참여 클래스: [AuthController], [AuthService], [User], [UserRepository]
- 사전조건: 미인증 사용자
- 기본 흐름: 가입 요청 -> 중복검사 -> [User] 생성/저장 -> 로그인 -> 토큰 발급
- 예외: 중복 이메일, 비밀번호 정책 불만족
- 상태 변화: User.status=ACTIVE

2) 강좌 개설(강사)
- 참여 클래스: [CourseController], [CourseService], [Course], [Section], [Lecture], [CourseRepository]
- 사전조건: Role=INSTRUCTOR
- 기본 흐름: 강좌 초안 생성 -> 섹션/강의 추가 -> 검수 요청 -> 게시
- 예외: 권한 없음, 필수 필드 누락
- 상태 변화: Course.status=DRAFT/PUBLISHED

3) 수강신청(학습자)
- 참여 클래스: [EnrollmentController], [EnrollmentService], [Enrollment], [EnrollmentRepository], (선택)[Payment]
- 사전조건: Role=LEARNER, Course.status=PUBLISHED
- 기본 흐름: 신청 -> 결제(유료 시) -> Enrollment 생성
- 예외: 좌석 제한, 중복 신청, 결제 실패
- 상태 변화: Enrollment.status=ACTIVE

4) 학습 진행/진도 저장
- 참여 클래스: [LearningController], [ProgressService], [ProgressTracker], [Lecture]
- 사전조건: Enrollment.active
- 기본 흐름: 강의 재생 -> 구간 완료 -> 진도 업데이트 -> 완료 이벤트 발행
- 예외: 접근 권한 없음
- 상태 변화: ProgressTracker.rate 0->100

5) 과제 생성/제출/채점
- 참여 클래스: [AssignmentController], [AssignmentService], [Assignment], [Submission], [Grade]
- 사전조건: Role=INSTRUCTOR(생성), Role=LEARNER(제출)
- 기본 흐름: 과제 생성 -> 기한 내 제출 -> 채점 -> 성적 반영
- 예외: 마감 초과, 파일 형식 오류
- 상태 변화: Submission.status=SUBMITTED, Grade.published=true

6) 퀴즈 응시/자동채점
- 참여 클래스: [QuizController], [QuizService], [Quiz], [Question], [QuizResult]
- 사전조건: Quiz.open=true
- 기본 흐름: 응시 시작 -> 답안 제출 -> 자동채점 -> 결과 저장/공개
- 예외: 타임아웃, 재응시 제한
- 상태 변화: QuizResult.score 계산/저장

7) 알림 발송
- 참여 클래스: [EventBus], [NotificationService], [Notification]
- 트리거: 과제 채점 완료, 강좌 게시, 마감 임박
- 흐름: 도메인 이벤트 -> 구독자 처리 -> 채널별 발송(Email/Push/InApp)
- 예외: 채널 장애 -> 재시도/대기열

8) 성적/리포트 조회
- 참여 클래스: [ReportController], [ReportService], [Grade], [ProgressTracker], [Enrollment]
- 흐름: 집계 질의 -> 학습자/강사/관리자 관점 리포트 생성
- 예외: 권한 부족

책임 분리 요약
- Controller: 입력 검증, 응답 매핑
- Service: 도메인 규칙, 트랜잭션, 이벤트 발행
- Repository: 영속화, 조회 최적화
- Domain: 상태와 불변식 보장(ValueObject로 캡슐화)

실무 팁
- 조회 전용 쿼리(리포트)는 CQRS로 분리
- 마감/리마인더는 스케줄러와 이벤트 기반 혼용
- 대용량 제출/미디어는 객체 스토리지와 비동기 처리
- 필수 인덱스: Enrollment(userId, courseId), Progress(lectureId, enrollmentId), Submission(assignmentId, userId)

참고
- UML Use Case/Sequence 다이어그램 키워드: “UML use case”, “UML sequence”
- PlantUML: https://plantuml.com/ko/
- Mermaid: https://mermaid.js.org/