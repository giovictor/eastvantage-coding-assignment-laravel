# User Management System API

*Disclaimer: This repository is for technical assessment only.*

User and role management system built with Laravel and Laravel Sail.

*Note: This is only the repository for the backend. Please make sure to clone and use it together with the <a href="https://github.com/giovictor/eastvantage-coding-assignment-react" target="_blank">User Management System Frontend</a> to use the actual application.*

## Requirements
* Docker Desktop
* WSL2 (Windows)
* PHP
* Composer

If using Windows, I recommend to clone it inside the actual WSL2 distro instead of Windows file explorer or `/mnt` folder for better API performance.

## Initial Setup
1. Open terminal and clone the repository

```
git clone https://github.com/giovictor/eastvantage-coding-assignment-laravel.git
```

2. Change directory to the repository
```
cd eastvantage-coding-assignment-laravel
```

3. In order to load environment variables, copy the `.env.example` file and rename it as `.env`

Then add or update the following values:
```
APP_NAME="User Management System"
APP_URL=http://localhost:8000
APP_PORT=8000
APP_SERVICE=laravel

LOG_CHANNEL=daily

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=eastvantage_coding_assignment_laravel
DB_USERNAME=sail
DB_PASSWORD=password
```

4. Install dependencies via composer
```
composer install
```

5. Assign  alias for `sail` command
```
alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'
```

6. Start the containers (Laravel and MySQL)
```
sail up -d
```

Check if both containers are running
```
sail ps
```

7. Generate application key for Laravel then clear cache
```
sail artisan key:generate

sail artisan optimize:clear
```

8. Run the database migrations to generate tables in the local database
```
sail artisan migrate
```

Check status of migration
```
sail artisan migrate:status
```

9. Run the database seeders to create test users and the initial roles data
```
sail artisan db:seed
```
## Usage
After the initial setup, starting the containers, and setting up the database you may:
1. Access API documentation via http://localhost:8000 or http://localhost:8000/docs to view all information about the API endpoints
2. Access or use the API endpoints via http://localhost:8000/api/v1/{endpoint}

Additional features aside from the assignment's requirements:
1. User update and delete
2. Roles CRUD
3. Integrated <a href="https://scribe.knuckles.wtf/laravel/" target="_blank">Scribe</a> for generating API documentation
