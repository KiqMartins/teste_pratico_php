# Teste Prático PHP - Technical Documentation


## 🚀 Live Demo

You can access the live project running on AWS here:
👉 **[Access Project](http://3.137.202.21:5173/)**

*(Note: Since this is a free tier instance, the initial load might take a few seconds)*


## 1. Methodology & Technologies

This project was built to demonstrate a robust, scalable, and modern approach to web development, separating the application into a decoupled Backend and Frontend.

### Backend (Pure PHP & Clean Architecture)
Instead of relying on a full-stack framework (like Laravel or Symfony), the backend was built from scratch using **PHP 8.2+** to demonstrate a deep understanding of **SOLID principles**, **Dependency Injection**, and **Domain-Driven Design (DDD)** patterns.

* **Architecture:** Hexagonal / Clean Architecture.
    * **Domain Layer:** Pure PHP entities and value objects (no external dependencies).
    * **Application Layer:** Use Cases representing specific business rules.
    * **Infrastructure Layer:** Database implementation, HTTP controllers, and frameworks.
* **Database:** MySQL 8.0 with **Doctrine ORM** for entity mapping and query building.
    * *Optimization:* Custom repository logic using **DBAL** to prevent N+1 query problems when fetching contacts and their phones.
* **PSR Standards:** Full compliance with PSR-4 (Autoloading), PSR-7 (HTTP Message), and PSR-11 (Container).

### Frontend (Vue.js Ecosystem)
The frontend was designed as a Single Page Application (SPA) focusing on reactivity and Type Safety.

* **Framework:** **Vue.js 3** (Composition API with `<script setup>`).
* **Language:** **TypeScript** for strict typing of API responses and components.
* **Styling:** **Tailwind CSS 3** for utility-first, responsive design.
* **State/Routing:** Vue Router for navigation.
* **Build Tool:** Vite for blazing-fast development and building.

### Infrastructure & DevOps
* **Containerization:** **Docker** and **Docker Compose** to orchestrate Nginx, PHP-FPM, MySQL, and Node.js containers.
* **Deployment:** Deployed on **AWS EC2** (Free Tier), configured with Linux Swap memory management to optimize resources on low-memory instances.

---

## 2. Project Structure

The project is divided into root infrastructure files, the backend source code, and the frontend directory.

```text
/
├── .env                  # Environment variables (DB credentials, API URL)
├── docker-compose.yml    # Container orchestration
├── docker/               # Docker configurations (Nginx, PHP)
├── public/               # Backend entry point (index.php)
│
├── src/                  # BACKEND SOURCE CODE
│   ├── Application/
│   │   └── UseCase/      # Business logic (e.g., CreateContact, ListContacts)
│   ├── Domain/
│   │   ├── Entity/       # Core business objects (User, Contact, Phone)
│   │   └── Repository/   # Interfaces for data persistence
│   └── Infrastructure/
│       ├── Http/         # Controllers and API handling
│       └── Persistence/  # Doctrine implementations and Mappings
│
└── frontend/             # FRONTEND SOURCE CODE
    ├── src/
    │   ├── assets/       # CSS and Static files
    │   ├── components/   # Reusable Vue components
    │   ├── http/         # Axios configuration (API client)
    │   ├── router/       # Vue Router routes
    │   ├── types/        # TypeScript interfaces (User, Contact, etc.)
    │   └── views/        # Main pages (Login, Contacts List, Form)
    ├── vite.config.ts    # Vite configuration
    └── tailwind.config.cjs # Tailwind configuration
```

---

## 3. API Routes

The backend exposes a RESTful API consumed by the frontend.

| Method | Endpoint         | Description                                                                                               |
|--------|------------------|-----------------------------------------------------------------------------------------------------------|
| POST   | /users           | Auth/Register. Creates a new user. In this MVP, it also acts as a login method, returning the User ID.    |
| GET    | /contacts        | List Contacts. Returns a paginated list of contacts for the logged user. Supports `?page=` and `?limit=`. |
| POST   | /contacts        | Create Contact. Accepts JSON payload with name, email, address, and an array of phone numbers.            |
| GET    | /contacts/{id}   | Read Contact. Fetches a single contact's details and their associated phone numbers.                      |
| PUT    | /contacts/{id}   | Update Contact. Updates contact info and synchronizes the phone numbers (add/remove).                     |
| DELETE | /contacts/{id}   | Delete Contact. Removes the contact and their cascade-linked data.                                        |

---

## 4. Possible Implementations

To evolve this project from an MVP (Minimum Viable Product) to a production-ready Enterprise application, the following steps are planned:

### **Authentication & Security**
- Replace the current `user_id` header mechanism with JWT (JSON Web Tokens) or session-based authentication.
- Implement password hashing (Argon2id/Bcrypt).
- Add HTTPS/SSL via Certbot/Let's Encrypt on AWS.

### **DevOps & CI/CD**
- Set up GitHub Actions for automated testing and deployment.
- Separate the frontend build process (serving static files via Nginx/S3) from the dev server.

### **Features**
- Add input validation middleware (Respect/Validation or similar).
- Implement soft deletes for contacts.
- Add user profile editing capabilities.