# Simple MVC PHP App

This repository contains a minimal full-stack website built using PHP with an MVC structure and OOP principles. The frontend uses Bootstrap for styling.

## Features
- Registration (with server-side validation)
- Login/Logout (requires registered account)
- Dashboard (authenticated users)
- User list (admin view)

## Project Structure
```
/public             # entry point and assets
/app
  /controllers      # controller classes
  /models           # model classes
  /views            # view templates
    /layouts
    /auth
    /home
/config             # configuration and database helper
/db.sql             # SQL script to create database/table
```

## Installation
1. Clone the repo:
   ```sh
   git clone https://github.com/<your-user>/mvc-php-app.git
   cd mvc-php-app
   ```
2. Create the database and table using the provided SQL.
   *If you have a local MySQL/MariaDB server running (XAMPP on Arch, for example), run:*
   ```sh
   /opt/lampp/bin/mysql -u root < db.sql
   ```
   *or* start your system server and then:
   ```sh
   mysql -u root -p < db.sql
   ```
3. Configure database credentials in `config/config.php`.
   - Use `127.0.0.1` instead of `localhost` to force a TCP connection, which avoids socket issues on Arch.
   - If you’re using a custom port, set `db_port` accordingly.

4. Serve the `public` directory with your Apache/Nginx server, or use PHP's built-in server for testing:
   ```sh
   php -S localhost:8000 -t public
   ```

5. Visit `http://localhost:8000` in your browser. You will be redirected to the login page; if you don't yet have an account you must register first using the **Register** link. The forms enforce simple validation (username length, valid email, password minimum length).

## Web (HTML)
The site provides traditional web pages rendered by PHP in the `app/views` folder.  Users register, log in and view a dashboard or user list via browser forms:

- `GET /` → redirects to `/login` or `/dashboard` if authenticated
- `GET /register` → registration form
- `POST /register` → handles form submission
- `GET /login` → login form
- `POST /login` → auth handler
- `GET /dashboard`, `/users` → protected pages

### REST API
A JSON REST API is available under `/api`.

- `POST /api/auth/register` — register a new user
  ```sh
  curl -X POST http://localhost:8000/api/auth/register \
       -H "Content-Type: application/json" \
       -d '{"username":"bob","email":"bob@example.com","password":"secret"}'
  ```

- `POST /api/auth/login` — authenticate a user
  ```sh
  curl -X POST http://localhost:8000/api/auth/login \
       -H "Content-Type: application/json" \
       -d '{"email":"bob@example.com","password":"secret"}'
  ```

- `GET /api/home/dashboard?user_id=1` — fetch dashboard data for a user (use the id returned above)
- `GET /api/home/users` — list all users

These endpoints are stateless; in a real application you would add token-based authentication.

### SOAP Service
A basic SOAP server lives at `/soap.php`.  It exposes two methods:

- `hello(string $name): string`
- `getUser(int $id): object` (returns user information or SOAP fault)

Example client call using PHP:
```php
$client = new SoapClient(null, ['location' => 'http://localhost:8000/soap.php', 'uri' => 'http://localhost/']);
echo $client->hello('Alice');
try {
    $user = $client->getUser(1);
    var_dump($user);
} catch (SoapFault $f) {
    echo "SOAP fault: {$f->getMessage()}";
}
```

The SOAP endpoint simply wraps the existing `User` model and can be extended with authentication/tokens.

## GitHub
This project can be pushed to a GitHub repository. Ensure sensitive info is omitted.

Example link:
`https://github.com/<your-user>/mvc-php-app`

## License
MIT
