# User Management System

Simple user management system build using Symfony 4.1

## What's included?

- ADMIN CRUD for users / groups
- API CRUD for users / groups

## How to Install?

1) Clone this repository, then do the following:
2) `composer install`
3)  Configure the **DATABASE_URL** in the **.env** file
4) `php bin/console doctrine:database:create`
5) `php bin/console doctrine:migrations:migrate`
6) `php bin/console doctrine:fixtures:load`

## How to Run?

### Run Local Webserver
1) `php bin/console server:run`

### To access Admin:
1) After installation, start the server and then navigate to: http://localhost:8001/
2) Credentials:
	Username: admin
	Password: admin
	
### To access API:
1) Navigate to (for example) http://localhost:8001/api/groups/1
2) Credentials: It requires the token (**X-AUTH-TOKEN**) in headers. Token value will be the same as the admin username (default: **admin**)

###### All routes can be found with this command:
`php bin/console debug:router`

###### Database Model
[ERD](ums_erd.png)