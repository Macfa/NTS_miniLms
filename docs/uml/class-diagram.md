# miniLms Class Diagram (Mermaid)

```mermaid
%%{init: {
  "themeVariables": { "fontSize": "18px" },
  "classDiagram": { "useMaxWidth": false, "nodeSpacing": 60, "rankSpacing": 80 }
}}%%
classDiagram
    direction TB

    class User {
      +id: int
      +name: string
      +email: string
      +password: string
      +status: int
      +role: string
      +admin(): Admin
      +student(): Student
      +manager(): Manager
    }

    class Admin {
      +id: int
      +user_id: int
      +role: string
      +user(): User
    }

    class Student {
      +id: int
      +user_id: int
      +user(): User
    }

    class Manager {
      +id: int
      +user_id: int
      +Courses(): Course[]
      +user(): User
    }

    class Course {
      +id: int
      +manager_id: int
      +category: string
      +name: string
      +description: text
      +total_week: int
      +limit_count: int
      +total_price: int
      +status: int
      +approval_status: int
      +curriculums(): Curriculum[]
      +manager(): Manager
      +user(): User // hasOneThrough via Manager
    }

    class Curriculum {
      +id: int
      +Course_id: int
      +start: datetime
      +end: datetime
      +week_days: string
      +status: int
      +Course(): Course
    }

    %% Relationships
    User <|-- Admin : hasOne
    User <|-- Student : hasOne
    User <|-- Manager : hasOne

    User "1" o-- "0..1" Admin : hasOne
    User "1" o-- "0..1" Student : hasOne
    User "1" o-- "0..1" Manager : hasOne

    Manager "1" --> "*" Course : hasMany
    Course "*" --> "1" Manager : belongsTo

    Course "1" --> "*" Curriculum : hasMany
    Curriculum "*" --> "1" Course : belongsTo

    %% Through relation (Course -> User via Manager)
    Course ..> User : hasOneThrough (via Manager)

    %% Polymorphic attachments (custom Attachment model)
    class Attachment {
      +id: int
      +attachable_type: string
      +attachable_id: int
      +type: string
      +path: string
      +meta: json
      +attachable(): (morphTo)
    }
    Attachment ..> Course : morphTo
    Attachment ..> Curriculum : morphTo
    Attachment ..> Manager : morphTo

    %% Spatie MediaLibrary integration
    class Media {
      +id: int
      +model_type: string
      +model_id: int
      +collection_name: string
      +mime_type: string
      +isImage(): bool
      +isVideo(): bool
    }
    Media ..> Course : morphTo (media)
    Media ..> Manager : morphTo (media)
```