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

- `GET /tasks` - returns list of tasks for loged in user
- `POST /tasks` - creates new task, returns created task with id
- `PUT /tasks/{id}` - updates task with given id, returns updated task
- `DELETE /tasks/{id}` - deleted task with given id

## /users

- `GET /users` - returns list of users
- `POST /users` - creates new user, returns created user with id

## /login

- `POST /login` - ??? token/cookies

## TODO

- landing page
  - all
- ekran rejestracji
  - ~~zakładanie konta na bazie~~
  - wygląd strony
  - hashowanie haseł
- ekran logowania
  - zapamiętywanie emaila
  - wygląd strony
- ekran zadań
  - dodawanie zadań na bazie
  - usuwanie zadań na bazie
  - edycja opisu zadania
  - ~~wyswietlanie statusu zadania~~
  - ~~pobierania zadań z bazy~~
  - wygląd strony
