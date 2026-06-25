# Task Dashboard

A simple kanban-style task management dashboard built with Laravel, Tailwind CSS, and Alpine.js.

## Tech Stack

- **Backend:** Laravel 11
- **Database:** MySQL
- **Frontend:** Tailwind CSS v4 + Alpine.js
- **Build Tool:** Vite

## Features

- Create tasks with a title, description, and status
- View tasks in a 3-column kanban board (Pending, In Progress, Completed)
- Update task status instantly via AJAX (no page reload)
- Delete tasks
- Search/filter tasks by title in real time
- Fully responsive design

## Prerequisites

Make sure you have these installed on your machine:

- **PHP** 8.2 or higher
- **Composer**
- **Node.js** 18+ and **npm**
- **MySQL** running locally

## Setup Instructions

### 1. Clone the repo

```bash
git clone https://github.com/Janith-D/task-dashboard.git
cd task-dashboard
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Set up the environment file

```bash
cp .env.example .env
```

Open `.env` and update your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_management
DB_USERNAME=root
DB_PASSWORD=
```

Make sure the database `task_management` exists in MySQL. You can create it with:

```bash
mysql -u root -p -e "CREATE DATABASE task_management"
```

### 4. Generate the app key

```bash
php artisan key:generate
```

### 5. Run migrations

```bash
php artisan migrate
```

### 6. Install frontend dependencies and build assets

```bash
npm install
npm run build
```

### 7. Start the dev server

```bash
php artisan serve
```

The app will be available at **http://127.0.0.1:8000**.

## Usage

- Click **New Task** to open the modal and create a task.
- Use the dropdown on any task card to change its status — the card will move to the correct column automatically.
- Hover over a card to reveal the **delete** button.
- Use the search bar in the header to filter tasks by title.
