# System Specification Document (SSD)
## Project: Post Tracking System

### 1. Introduction
This document defines the system architecture, underlying principles, and technical specifications for the Post Tracking System. The system manages shipment tracking, user accounts, post offices, and support tickets.

### 2. System Architecture
The application follows a decoupled client-server architecture:
- **Frontend (Presentation Layer):** Built with React.js, focusing strictly on displaying and interacting with data using component-based principles.
- **Backend (Application Layer):** A vanilla PHP API responsible for all core business logic, routing, and database interaction.
- **Database (Data Layer):** MySQL database (named `project`) managed via phpMyAdmin.

### 3. Backend Implementation & Request Lifecycle
The PHP backend operates using a custom MVC-like structure. The request lifecycle flows as follows:
1. **Entry Point (`index.php`):** Initializes session handling and sets CORS headers.
2. **Routing:** A custom `Router` class matches the request URI and HTTP method to a specific route strategy using regex patterns.
3. **Middleware Dispatch:** A `Dispatcher` wraps the request through middleware, specifically `BufferingMiddleware`, which manages output buffering and caching headers based on the response code.
4. **Security:** Route strategies are optionally wrapped in `SecurityDecorators` (using a Singleton `Security` class) to enforce authorization (e.g., authenticated user, admin, resource owner).
5. **Strategies:** The router returns a specific strategy (`GetStrategy`, `AddStrategy`, `UpdateStrategy`, `DeleteStrategy`) dictating the CRUD behavior.
6. **Controllers:** Strategies delegate the request to specific controllers (`UserController`, `ShipmentController`, `TrackingStatusController`, `PostOfficeController`, `SupportTicketController`). All controllers extend an `AbstractController`.
7. **Repositories:** Controllers interact with Repositories (e.g., `UserRepository`, `ShipmentRepository`) which implement a `RepositoryInterface`. Repositories execute raw SQL queries via PDO.
8. **Models & Response:** Repositories hydrate data into models (e.g., `User` model) that implement `JsonSerializable`. The data is finally returned to the client as a JSON-encoded HTTP response.

### 4. Design Patterns & Principles
The backend architecture strictly adheres to standard software engineering principles:
- **SOLID Principles:** Single Responsibility, Open/Closed, Liskov Substitution, Dependency Inversion.
- **DRY & KISS:** Centralized routing, reusable middleware, minimal controller logic.
- **Design Patterns:**
  - *Strategy Pattern:* Defines behavior for each HTTP method.
  - *Decorator Pattern:* Wraps objects to enforce security and authorization rules.
  - *Chain of Responsibility:* Manages middleware processing.
  - *Singleton:* Provides global access points for `Security` and `Database` instances.
  - *Repository Pattern:* Abstracts database interactions via SQL/PDO.

### 5. Infrastructure and Deployment
The system is fully containerized using Docker and Docker Compose. 
**Containers & Environments:**
- `react-frontend` (Port: 5137)
- `php-backend` (Port: 8000)
- `mysql-db` (Database: project, User: root)
- `pma` (phpMyAdmin, Port: 8080)
- `docs-server` (JS docs, Port: 6060)
- `storybook-server` (Storybook, Port: 6007)