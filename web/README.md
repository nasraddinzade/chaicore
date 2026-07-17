# ÇAYCORE — Next.js frontend

Клиент сайта ChaiCore на **Next.js 16 (App Router, TypeScript, Tailwind v4)**.
Контент берётся из headless-API PHP-CMS (`/api.php` на бэкенде Hostinger).

## Архитектура

```
Пользователь ─▶ Next.js (Vercel)  ──fetch(/api.php, ISR 60s)──▶  PHP+MySQL CMS (Hostinger)
                    │                                                 ├─ /api.php     — JSON весь контент
                    ├─ /              главная (SSR + ISR)             ├─ /send.php    — форма заявки
                    ├─ /api/contact   proxy → /send.php               ├─ /review.php  — приём отзыва
                    └─ /api/review    proxy → /review.php             └─ /admin       — админка (без изменений)
```

Формы отправляются server-side через route-handlers (`/api/contact`, `/api/review`),
поэтому CORS не требуется, а токены/почта не светятся клиенту.

## Локальный запуск

```bash
npm install
cp .env.example .env.local     # BACKEND_URL=http://127.0.0.1:8090 для локального PHP
npm run dev                    # http://localhost:3000
```

## Переменные окружения

| Переменная    | Значение (prod)         | Назначение                             |
|---------------|-------------------------|----------------------------------------|
| `BACKEND_URL` | `https://chaicore.az`   | Origin PHP-бэкенда (без слэша в конце) |

## Деплой на Vercel

1. **Import** репозитория `nasraddinzade/chaicore` в Vercel.
2. **Root Directory** = `web` (важно — Next.js лежит в подпапке).
3. Framework preset определится как **Next.js** автоматически.
4. **Environment Variables** → добавить `BACKEND_URL`.
   - Если бэкенд остаётся на `https://chaicore.az` → так и указать.
   - Если апекс `chaicore.az` уводится на Vercel → бэкенд перенести на сабдомен
     (напр. `https://api.chaicore.az`) и указать его.
5. Deploy.

PHP-бэкенд и админка остаются на Hostinger без изменений.
