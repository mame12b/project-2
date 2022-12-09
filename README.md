# Online Internship Applicant Tracking System

**Internship Applicant System** is a system that allow as to manage and track Internships and Internship Applicants.


# Feature overview

## Admin Page
- Create, Read, Update and Delete Schools
- Create, Read, Update and Delete Departments
- Create, Read, Update and Delete Staff Members
- Read and Delete Internships
- Read and Delete Applications
- Read and Delete Interns

## School Page
- Create, Read, Update and Delete Departments
- Read and Delete Internships
- Read and Delete Applications
- Read and Delete Interns

## Department Page
- Create, Read, Update and Delete Internships
- Create, Read, Update and Delete Applications
- Create, Read, Update and Delete Interns

## User Page
- Search for internships
- View and Apply to internships
- Manage profile
- Manage Applications 
# Getting Started
## Required 
- Composer 
- PHP >=8
- MYSQL >=5
- nodejs >=18

## Installation
### Step 1
Clone this repository
``` bash 
git clone https://github.com/hailu-che/jun_intern.git
```
### Step 2
Go to cloned directory
```bash 
cd jun_intern
```
and install required packeges
```bash
composer install 
```
>optional 
>```bash
>npm install
>```

###  Step 3
Create ``.env`` file and copy everything from ``.env.example`` to ``.env`` file 
for linux 
```bash
cat .env.example >> .env
```
and generate ``APP_KEY``
```bash
php artisan key:generate
```

### Step 4
Create database named ``jun_intern`` and migrate the database files
```bash 
php artisan migrate
```
### Step 5
Start the server and explore!
```bash
php artisan serve
```
## Note:

You can find dumped MySQL data in ``./database/jun_inter.sql``
>Make sure to change the `APP_URL` to correct website url in `.env` file 
### Account Detail:
| Account Type | Email | Password |
|--|--|--|
| Admin | admin@gmail.com | 12345678 |
| School | sh1@gmail.com | 12345678 |
| School | sh2@gmail.com | 12345678 |
| Department | dp1@gmail.com | 12345678 |
| Department | dp2@gmail.com | 12345678 |
| User | us1@gmail.com | 12345678 |
| User | us2@gmail.com | 12345678 |

## Configs:

You can customize message functionality to whether use web socket(node) or not.
> **Note:** using websocket functionality allows live chat and reduce server load.

> **To configure websocket**:
> Login as `Admin` and go to `/profile` page and select `Configs` tab, From that you can start the node server or stop the node server.

# Contributers
1. [Abel Malu](https://github.com/Abelmalu)
2. [Kidist](https://github.com/Mahitere)
3. [Mame](https://github.com/mame12b)
4. [Nafiyad Menberu](https://github.com/NafMKD)

