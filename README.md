# Andino Products Management (PHP Version)

This is a PHP migration of the `andinoProducts` Spring Boot project. It uses the native PHP `mongodb` extension to connect to your MongoDB Atlas cluster.

## Features

- **MongoDB Atlas Integration**: Connects to the remote MongoDB cluster to fetch and save products.
- **Identical Layout & Styling**: Reuses the exact same HTML5 layout and CSS styling from the original Java Spring Boot app.
- **Client-Side HTML5 Form Validation**: Form elements validate inputs natively in the browser before submission, with custom error messages.
- **Server-Side Validation**: Basic PHP server-side validation is implemented to ensure data safety.
- **Post-Redirect-Get Pattern**: Prevents double submission of the form upon page refresh.

## Requirements

1. **PHP 8.2+** installed.
2. **MongoDB PHP Extension** (`mongodb`) enabled.

## Running the Application Locally

1. Open your terminal in this directory (`andinoProductsPhp`).
2. Run the PHP built-in web server:
   ```bash
   php -S localhost:8080
   ```
3. Open your browser and navigate to `http://localhost:8080`.

## Configuration

The connection string defaults to your MongoDB Atlas cluster:
`mongodb+srv://admin:admin@awd.ypmipjt.mongodb.net/Products?retryWrites=true&w=majority`

If you need to change this database URI, you can define the `MONGODB_URI` or `SPRING_DATA_MONGODB_URI` environment variable before starting the server. For example:

On Windows (PowerShell):
```powershell
$env:MONGODB_URI="mongodb+srv://user:pass@cluster.mongodb.net/dbname"
php -S localhost:8080
```
