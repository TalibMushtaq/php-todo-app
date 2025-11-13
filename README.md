# PHP Todo Application

A modern, feature-rich todo application built with PHP, MySQL, and vanilla JavaScript. This application provides a clean interface for managing your tasks with priority levels, filtering, and sorting capabilities.

## Features

- ✅ **Add Tasks** - Create new todos with priority levels (Low, Normal, High)
- ✅ **Mark Complete** - Toggle task completion status
- ✅ **Delete Tasks** - Remove tasks you no longer need
- ✅ **Filter Tasks** - View all, active, or completed tasks
- ✅ **Sort Tasks** - Sort by date added, priority, or name
- ✅ **Task Statistics** - View total and completed task counts
- ✅ **Responsive Design** - Works on desktop and mobile devices
- ✅ **Modern UI** - Beautiful gradient design with smooth animations
- ✅ **Toast Notifications** - User-friendly feedback for all actions
- ✅ **Keyboard Shortcuts** - Press `Ctrl/Cmd + K` to focus the input field

## Project Structure

```
php-todo-app/
├── api/                    # API endpoints
│   ├── add.php            # Add new todo
│   ├── get.php            # Get todos (with filtering & sorting)
│   ├── update.php         # Update todo (status, text, priority)
│   └── delete.php         # Delete todo
├── assets/                 # Static assets
│   ├── css/
│   │   └── style.css      # Application styles
│   └── js/
│       └── script.js       # Client-side JavaScript
├── includes/               # PHP includes
│   ├── config.php         # Configuration file
│   └── db.php             # Database connection & helpers
├── public/                 # Public-facing files
│   └── index.php          # Main application entry point
├── sql/                    # Database files
│   └── schema.sql         # Database schema
└── README.md              # This file
```

## Requirements

- **PHP** 7.4 or higher
- **MySQL** 5.7 or higher (or MariaDB 10.2+)
- **Web Server** (Apache, Nginx, or PHP built-in server)
- **MySQLi** extension enabled in PHP

## Installation & Setup

### Step 1: Clone or Download the Project

```bash
git clone <repository-url>
cd php-todo-app
```

### Step 2: Database Setup

1. **Create the database and tables:**

   ```bash
   mysql -u root -p < sql/schema.sql
   ```

   Or manually:
   ```sql
   mysql -u root -p
   ```
   Then run:
   ```sql
   source sql/schema.sql;
   ```

2. **Configure database credentials:**

   Edit `includes/config.php` and update the database credentials:

   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'your_username');
   define('DB_PASS', 'your_password');
   define('DB_NAME', 'todo_app');
   ```

### Step 3: Web Server Configuration

#### Option A: Using PHP Built-in Server (Development)

```bash
cd public
php -S localhost:8000
```

Then open your browser and navigate to: `http://localhost:8000`

#### Option B: Using Apache

1. **Configure Virtual Host** (recommended):

   Create a virtual host configuration file (e.g., `/etc/apache2/sites-available/todo-app.conf`):

   ```apache
   <VirtualHost *:80>
       ServerName todo-app.local
       DocumentRoot /path/to/php-todo-app/public
       
       <Directory /path/to/php-todo-app/public>
           Options Indexes FollowSymLinks
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

2. **Enable the site:**

   ```bash
   sudo a2ensite todo-app.conf
   sudo systemctl reload apache2
   ```

3. **Add to hosts file** (optional, for local development):

   ```bash
   echo "127.0.0.1 todo-app.local" | sudo tee -a /etc/hosts
   ```

#### Option C: Using Nginx

1. **Create Nginx configuration** (e.g., `/etc/nginx/sites-available/todo-app`):

   ```nginx
   server {
       listen 80;
       server_name todo-app.local;
       root /path/to/php-todo-app/public;
       index index.php;

       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }

       location ~ \.php$ {
           fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
           fastcgi_index index.php;
           include fastcgi_params;
           fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
       }
   }
   ```

2. **Enable the site:**

   ```bash
   sudo ln -s /etc/nginx/sites-available/todo-app /etc/nginx/sites-enabled/
   sudo nginx -t
   sudo systemctl reload nginx
   ```

### Step 4: Set Permissions (if needed)

```bash
chmod -R 755 /path/to/php-todo-app
```

### Step 5: Test the Application

1. Open your browser and navigate to your configured URL
2. Try adding a task
3. Test filtering and sorting features
4. Verify that tasks are being saved to the database

## Configuration

### Database Settings

Edit `includes/config.php` to configure:

- Database host, username, password, and database name
- Debug mode (enable/disable error reporting)

### Security Notes

- **Production**: Set `DEBUG_MODE` to `false` in `includes/config.php`
- **Database**: Use a dedicated database user with minimal privileges
- **Passwords**: Never commit database passwords to version control
- **HTTPS**: Use HTTPS in production environments

## API Endpoints

### GET `/api/get.php`
Retrieve todos with optional filtering and sorting.

**Query Parameters:**
- `filter` (optional): `all`, `active`, or `completed` (default: `all`)
- `sort` (optional): `created`, `priority`, or `task` (default: `created`)

**Response:**
```json
{
    "success": true,
    "todos": [...],
    "count": 5
}
```

### POST `/api/add.php`
Add a new todo.

**Parameters:**
- `task` (required): Task description (max 500 characters)
- `priority` (optional): `0` (Low), `1` (Normal), `2` (High) (default: `1`)

### POST `/api/update.php`
Update an existing todo.

**Parameters:**
- `id` (required): Todo ID
- `done` (optional): `0` or `1` to toggle completion
- `task` (optional): New task text
- `priority` (optional): New priority level

### POST `/api/delete.php`
Delete a todo.

**Parameters:**
- `id` (required): Todo ID to delete

## Troubleshooting

### Database Connection Error

- Verify MySQL/MariaDB is running: `sudo systemctl status mysql`
- Check database credentials in `includes/config.php`
- Ensure the database exists: `mysql -u root -p -e "SHOW DATABASES;"`
- Verify user permissions

### 404 Errors on API Calls

- Ensure your web server is configured to serve files from the `public` directory
- Check that `.htaccess` (Apache) or nginx configuration allows PHP execution
- Verify file paths in `public/index.php` and `assets/js/script.js`

### PHP Errors

- Check PHP error logs: `/var/log/php_errors.log` or check your web server logs
- Enable error reporting temporarily by setting `DEBUG_MODE` to `true` in `config.php`
- Verify PHP version: `php -v` (requires 7.4+)

### Styles/JavaScript Not Loading

- Check browser console for 404 errors
- Verify file paths in `public/index.php` are correct
- Ensure web server has read permissions on asset files

## Development

### Code Style

- PHP: Follows PSR-12 coding standards
- JavaScript: ES6+ with modern async/await
- CSS: Organized with comments and logical grouping

### Adding Features

1. **New API Endpoint**: Add to `api/` directory
2. **Frontend Changes**: Modify `assets/js/script.js` and `assets/css/style.css`
3. **Database Changes**: Update `sql/schema.sql` and run migrations

## License

This project is open source and available for educational purposes.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Support

For issues and questions, please open an issue on the repository.

---

**Happy Task Managing!** 🎉

