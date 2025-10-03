# Laravel To-Do App (Inertia + Vue)

A simple To-Do List application built with **Laravel**, **Inertia.js**, and **Vue 3**.  
Supports full CRUD operations (Create, Read, Update, Delete) with MySQL storage.

## Features
- ✅ Add new tasks with optional notes
- ✅ Mark tasks as done (strikethrough like Google Keep)
- ✅ Edit tasks
- ✅ Delete tasks
- ✅ Stored in MySQL database
- ✅ Simple single-user setup (no login required)

## Requirements
- PHP 8+
- Composer
- Node.js & NPM
- MySQL

## Installation
```bash
# Clone the repository
git clone https://github.com/Jansen1207/laravel-todo-inertia.git
cd laravel-todo-inertia

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate

# Start development servers
php artisan serve   # backend
npm run dev         # frontend
