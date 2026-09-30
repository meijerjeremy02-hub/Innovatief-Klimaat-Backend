# Innovatief Klimaat API

## Run locally

Requirements: Podman Compose (or Docker Compose).

Create your ignored local environment file once:

```sh
cp .env.local.example .env.local
```

Replace `APP_SECRET`, `ADMIN_PASSWORD_HASH`, and the MySQL passwords with
private values before starting the stack. Generate a bcrypt hash with:

```sh
php -r 'echo password_hash("choose-a-private-password", PASSWORD_BCRYPT), PHP_EOL;'
```

```sh
podman compose --env-file .env.local up -d --build
podman compose exec app php bin/console doctrine:migrations:migrate --no-interaction
```

The API is at `http://localhost:8080/api`; API Platform docs are at
`http://localhost:8080/api/docs`. The Compose setup serves the API in production
mode, exposes MySQL only on `127.0.0.1:3306`, and provides local phpMyAdmin at
`http://localhost:8086`. Log into phpMyAdmin with the `MYSQL_USER` and
`MYSQL_PASSWORD` values, then select `MYSQL_DATABASE` (defaults: `app` and
`ChangeMe`). phpMyAdmin is bound to localhost and is not exposed by the
production Compose file.

Use `Accept: application/json` and `Content-Type: application/json` in frontend
requests. Local CORS permits `localhost` and `127.0.0.1` on any port.

## Survey flow

`GET /api/questions` returns dimensions in circle order. Each dimension contains
five questions, also ordered by question ID.

`POST /api/submit` saves one person's answers for one dimension. Send the team
code, dimension ID, and all five question IDs with integer scores from 1 to 5.
The response contains a `response_id` for that person's submission for that
dimension.

Individual answers are available at
`GET /api/questions/{questionId}/answers`. Result endpoints are:

- `GET /api/results/team/{teamCode}`
- `GET /api/results/person/{responseId}`
- `GET /api/results/category/{categoryId}`
- `GET /api/results/total`

Team, category, and total results group scores by dimension and question. Each
question includes its average score and response count; each dimension includes
the average across its answers and the number of participating submissions.
Category results combine answers from teams assigned to that category.

Creating and deleting categories, teams, dimensions, and questions requires
HTTP Basic authentication with username `admin`. Submissions and result reads
are public.

## Tests

Run the functional API tests with `php bin/phpunit`. They use an isolated
in-memory SQLite database and check that category, team, dimension, and
question creation/deletion works for an administrator and is rejected without
administrator credentials.

## Deployment

The `Dockerfile` builds the PHP 8.4 + Apache API image and listens on port 8080.
Deploy it to a Docker-compatible host alongside a MySQL 8.4 database. Configure
these environment variables on the host (never commit their values):

- `APP_ENV=prod`
- `APP_DEBUG=0`
- `APP_SECRET` — a long random secret
- `DATABASE_URL` — the host's MySQL connection URL with `charset=utf8mb4`
- `ADMIN_PASSWORD_HASH` — password hash for the `admin` Basic-auth user
- `CORS_ALLOW_ORIGIN` — regex matching the exact deployed frontend origin, e.g.
  `^https://my-frontend\.example$`
- `MYSQL_DATABASE`, `MYSQL_USER`, `MYSQL_PASSWORD`, `MYSQL_ROOT_PASSWORD` when
  using the bundled Compose database

Run `php bin/console doctrine:migrations:migrate --no-interaction` as a release
step after provisioning the database. The migrations create the schema and seed
the ten dimensions and their 50 questions in circle order.

For a Compose-based server, provide a server-only `.env.local` based on
`.env.local.example`, set the production frontend origin and strong credentials,
then run `podman compose up -d --build` and execute the migration command in the
app container. Pass that file to Compose for variable interpolation:
`podman compose --env-file .env.local up -d --build`.

Use HTTPS on the public host and replace all local development passwords. Do
not expose the database port publicly. Hosting request logs may record client
IP addresses; the application itself does not store respondent IPs or identity
fields.
