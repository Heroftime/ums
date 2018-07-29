# User Management System

Simple user management system build using Symfony 4.1

## What's included?

- ADMIN CRUD for users / groups
- API CRUD for users / groups

## How to Install?

1) Clone this repository, then do the following:
2) `composer install`
3) `php bin/console doctrine:migrations:migrate`
4) `php bin/console doctrine:fixtures:load`

## How to Run?

### To access Admin:
1) After installation is done go to: http://localhost:8000/
2) Credentials:
	Username: admin
	Password: admin123
	
### To access API:
1) Navigate to (for example) http://localhost:8000/apis/groups/1
2) Credentials: It requires the token (**X-AUTH-TOKEN**) in headers. Token value will be the same as the admin username (default: admin)

## All routes can be found with this command:
`php bin/console debug:router`

## Database Model
[ERD](ums_erd.png)