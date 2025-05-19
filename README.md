# Mini CRM API

A simple Laravel-based CRM API with role-based access control (RBAC) for managing employees, customers, and customer actions.

---

## Features

- **User Authentication** (Register, Login, Logout) using Sanctum
- **Role-Based Access Control** with two roles: Admin and Employee
- Admin can:
  - Add, list, update, and delete employees
- Employees can:
  - Add customers (only assign to themselves)
  - Add, update, and view actions (call, visit, follow-up) on their assigned customers
- Secure API endpoints with appropriate middleware
- API Resource formatting and paginated responses
- Standardized API response wrapper

---

## API Endpoints Overview

### Auth

| Method | URI          | Description           | Auth Required | Role        |
|--------|--------------|-----------------------|---------------|-------------|
| POST   | /api/auth/register | Register new user    | No            | Any         |
| POST   | /api/auth/login    | Login user           | No            | Any         |
| POST   | /api/auth/logout   | Logout current user  | Yes           | Any         |

### Admin Employee Management

| Method | URI                  | Description         | Auth Required | Role  |
|--------|----------------------|---------------------|---------------|-------|
| POST   | /api/admin/employees  | Add employee        | Yes           | Admin |
| GET    | /api/admin/employees  | List employees      | Yes           | Admin |
| PUT    | /api/admin/employees/{id} | Update employee   | Yes           | Authenticated (any) |
| DELETE | /api/admin/employees/{id} | Delete employee   | Yes           | Admin |

### Customer Management

| Method | URI              | Description          | Auth Required | Role       |
|--------|------------------|----------------------|---------------|------------|
| POST   | /api/customers    | Add customer         | Yes           | Any Authenticated |

### Action Management (Employee only)

| Method | URI                | Description             | Auth Required | Role      |
|--------|--------------------|-------------------------|---------------|-----------|
| POST   | /api/actions       | Add action for customer  | Yes           | Employee  |
| GET    | /api/actions       | List employee actions    | Yes           | Employee  |
| PUT    | /api/actions/{id}  | Update an action         | Yes           | Employee  |

---

## Installation

1. Clone this repo:

   ```bash
   git clone https://github.com/yourusername/mini-crm.git
   cd mini-crm
2. Composer install

3. Copy .env.example to .env and configure your database and app settings.

4. Generate app key:

    ```bash
   php artisan key:generate
5. Run migrations:
     ```bash
   php artisan migrate

