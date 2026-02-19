# Dev Notes (Local)

## 1) APP_URL Consistency (Fix 419)
Use the same host everywhere to avoid session/CSRF mismatches.
- Recommended: `http://127.0.0.1:8000`
- Avoid mixing `localhost` and `127.0.0.1`

If you still see `419 Page Expired`:
1. Clear browser cookies for both `localhost` and `127.0.0.1`.
2. Run:
```bash
php artisan optimize:clear
```

## 2) CSRF for AJAX
All forms must include `@csrf`.
For AJAX, ensure `X-CSRF-TOKEN` is set. Layouts already expose a global helper:
- `window.csrfToken`
- `window.fetchWithCsrf(url, options)`

## 3) Sessions
If `SESSION_DRIVER=database`, ensure the sessions table exists and migrations are run:
```bash
php artisan migrate
```
