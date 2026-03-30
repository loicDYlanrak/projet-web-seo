# projet-web-seo

## Local stack (Docker)

This project runs with `php:8.2-apache` and `mysql:8.0`.

Before startup, verify local images:

```bash
docker images --format "{{.Repository}}:{{.Tag}}"
```

Build and start without pulling images:

```bash
docker compose build --pull=false
docker compose up -d --pull never
```

Stop stack:

```bash
docker compose down
```

## URLs

- App: `http://localhost:8080`
- DB test page: `http://localhost:8080/pages/db-test.php`

Rewrite quick checks:

- `http://localhost:8080/essai.html`
- `http://localhost:8080/pages/article-1.html`
- `http://localhost:8080/news-1-tech.html`

## Live edits without restart

The compose config mounts the project folder directly in the web container:

`./:/var/www/html`

Any change in PHP/HTML/CSS/JS is visible immediately without restarting Docker or Apache.

## Documentation reprise

- Reprise technique et architecture: docs/reprise-code.md
- Fonctions mutualisees: includes/function.php

