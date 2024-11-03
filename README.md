# Project Management API

A Laravel-based RESTful API for managing projects, companies, and users. This API provides authentication, role-based authorization, and CRUD operations for managing companies, users, and projects.

## Table of Contents

- [Features](#features)  
- [Requirements](#requirements)  
- [Installation](#installation)  
- [Environment Configuration](#environment-configuration)  
- [Database Setup](#database-setup)  
- [Running the Project](#running-the-project)  
- [Authentication](#authentication)  
- [API Reference](#api-reference)  
- [Testing](#testing)  

## Features

- **User Authentication**: Uses Laravel Sanctum for API token-based authentication.
- **Role-Based Access Control**: Admin and User roles managed via Spatie's Permission package.
- **Project Types**: Supports different project types (Standard and Complex).
- **CRUD Operations**: Full CRUD support for companies, users, and projects.

## Requirements

- PHP 8.2 or above
- Composer
- MySQL or other supported database

## Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/alexandrosKapriniotis/project-management-api.git
   cd project-management-api
   ```

2. **Install PHP dependencies**:
   ```bash
   composer install
   ```

## Environment Configuration

1. **Create a `.env` file**:
   Copy `.env.example` to create your `.env` file.
   ```bash
   cp .env.example .env
   ```

2. **Generate application key**:
   ```bash
   php artisan key:generate
   ```

3. **Configure the database**:
   Open the `.env` file and set your database configuration:
   ```dotenv
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_user
   DB_PASSWORD=your_database_password
   ```

## Database Setup

1. **Run migrations**:
   ```bash
   php artisan migrate
   ```

2. **Seed the database** (optional):
   This will create the user roles (Admin, User) and some test data.
   ```bash
   php artisan db:seed
   ```

## Running the Project

Start the application server:

```bash
php artisan serve
```

Your API should now be accessible at `http://127.0.0.1:8000`.

## Authentication

The API uses Laravel Sanctum for authentication. Follow these steps to obtain a token and access protected endpoints:

1. **Login**:  
   Send a POST request to `/login` with valid credentials to get an access token:
   ```json
   {
       "email": "admin@example.com",
       "password": "password"
   }
   ```

2. **Authorization Header**:  
   Use the token in the `Authorization` header as a Bearer token for authenticated requests:
   ```
   Authorization: Bearer <your-token>
   ```

3. **Logout**:  
   Send a POST request to `/logout` to invalidate the current token.

## API Reference

### Auth Endpoints

#### Login

```http
  POST /login
```

| Parameter   | Type     | Description                           |
| :---------- | :------- | :------------------------------------ |
| `email`     | `string` | **Required**. The user’s email        |
| `password`  | `string` | **Required**. The user’s password     |

#### Logout

```http
  POST /logout
```

| Parameter       | Type     | Description                                   |
| :-------------- | :------- | :-------------------------------------------- |
| `Authorization` | `string` | **Required**. Bearer token for authentication |

### User Endpoints

#### Register a new user

```http
  POST /api/register
```

| Parameter       | Type     | Description                                   |
| :-------------- | :------- | :-------------------------------------------- |
| `name`          | `string` | **Required**. Name of the user                |
| `email`         | `string` | **Required**. Email address of the user       |
| `password`      | `string` | **Required**. Password for the user           |
| `password_confirmation` | `string` | **Required**. Confirm password  |
| `Authorization` | `string` | **Required**. Bearer token for authentication (admin access) |

#### Get all users

```http
  GET /api/users
```

| Parameter       | Type     | Description                                   |
| :-------------- | :------- | :-------------------------------------------- |
| `Authorization` | `string` | **Required**. Bearer token for authentication (admin access) |

#### Get user

```http
  GET /api/users/${id}
```

| Parameter       | Type     | Description                                   |
| :-------------- | :------- | :-------------------------------------------- |
| `id`            | `string` | **Required**. ID of the user to retrieve      |
| `Authorization` | `string` | **Required**. Bearer token for authentication (admin access) |

#### Update a user

```http
  PUT /api/users/${id}
```

| Parameter       | Type     | Description                                   |
| :-------------- | :------- | :-------------------------------------------- |
| `id`            | `string` | **Required**. ID of the user to update        |
| `name`          | `string` | **Optional**. New name of the user            |
| `email`         | `string` | **Optional**. New email of the user           |
| `password`      | `string` | **Optional**. New password for the user       |
| `Authorization` | `string` | **Required**. Bearer token for authentication (admin access) |

#### Delete a user

```http
  DELETE /api/users/${id}
```

| Parameter       | Type     | Description                                   |
| :-------------- | :------- | :-------------------------------------------- |
| `id`            | `string` | **Required**. ID of the user to delete        |
| `Authorization` | `string` | **Required**. Bearer token for authentication (admin access) |


### Company Endpoints

#### Get all companies

```http
  GET /api/companies
```

| Parameter       | Type     | Description                                   |
| :-------------- | :------- | :-------------------------------------------- |
| `Authorization` | `string` | **Required**. Bearer token for authentication |

#### Get company

```http
  GET /api/companies/${id}
```

| Parameter       | Type     | Description                                   |
| :-------------- | :------- | :-------------------------------------------- |
| `id`            | `string` | **Required**. ID of the company to retrieve   |
| `Authorization` | `string` | **Required**. Bearer token for authentication |

#### Create a company

```http
  POST /api/companies
```

| Parameter       | Type     | Description                                   |
| :-------------- | :------- | :-------------------------------------------- |
| `name`          | `string` | **Required**. Name of the company             |
| `address`       | `string` | **Required**. Address of the company          |
| `Authorization` | `string` | **Required**. Bearer token for authentication |

#### Update a company

```http
  PUT /api/companies/${id}
```

| Parameter       | Type     | Description                                   |
| :-------------- | :------- | :-------------------------------------------- |
| `id`            | `string` | **Required**. ID of the company to update     |
| `name`          | `string` | **Optional**. New name of the company         |
| `address`       | `string` | **Optional**. New address of the company      |
| `Authorization` | `string` | **Required**. Bearer token for authentication |

#### Delete a company

```http
  DELETE /api/companies/${id}
```

| Parameter       | Type     | Description                                   |
| :-------------- | :------- | :-------------------------------------------- |
| `id`            | `string` | **Required**. ID of the company to delete     |
| `Authorization` | `string`

### Project Endpoints

#### Get all projects

```http
  GET /api/projects
```

| Parameter       | Type     | Description                                   |
| :-------------- | :------- | :-------------------------------------------- |
| `Authorization` | `string` | **Required**. Bearer token for authentication |

#### Get project

```http
  GET /api/projects/${id}
```

| Parameter       | Type     | Description                                   |
| :-------------- | :------- | :-------------------------------------------- |
| `id`            | `string` | **Required**. ID of the project to retrieve   |
| `Authorization` | `string` | **Required**. Bearer token for authentication |

#### Create a project

```http
  POST /api/projects
```

| Parameter       | Type     | Description                                         |
| :-------------- | :------- | :-------------------------------------------------- |
| `name`          | `string` | **Required**. Name of the project                   |
| `description`   | `string` | **Optional**. Description of the project            |
| `type`          | `string` | **Required**. Type of project (e.g., Standard, Complex) |
| `company_id`    | `string` | **Required**. ID of the associated company          |
| `Authorization` | `string` | **Required**. Bearer token for authentication       |

#### Update a project

```http
  PUT /api/projects/${id}
```

| Parameter       | Type     | Description                                         |
| :-------------- | :------- | :-------------------------------------------------- |
| `id`            | `string` | **Required**. ID of the project to update           |
| `name`          | `string` | **Optional**. New name of the project               |
| `description`   | `string` | **Optional**. New description of the project        |
| `type`          | `string` | **Optional**. New type of project                   |
| `Authorization` | `string` | **Required**. Bearer token for authentication       |

#### Delete a project

```http
  DELETE /api/projects/${id}
```

| Parameter       | Type     | Description                                         |
| :-------------- | :------- | :-------------------------------------------------- |
| `id`            | `string` | **Required**. ID of the project to delete           |
| `Authorization` | `string` | **Required**. Bearer token for authentication       |


## Testing
   ```bash
   php artisan test
   ```
