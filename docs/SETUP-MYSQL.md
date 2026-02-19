# MySQL Setup (Local Dev)

This guide helps you switch the app to MySQL with a dedicated database user. **Do not hardcode credentials in the repo.** Use `.env` for local values.

## 1) Create Database
```sql
CREATE DATABASE manake_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

## 2) Create Dedicated User (Recommended)
```sql
CREATE USER 'manake'@'localhost' IDENTIFIED BY 'strong_password_here';
GRANT ALL PRIVILEGES ON manake_db.* TO 'manake'@'localhost';
FLUSH PRIVILEGES;
```

## 3) Update `.env`
Example only (replace with your own values):
```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=manake_db
DB_USERNAME=manake
DB_PASSWORD=strong_password_here
```

## 4) Run Migrations
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan migrate
```

## 5) If You See 419 Page Expired
- Clear browser cookies for `127.0.0.1` and `localhost`.
- Run:
```bash
php artisan optimize:clear
```
- Ensure `APP_URL` matches your dev URL (e.g. `http://127.0.0.1:8000`).

## 6) Optional: Verify Connection
```bash
php artisan tinker
>>> DB::connection()->getPdo();
```
