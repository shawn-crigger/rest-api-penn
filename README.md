# REST API TEST

This is a very simple REST API using only PDO as the database connection, with only the packages SLIMPHP 4 and PHPUNIT required installed.

## TODO

- Explain how to test
- Finish unit tests for multiple routes

## Install and Setup

  1. Git clone the repository and cd into the directory containing the repository.
  2. Since SLIMPHP 4 does not have migrations I have included a DB script `database.sql` that will create a database named `restapi` with a table `users` and some users to test against.
  3. Open `settings.ini` and set your database configuration. Normally I would store these in a `.env` file but this is the most secure method I could think of without using a extra package.
  4. Run `composer install` to install dependencies.
  5. Run `composer start` to start a local PHP server using port 8888 so all your REST API tests can be started.
  6. Run `composer test` to run the test cases.

## Manually testing using POSTMAN or Insomnia

You will receive a JSON response with the keys `success` and `message` for all requests whether the request was successful or failed. The status code will also be set to 400 for invalid parameters, 422 for various errors tht 422 seemed to be the best solution for and 500 for database errors.

`success` will always return true or false depending on whether the request was successful.
`message` will always return a description of the request.

## Route `GET` `/users` to fetch all users from the database

Create a new `GET` request to `http://localhost:8888/users` and you should get a JSON response of all your users.

## Route `POST` `/users` to create a new user in the database

Creaet a new POST request to `http://localhost:8888/users`
Setup a `multipart/form-data` body with the parameters

- name: User Name of the new user
- email: A valid email address of the new user

Setup your `Headers` with the following headers:

- `Content-Type`: `multipart/form-data`
- `Encoding-Type`: `raw`

## Route `POST` `/users/{id}/earn` to add points to a user

Creaet a new POST request to `http://localhost:8888/users/{id}/earn` replace the `id` with the ID of the user you want to change the points to.

Setup a `multipart/form-data` body with the parameters

- id: id of the user you want to change
- points: number of points you want to add to the current user's point balance.

Setup your `Headers` with the following headers:

- `Content-Type`: `multipart/form-data`
- `Encoding-Type`: `raw`

## Route `POST` `/users/{id}/redeem` to reduce points from a user

Creaet a new POST request to `http://localhost:8888/users/{id}/redeem` replace the `id` with the ID of the user you want to change the points to.

Setup a `multipart/form-data` body with the parameters

- id: id of the user you want to change
- points: number of points you want to subtract from the current user's point balance.

Setup your `Headers` with the following headers:

- `Content-Type`: `multipart/form-data`
- `Encoding-Type`: `raw`

## Route `DELETE` `/users/{id}` To delete a user from the database

Create a new `DELETE` request to `http://localhost:8888/users/{id}` the user will be deleted if it exists. You will receive a JSON response on success or failure. But you can verify using the `GET` `/users` request.
