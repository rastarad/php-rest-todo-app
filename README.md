# Data structure

## Task

- id
- name
- status
- user_id
- due_date

## User

- id
- email
- password_hash
- is_admin

# API

## /tasks

- `GET /tasks` - returns list of tasks for loged in user (if task id is provided returns single task)
- `POST /tasks` - creates new task, returns created task with id
- `PUT /tasks` - updates task with given id, returns updated task (requires task id)
- `DELETE /tasks` - deleted task with given id (requires task id)

## TODO

- landing page
  - ~~all~~
- ekran rejestracji
  - ~~zakładanie konta na bazie~~
  - ~~wygląd strony~~
  - ~~informacja po próbie stworzenia takiego samego mejla~~
  - ~~hashowanie haseł Argon2id~~
  - ~~przeniesienie do logowania~~
- ekran logowania
  - ~~zapamiętywanie emaila~~
  - ~~wygląd strony~~
  - ~~przeniesienie do rejestracji~~
  - ~~hashowanie haseł Argon2id~~
- ekran zadań
  - ~~dodawanie zadań na bazie~~
  - ~~usuwanie zadań na bazie~~
  - ~~edycja opisu zadania~~
  - ~~wyswietlanie statusu zadania~~
  - ~~pobierania zadań z bazy~~
  - ~~wygląd strony~~
  - ~~przycisk wylogowania~~
  - ~~podpięcie do api~~
  - ~~wywołanie funkcji~~
