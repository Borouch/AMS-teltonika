# Setup
Postman collection can also be located in ``example/AMS postman collection.json``

[![Run in Postman](https://run.pstmn.io/button.svg)](https://god.postman.co/run-collection/c5fb8a521b5d01181d7a?action=collection%2Fimport)
#### Download project
```bash
git clone https://github.com/Borouch/AMS-teltonika.git
```

Move into the project directory
```bash
cd project_path
```

#### Install required dependencies
```bash
composer update #installs all required dependencies
```
#### Configuration
Make a copy of `.env.example` and rename it to ``.env``

Inside .env file specify your local database configuration. This will allow laravel to access your local database.
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=
DB_PASSWORD= 
```

In order to sent emails specify these configurations:

```bash
MAIL_MAILER=smtp  
MAIL_HOST=smtp.gmail.com  
MAIL_PORT=587  
MAIL_USERNAME=*Email username*  
MAIL_PASSWORD=*Email generated app password*  
MAIL_ENCRYPTION=tls  
MAIL_FROM_ADDRESS=*Email address from which emails will be sent*  
MAIL_FROM_NAME="${APP_NAME}"
```

Configure admin credentials according to which admin user will be created during the seeding of database.
```bash
ADMIN_EMAIL=null  
ADMIN_PASSWORD=null
```

#### Initiate migrations and seed the databse
**Note**: database server must be started to initiate migrations!

```bash
php artisan migrate:fresh --seed
```
#### Generate secret key
According to which JWT authentication tokens will be generated
```bash
php artisan jwt:secret
```
#### Start project
```bash
php artisan serve
```

# Documentation

* [Authenthication](#authenthication)
    + [Registration](#registration)
    + [Login](#login)
    + [Send password reset link](#send-password-reset-link)
    + [Reset password](#reset-password)
* [Roles](#roles)
    + [Index roles](#index-roles)
    + [Show role](#show-role)
    + [Assign roles](#assign-roles)
    + [Remove roles](#remove-roles)
* [Permission](#permission)
    + [Index permissions](#index-permissions)
    + [Show permission](#show-permission)
    + [Assign permissions](#assign-permissions)
    + [Remove permissions](#remove-permissions)
* [Users](#users)
    + [Index users](#index-users)
    + [Show user](#show-user)
* [Candidates](#candidates)
    + [Predefined properties](#predefined-properties)
      + [Courses](#courses)
      + [Statuses](#statuses)
      + [Genders](#genders)
    + [Index candidates](#index-candidates)
    + [Show candidate](#show-candidate)
    + [Store candidate](#store-candidate)
    + [Update candidate](#update-candidate)
    + [Candidate import](#candidate-import)
    + [Export CV](#export-cv)
    + [Export candidates](#export-candidates)
* [Candidate comments](#candidate-comments)
    + [Index candidate comments](#index-candidate-comments)
    + [Post candidate comment](#post-candidate-comment)
    + [Update candidate comment](#update-candidate-comment)
* [Education institution](#education-institution)
    + [Index education institutions](#index-education-institutions)
    + [Show education institution](#show-education-institution)
    + [Store education institution](#store-education-institution)
    + [Update education institution](#update-education-institution)
* [Academy](#academy)
    + [Index academies](#index-academies)
    + [Show academy](#show-academy)
    + [Show academy positions](#show-academy-positions)
    + [Store academy](#store-academy)
    + [Update academy](#update-academy)
* [Positions](#positions)
    + [Index positions](#index-positions)
    + [Show position](#show-position)
    + [Store position](#store-position)
    + [Update academy](#update-academy-1)
* [Academy statistic](#academy-statistic)
    + [Index statistic by position](#index-statistic-by-position)
    + [Show statistic by position](#show-statistic-by-position)
    + [Index statistic by education institution](#index-statistic-by-education-institution)
    + [Show statistic by education institution](#show-statistic-by-education-institution)
    + [Index statistic by gender](#index-statistic-by-gender)
    + [Show statistic by gender](#show-statistic-by-gender)
    + [Index statistic by course](#index-statistic-by-course)
    + [Show statistic by course](#show-statistic-by-course)
    + [Index statistic by status](#index-statistic-by-status)
    + [Show statistic by status](#show-statistic-by-status)
    + [Index statistic by application date](#index-statistic-by-application-date)
    + [Show statistic by application date](#show-statistic-by-application-date)
    + [Index statistic by month](#index-statistic-by-month)
    + [Show statistic by month](#show-statistic-by-month)

## Authenthication
---
Only admin can register new users. A registered user will receive an email with a generated password and an option to change it.
Logged in user will receive a token through which he will be authenthicated

### Registration
Role: `Admin`

**URL**: `POST` localhost/api/register

**Body parameters**:
type: form-data
| Parameter | type(value) | Description | Required |
| --------- | ----------- | ----------- | -------- |
| email     | string      | -           | true     |


**Success response example:**
```json
{
    "message": "Registration successful, an email has been sent to user email address with its credentials"
}
```

[Back to top](#Documentation)
### Login
Token comes with a custom payload that contains user roles and permissions ids. Example:
```json
{
  "iss": "http://localhost:8000/api/login",
  "iat": 1640679986,
  "exp": 1640683586,
  "nbf": 1640679986,
  "jti": "wcryzExzRmTAHsvg",
  "sub": 1,
  "prv": "23bd5c8949f600adb39e701c400872db7a5976f7",
  "roles": [
    1
  ],
  "permissions": []
}
```

**URL**: `POST` localhost/api/Login

**Body parameters**:
type: form-data
| Parameter | type(value) | Description | Required |
| --------- | ----------- | ----------- | -------- |
| email     | string      | -           | true     |
| password  | string      | -           | true     |

**Success response example:**
```json
{
    "message": "Login was successful",
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTY0MDI2NzA1OCwiZXhwIjoxNjQwMjcwNjU4LCJuYmYiOjE2NDAyNjcwNTgsImp0aSI6ImM5ejVOelYzelJIbzdGSWkiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.RmTFcs9EFFojB1WzTttFoCVHhPTOZxcR0LrM-T9S3b8"
}
```

[Back to top](#Documentation)
### Send password reset link
Password reset link wil be sent to an email. Link will contain a token which will be used to reset password.


**URL**: `GET` localhost/api/reset_link

**Query parameters**:

| Parameter | type(value) | Description | Required |
| --------- | ----------- | ----------- | -------- |
| email     | string      | -           | true     | 


**Success response example:**
```json
{
    "message": "We have emailed your password reset link!"
}
```

**Password reset link example:**
```
http://localhost/reset_password?token=4af52d04c68fb3586a77da00366adfe36870dab10b7ebc4f3847c3e8ce6b9cba
```

[Back to top](#Documentation)
### Reset password

**URL**: `POST` localhost/api/reset_password

**Body parameters**:
type: form-data

| Parameter             | type(value) | Description                                                                                   | Required |
| --------------------- | ----------- | --------------------------------------------------------------------------------------------- | -------- |
| email                 | string      | -                                                                                             | true     |
| password              | string      | -                                                                                             | true     |
| password_confirmation | string      | -                                                                                             | true     |
| token                 | string      | password reset token that is mailed to user in `Register` or `Send password reset link` route | true     |
|                       |             |                                                                                               |          |

**Success response example:**
```json
{
    "message": "Password reset successfully"
}
```

[Back to top](#Documentation)
## Roles
---
Role: `admin`

### Index roles

**URL**: `GET` localhost/api/roles

**Success response example:**
```json
{
    "roles": [
        {
            "id": 1,
            "name": "admin",
            "guard_name": "web",
            "created_at": "2021-12-23 08:01:42",
            "updated_at": "2021-12-23 08:01:42"
        },
        {
            "id": 2,
            "name": "read",
            "guard_name": "web",
            "created_at": "2021-12-23 08:01:42",
            "updated_at": "2021-12-23 08:01:42"
        },
        {
            "id": 3,
            "name": "update",
            "guard_name": "web",
            "created_at": "2021-12-23 08:01:42",
            "updated_at": "2021-12-23 08:01:42"
        },
        {
            "id": 4,
            "name": "write",
            "guard_name": "web",
            "created_at": "2021-12-23 08:01:42",
            "updated_at": "2021-12-23 08:01:42"
        },
        {
            "id": 5,
            "name": "delete",
            "guard_name": "web",
            "created_at": "2021-12-23 08:01:42",
            "updated_at": "2021-12-23 08:01:42"
        }
    ]
}
```


[Back to top](#Documentation)
### Show role

**URL**: `GET` localhost/api/roles/``{id}``

**Success response example:**
```json
{
    "role": {
        "id": 1,
        "name": "admin",
        "guard_name": "web",
        "created_at": "2021-12-23 08:01:42",
        "updated_at": "2021-12-23 08:01:42"
    }
}
```


[Back to top](#Documentation)
### Assign roles

**URL**: `PUT` localhost/api/roles/assign/``{user_id}``

**Body parameters**:
type: x-www-form-urlencoded

| Parameter | type(value)          | Description | Required |
| --------- | -------------------- | ----------- | -------- |
| roles[]   | array\<int(role_id)> | -           | true     | 


**Success response example:**
```json
{
    "message": "Role(s) has been successfully assigned to user",
    "user": {
        "id": 2,
        "email": "demo@gmail.com",
        "created_at": "2021-12-23 11:01:55",
        "updated_at": "2021-12-23 11:03:42",
        "roles": [
            {
                "id": 2,
                "name": "read",
                "guard_name": "web",
                "created_at": "2021-12-23 08:01:42",
                "updated_at": "2021-12-23 08:01:42"
            }
        ]
    }
}
```


[Back to top](#Documentation)
### Remove roles

**URL**: `PUT` localhost/api/roles/remove/``{user_id}``

**Body parameters**:
type: x-www-form-urlencoded

| Parameter | type(value)          | Description | Required |
| --------- | -------------------- | ----------- | -------- |
| roles[]   | array\<int(role_id)> | -           | true     | 


**Success response example:**
```json
{
    "message": "Role(s) has been successfully removed from user",
    "user": {
        "id": 2,
        "email": "demo@gmail.com",
        "created_at": "2021-12-23 11:01:55",
        "updated_at": "2021-12-23 11:03:42",
        "roles":[]

    }
}
```


[Back to top](#Documentation)
## Permission
---
Role: `admin`

### Index permissions

**URL**: `GET` localhost/api/permissions

**Success response example:**
```json
{
    "Permissions": [
        {
            "id": 1,
            "name": "candidate",
            "guard_name": "web",
            "created_at": "2021-12-23 08:01:42",
            "updated_at": "2021-12-23 08:01:42"
        },
        {
            "id": 2,
            "name": "academy",
            "guard_name": "web",
            "created_at": "2021-12-23 08:01:42",
            "updated_at": "2021-12-23 08:01:42"
        },
        {
            "id": 3,
            "name": "education_institution",
            "guard_name": "web",
            "created_at": "2021-12-23 08:01:42",
            "updated_at": "2021-12-23 08:01:42"
        },
        {
            "id": 4,
            "name": "position",
            "guard_name": "web",
            "created_at": "2021-12-23 08:01:42",
            "updated_at": "2021-12-23 08:01:42"
        },
        {
            "id": 5,
            "name": "statistic",
            "guard_name": "web",
            "created_at": "2021-12-23 08:01:42",
            "updated_at": "2021-12-23 08:01:42"
        }
    ]
}
```


[Back to top](#Documentation)
### Show permission

**URL**: `GET` localhost/api/permissions/``{id}``

**Success response example:**
```json
{
    "Permissions": [
        {
            "id": 1,
            "name": "candidate",
            "guard_name": "web",
            "created_at": "2021-12-23 08:01:42",
            "updated_at": "2021-12-23 08:01:42"
        },
        {
            "id": 2,
            "name": "academy",
            "guard_name": "web",
            "created_at": "2021-12-23 08:01:42",
            "updated_at": "2021-12-23 08:01:42"
        },
        {
            "id": 3,
            "name": "education_institution",
            "guard_name": "web",
            "created_at": "2021-12-23 08:01:42",
            "updated_at": "2021-12-23 08:01:42"
        },
        {
            "id": 4,
            "name": "position",
            "guard_name": "web",
            "created_at": "2021-12-23 08:01:42",
            "updated_at": "2021-12-23 08:01:42"
        },
        {
            "id": 5,
            "name": "statistic",
            "guard_name": "web",
            "created_at": "2021-12-23 08:01:42",
            "updated_at": "2021-12-23 08:01:42"
        }
    ]
}
```


[Back to top](#Documentation)
### Assign permissions

**URL**: `PUT` localhost/api/permissions/assign/``{user_id}``

**Body parameters**:
type: x-www-form-urlencoded

| Parameter     | type(value)                | Description | Required |
| ------------- | -------------------------- | ----------- | -------- |
| permissions[] | array\<int(permission_id)> | -           | true     |


**Success response example:**
```json
{
    "message": "Permission(s) has been successfully assigned to user",
    "user": {
        "id": 2,
        "email": "demo@gmail.com",
        "created_at": "2021-12-23 11:01:55",
        "updated_at": "2021-12-23 11:03:42",
        "roles": [
        ],
        "permissions": [
            {
                "id": 2,
                "name": "academy",
                "guard_name": "web",
                "created_at": "2021-12-23 08:01:42",
                "updated_at": "2021-12-23 08:01:42"
            },
        ]
    }
}
```


[Back to top](#Documentation)
### Remove permissions

**URL**: `PUT` localhost/api/permissions/remove/``{user_id}``

**Body parameters**:
type: x-www-form-urlencoded

| Parameter | type(value)          | Description | Required |
| --------- | -------------------- | ----------- | -------- |
| permission[]   | array\<int(permission_id)> | -           | true     | 


**Success response example:**
```json
{
    "message": "Permission(s) has been successfully removed from user",
    "user": {
        "id": 2,
        "email": "demo@gmail.com",
        "created_at": "2021-12-23 11:01:55",
        "updated_at": "2021-12-23 11:03:42",
        "roles": [
        ],
        "permissions": [
        ]
    }
}
```


[Back to top](#Documentation)
## Users
Role: `admin`

### Index users

**URL**: `GET` localhost/api/users

**Success response example:**
```json
{
    "users": [
        {
            "id": 1,
            "email": "admin@teltonika.com",
            "created_at": "2021-12-23 08:01:42",
            "updated_at": "2021-12-23 08:01:42",
            "roles": [
                {
                    "id": 1,
                    "name": "admin",
                    "guard_name": "web",
                    "created_at": "2021-12-23 08:01:42",
                    "updated_at": "2021-12-23 08:01:42"
                }
            ],
            "permissions": []
        },
        {
            "id": 2,
            "email": "demo@gmail.com",
            "created_at": "2021-12-23 11:01:55",
            "updated_at": "2021-12-23 11:03:42",
            "roles": [
            ],
            "permissions": [
            ]
        }
    ]
}
```


[Back to top](#Documentation)
### Show user

**URL**: `GET` localhost/api/users/`{id}`

**Success response example:**
```json
{
    "User": {
        "id": 1,
        "email": "admin@teltonika.com",
        "created_at": "2021-12-23 08:01:42",
        "updated_at": "2021-12-23 08:01:42",
        "roles": [
            {
                "id": 1,
                "name": "admin",
                "guard_name": "web",
                "created_at": "2021-12-23 08:01:42",
                "updated_at": "2021-12-23 08:01:42"
            }
        ],
        "permissions": []
    }
}
```


[Back to top](#Documentation)
## Candidates
---
Permission: `candidate`

### Predefined properties
###### Courses
- first stage 1
- first stage 2
- first stage 3
- first stage 4
- second stage 1
- second stage 2
- graduated bachelor
- graduated masters
- not studying

###### Statuses
- candidate
- called for interview
- interviewed
- accepted for internship
- employed
- not accepted for internship
- not employed
- declined
- other

###### Genders
- male
- female


### Index candidates
Role: `read`

Retrieve a list of all candidates

**URL**: `GET` localhost/api/candidates

**Query parameters**:

| Parameter        | type(value)                | Description                                                                                   | Required |
| ---------------- | -------------------------- | --------------------------------------------------------------------------------------------- | -------- |
| group_by_academy | int(0 or 1)                | Specifies whether results should be grouped based on the academy                              | false    |
| search           | string                     | Search query whose terms try to match candidate `name`, `surnname`,`email`,`phone` properties | false    |
| date_from        | date                       | Only candidates whose application date is higher or equal will be returned                    | false    |
| date_to          | date                       | Only candidates whose application date is less than will be returned                          | false    |
| positions[]      | array\<int(position_id)>   | Only candidates who apply to the specified positions will be returned                         | false    |
| academy          | int(academy_id)            | Only candidates  who apply to the specified academy will be returned                          | false    |
| course           | string([course](#Courses)) | Only candidates who apply to the specified course will be returned                            | false    |

**Success response example:**
```json
{
    "candidates": [
        {
            "id": 1,
            "name": "Elton",
            "surnname": "Dibbert",
            "gender": "female",
            "phone": "864210012",
            "email": "tmills@gmail.com",
            "application_date": "2021-12-19",
            "city": "Robelshire",
            "status": "candidate",
            "course": "graduated masters",
            "can_manage_data": "1",
            "CV": null,
            "positions": [
                {
                    "id": 8,
                    "name": "web programming",
                    "abbreviation": null,
                    "created_at": "2021-12-23 08:01:42"
                }
            ],
            "comments": [],
            "education_institution": {
                "id": 2,
                "name": "Vilnius Gediminas technical university",
                "abbreviation": null,
                "created_at": "2021-12-23 08:01:42"
            },
            "academy": {
                "id": 2,
                "name": "Internet of things",
                "abbreviation": "IoT",
                "created_at": "2021-12-23 08:01:42"
            }
        },
		]
}
```


[Back to top](#Documentation)
### Show candidate
Role: `read`

**URL**: `GET` localhost/api/candidates/``{id}``

**Query parameters**: None

**Success response example:**
```json
{
    "candidate": {
        "id": 1,
        "name": "Elton",
        "surnname": "Dibbert",
        "gender": "female",
        "phone": "864210012",
        "email": "tmills@gmail.com",
        "application_date": "2021-12-19",
        "city": "Robelshire",
        "status": "candidate",
        "course": "graduated masters",
        "can_manage_data": "1",
        "CV": null,
        "positions": [
            {
                "id": 11,
                "name": "electronics engineering",
                "abbreviation": null,
                "created_at": "2021-12-23 08:01:42"
            }
        ],
        "comments": [],
        "education_institution": {
            "id": 2,
            "name": "Vilnius Gediminas technical university",
            "abbreviation": null,
            "created_at": "2021-12-23 08:01:42"
        },
        "academy": {
            "id": 2,
            "name": "Internet of things",
            "abbreviation": "IoT",
            "created_at": "2021-12-23 08:01:42"
        }
    }
}
```


[Back to top](#Documentation)
### Store candidate
Role: `create`
**URL**: `POST` localhost/api/candidate

**Body parameters**:
type: form-data

| Parameter                | type(value)                                       | Description                                         | Required |
| ------------------------ | ------------------------------------------------- | --------------------------------------------------- | -------- |
| name                     | string                                            | -                                                   | true     |
| surnname                 | string                                            | -                                                   | true     |
| gender                   | string([gender](#Genders))                        | -                                                   | true     |
| email                    | string                                            | -                                                   | true     |
| application_date         | date                                              | -                                                   | true     |
| city                     | string                                            | -                                                   | true     |
| course                   | string([course](#Courses))                        | -                                                   | true     |
| academy_id               | int                                               | -                                                   | true     |
| education_institution_id | int                                               | -                                                   | true     |
| positions[]              | array<int(position_id)>                           | -                                                   | true     |
| can_manage_data          | int(0 or 1)                                       | Will determine whether candidate data can be stored | true     |
| CV                       | file.pdf                                          | -                                                   | false    |
| status                   | string([status](#Statuses), default: 'candidate') | -                                                   | false    |
| comment                  | string                                            | -                                                   | false    |
| phone                    | string                                            | -                                                   | false    |
|                          |                                                   |                                                     |          |
**Success response example:**
```json
{
    "message": "Candidate saved successfully",
    "candidate": {
        "id": 11,
        "name": "Jonas",
        "surnname": "Jonaitis",
        "gender": "male",
        "phone": null,
        "email": "jonas@gmail.com",
        "application_date": "2021-10-07",
        "city": "Kaunas",
        "status": "called for interview",
        "course": "first stage 3",
        "can_manage_data": "1",
        "CV": null,
        "positions": [
            {
                "id": 1,
                "name": "Negotiation skills",
                "abbreviation": null,
                "created_at": "2021-12-23 08:01:42"
            }
        ],
        "comments": [],
        "education_institution": {
            "id": 2,
            "name": "Vilnius Gediminas technical university",
            "abbreviation": null,
            "created_at": "2021-12-23 08:01:42"
        },
        "academy": {
            "id": 1,
            "name": "Business to business",
            "abbreviation": "B2B",
            "created_at": "2021-12-23 08:01:42"
        }
    }
}
```


[Back to top](#Documentation)
### Update candidate
Role: `update`

**URL**: `POST` localhost/api/candidate/{id}

**Body parameters**:
type: form-data

| Parameter                | type(value)                                       | Description                                                                                                                                | Required |
| ------------------------ | ------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------ | -------- |
| name                     | string                                            | -                                                                                                                                          | false    |
| surnname                 | string                                            | -                                                                                                                                          | false    |
| gender                   | string([gender](#Genders))                        | -                                                                                                                                          | false    |
| email                    | string                                            | -                                                                                                                                          | false    |
| application_date         | date                                              | -                                                                                                                                          | false    |
| city                     | string                                            | -                                                                                                                                          | false    |
| course                   | string([course](#Courses))                        | -                                                                                                                                          | false    |
| academy_id               | int                                               | -                                                                                                                                          | false    |
| education_institution_id | int                                               | -                                                                                                                                          | false    |
| positions[]              | array<int(position_id)>                           | -                                                                                                                                          | false    |
| can_manage_data          | int(0 or 1)                                       | Will determine whether candidate data can be stored                                                                                        | false    |
| CV                       | file.pdf                                          | -                                                                                                                                          | false    |
| status                   | string([status](#Statuses), default: 'candidate') | -                                                                                                                                          | false    |
| comment                  | string                                            | -                                                                                                                                          | false    |
| phone                    | string                                            | -                                                                                                                                          | false    |
| method                   | string('PUT')                                     | Laravel doesn't accept form-data with `PUT` request, which is needed to work with files. Specifying this param as such act as a workaround | true     |

**Success response example:**
```json
{
    "message": "Candidate updated successfully",
    "candidate": {
        "id": 1,
        "name": "Elton",
        "surnname": "Dibbert",
        "gender": "female",
        "phone": "864210012",
        "email": "tmills@gmail.com",
        "application_date": "2021-12-19",
        "city": "Robelshire",
        "status": "candidate",
        "course": "graduated masters",
        "can_manage_data": "1",
        "CV": null,
        "positions": [
            {
                "id": 7,
                "name": "embedded programming",
                "abbreviation": null,
                "created_at": "2021-12-23 08:01:42"
            }
        ],
        "comments": [],
        "education_institution": {
            "id": 2,
            "name": "Vilnius Gediminas technical university",
            "abbreviation": null,
            "created_at": "2021-12-23 08:01:42"
        },
        "academy": {
            "id": 2,
            "name": "Internet of things",
            "abbreviation": "IoT",
            "created_at": "2021-12-23 08:01:42"
        }
    }
}
```


[Back to top](#Documentation)
### Candidate import
Role: `create`

Imports candidate data from csv. Example file candidate_import_example.csv stored in project files.

**URL**: `POST` localhost/api/candidates/import

**Body parameters**:
type: form-data

| Parameter      | type(value) | Description                                                                                               | Required |
| -------------- | ----------- | --------------------------------------------------------------------------------------------------------- | -------- |
| candidate_data | file.csv    | Candidate properties must be defined according to the same rules as in [store candiate](#Store-candidate) | true     |


**Success response example:**
```json
[
    {
        "message": "Candidate saved successfully",
        "candidate": {
            "id": 12,
            "name": "John Michael Stevenson",
            "surnname": "Turner",
            "gender": "male",
            "phone": "867765081",
            "email": "ihansen@yahoo.com",
            "application_date": "2021-11-01",
            "city": "Paoloberg",
            "status": "candidate",
            "course": "second stage 1",
            "can_manage_data": "1",
            "CV": "CVs/pvuTsSw9mV9uSqlFqNb5a8KFScUTd3QZPDkEqJVw.pdf",
            "positions": [
                {
                    "id": 2,
                    "name": "Sales techniques",
                    "abbreviation": null,
                    "created_at": "2021-12-23 08:01:42"
                },
            ],
            "comments": [
                {
                    "id": 1,
                    "content": "Moka SQL",
                    "candidate_id": 12,
                    "created_at": "2021-12-26 15:48:46",
                    "updated_at": "2021-12-26 15:48:46"
                }
            ],
            "education_institution": {
                "id": 1,
                "name": "Kaunas University of Technology",
                "abbreviation": null,
                "created_at": "2021-12-23 08:01:42"
            },
            "academy": {
                "id": 1,
                "name": "Business to business",
                "abbreviation": "B2B",
                "created_at": "2021-12-23 08:01:42"
            }
        }
    },
]
```


[Back to top](#Documentation)
### Export CV
Role: `read`

Retrieves  candidate CV in `file_name.pdf`

**URL**: `GET` localhost/api/candidate/``{id}``/export_cv

**Query parameters**: None


### Export candidates
Role: `read`

Retrieves  all candidates data in  `file_name.xlsr`

**URL**: `GET` localhost/api/candidates/export

## Candidate comments
Permission: `candidate`

###  Index candidate comments
Role: `read`
**URL**: `GET` localhost/api/candidates/``{id}``/comments

**Success response example:**
```json
{
    "comments": [
        {
            "id": 3,
            "content": "Has experience in Vuejs",
            "candidate_id": 1,
            "created_at": "2021-12-28 08:28:43",
            "updated_at": "2021-12-28 08:28:43"
        }
    ]
}
```

[Back to top](#Documentation)
### Post candidate comment
Role: `post`

**URL**: `POST`localhost/api/candidate/``{id}``/comment

**Body parameters**:

| Parameter | type(value) | Description | Required |
| --------- | ----------- | ----------- | -------- |
| content   | string      | -           | true     |

**Success response example:**
```json
{
    "message": "Comment saved successfully",
    "comment": {
        "content": "Has experience in Vuejs",
        "candidate_id": 1,
        "updated_at": "2021-12-28 08:28:43",
        "created_at": "2021-12-28 08:28:43",
        "id": 3
    }
}
```
[Back to top](#Documentation)
### Update candidate comment
Role: `update`

**URL**: `GET` localhost/api/candidate/comment/``{id}``

**Body parameters**:
type:xxx-www-form-urlencoded
| Parameter | type(value) | Description | Required |
| --------- | ----------- | ----------- | -------- |
| content   | string      | -           | true     |

**Success response example:**
```json
{
    "message": "Comment updated successfully",
    "comment": {
        "id": 1,
        "content": "Knows angular",
        "candidate_id": 12,
        "created_at": "2021-12-26 15:48:46",
        "updated_at": "2021-12-28 08:36:13"
    }
}
```
[Back to top](#Documentation)
## Education institution
---
Permission: `education_institution`
### Index education institutions
Role: `read`
Retrieves a list of all education institutions

**URL**: `GET` localhost/api/education_institutions

**Query parameters**: None
**Success response example:**
```json
{
    "education_institutions": [
        {
            "id": 1,
            "name": "Kaunas University of Technology",
            "abbreviation": null,
            "created_at": "2021-12-23 08:01:42"
        },
        {
            "id": 2,
            "name": "Vilnius Gediminas technical university",
            "abbreviation": null,
            "created_at": "2021-12-23 08:01:42"
        }
    ]
}
```

[Back to top](#Documentation)
### Show education institution
Retrieves education institutiton
Role: `read`

**URL**: `GET` localhost/api/education_institutions/``{id}``

**Query parameters**: None
**Success response example:**
```json
{
    "education_institution": {
        "id": 1,
        "name": "Kaunas University of Technology",
        "abbreviation": null,
        "created_at": "2021-12-23 08:01:42"
    }
}
```


[Back to top](#Documentation)
### Store education institution
Role: `create`
**URL**: `POST` localhost/api/education_institution

**Body parameters**:
type: form-data|x-www-form-urlencoded

| Parameter    | type(value) | Description | Required |
| ------------ | ----------- | ----------- | -------- |
| name         | string      | -           | true     |
| abbreviation | string      | -           | false    | 

**Success response example:**
```json
{
    "message": "Education institution saved successfully",
    "education_institution": {
        "name": "Vilniaus Kolegija",
        "abbreviation": "VK",
        "created_at": "2021-12-26 16:09:34",
        "id": 4
    }
}
```


[Back to top](#Documentation)
### Update education institution
Role: `update`
**URL**: `PUT` localhost/api/education_institution/{id}

**Body parameters**:
type: x-www-form-urlencoded

| Parameter    | type(value) | Description | Required |
| ------------ | ----------- | ----------- | -------- |
| name         | string      | -           | false    |
| abbreviation | string      | -           | false    | 

```json
{
    "message": "Education institution updated successfully",
    "education_institution": {
        "id": 1,
        "name": "Kaunas University of Technology",
        "abbreviation": "KTU",
        "created_at": "2021-12-23 08:01:42"
    }
}
```


[Back to top](#Documentation)
## Academy
---
Permission: `academy`

### Index academies
Role: `read`
Retrieve a list of all academies

**URL**: `GET` localhost/api/academies

**Query parameters**: None

```json
{
    "academies": [
        {
            "id": 1,
            "name": "Business to business",
            "abbreviation": "B2B",
            "created_at": "2021-12-23 08:01:42"
        },
        {
            "id": 2,
            "name": "Internet of things",
            "abbreviation": "IoT",
            "created_at": "2021-12-23 08:01:42"
        }
    ]
}
```



[Back to top](#Documentation)
### Show academy
Role: `read`
Retrieves academy

**URL**: `GET` localhost/api/academies/``{id}``
```json
{
    "academy": {
        "id": 1,
        "name": "Business to business",
        "abbreviation": "B2B",
        "created_at": "2021-12-23 08:01:42"
    }
}
```


[Back to top](#Documentation)
### Show academy positions
Role: `read`
Retrieves academy with positions

**URL**: `GET` localhost/api/academy/``{id}``/positions

```json
{
    "academy": {
        "id": 1,
        "name": "Business to business",
        "abbreviation": "B2B",
        "created_at": "2021-12-23 08:01:42",
        "positions": [
            {
                "id": 1,
                "name": "Negotiation skills",
                "abbreviation": null,
                "created_at": "2021-12-23 08:01:42"
            },
            {
                "id": 2,
                "name": "Sales techniques",
                "abbreviation": null,
                "created_at": "2021-12-23 08:01:42"
            },
            {
                "id": 3,
                "name": "objections overcoming skills",
                "abbreviation": null,
                "created_at": "2021-12-23 08:01:42"
            },
            {
                "id": 4,
                "name": "presentations: tool for selling ideas",
                "abbreviation": null,
                "created_at": "2021-12-23 08:01:42"
            },
            {
                "id": 5,
                "name": "business communication skills",
                "abbreviation": null,
                "created_at": "2021-12-23 08:01:42"
            }
        ]
    }
}
```


[Back to top](#Documentation)
### Store academy
Role: `create`

**URL**: `POST` localhost/api/academy

**Body parameters**:
type: form-data|x-www-form-urlencoded

| Parameter    | type(value) | Description | Required |
| ------------ | ----------- | ----------- | -------- |
| name         | string      | -           | true     |
| abbreviation | string      | -           | false    | 

```json
{
    "message": "Academy saved successfully",
    "academy": {
        "name": "Teltonika IT networks",
        "abbreviation": "TIN",
        "created_at": "2021-12-26 20:33:12",
        "id": 3
    }
}
```


[Back to top](#Documentation)
### Update academy
Role: `update`
**URL**: `PUT` localhost/api/academy/``{id}``

**Body parameters**:
type: x-www-form-urlencoded

| Parameter    | type(value) | Description | Required |
| ------------ | ----------- | ----------- | -------- |
| name         | string      | -           | false    |
| abbreviation | string      | -           | false    |

```json
{
    "message": "Academy updated successfully",
    "academy": {
        "id": 3,
        "name": "Academy Of Gentelments",
        "abbreviation": "TIN",
        "created_at": "2021-12-26 20:33:12"
    }
}
```


[Back to top](#Documentation)
## Positions
---
Permissions; `position`
### Index positions
Role: `read`
**URL**: `GET` localhost/api/positions

**Query parameters**: None

```json
{
    "positions": [
        {
            "id": 1,
            "name": "Negotiation skills",
            "abbreviation": null,
            "created_at": "2021-12-23 08:01:42",
            "academies": [
                {
                    "id": 1,
                    "name": "Business to business",
                    "abbreviation": "B2B",
                    "created_at": "2021-12-23 08:01:42"
                }
            ]
        },
        {
            "id": 2,
            "name": "Sales techniques",
            "abbreviation": null,
            "created_at": "2021-12-23 08:01:42",
            "academies": [
                {
                    "id": 1,
                    "name": "Business to business",
                    "abbreviation": "B2B",
                    "created_at": "2021-12-23 08:01:42"
                }
            ]
        }
    ]
}
```



[Back to top](#Documentation)
### Show position
Role: `read`
**URL**: `GET` localhost/api/positions/``{id}``

**Query parameters**: None

```json
{
    "position": {
        "id": 1,
        "name": "Negotiation skills",
        "abbreviation": null,
        "created_at": "2021-12-23 08:01:42"
    }
}
```


[Back to top](#Documentation)
### Store position
Role: `create`
**URL**: `POST` localhost/api/academy

**Body parameters**:
type: form-data|x-www-form-urlencoded

| Parameter    | type(value)            | Description | Required |
| ------------ | ---------------------- | ----------- | -------- |
| name         | string                 | -           | true     |
| abbreviation | string                 | -           | false    |
| academies[]  | array<int(academy_id)> | -           | true     | 

```json
{
    "message": "Position saved successfully",
    "position": {
        "id": 12,
        "name": "Artificial intelligence",
        "abbreviation": "AI",
        "created_at": "2021-12-26 20:42:42",
        "academies": [
            {
                "id": 2,
                "name": "Internet of things",
                "abbreviation": "IoT",
                "created_at": "2021-12-23 08:01:42"
            }
        ]
    }
}
```


[Back to top](#Documentation)
### Update academy
Role: `update`
**URL**: `PUT` localhost/api/academy/{id}

**Body parameters**:
type: x-www-form-urlencoded

| Parameter    | type(value)            | Description | Required |
| ------------ | ---------------------- | ----------- | -------- |
| name         | string                 | -           | false    |
| abbreviation | string                 | -           | false    |
| academies[]  | array<int(academy_id)> | -           | false    | 

```json
{
    "message": "Position updated successfully",
    "position": {
        "id": 12,
        "name": "Machine Learning",
        "abbreviation": "ML",
        "created_at": "2021-12-26 20:42:42",
        "academies": [
            {
                "id": 2,
                "name": "Internet of things",
                "abbreviation": "IoT",
                "created_at": "2021-12-23 08:01:42"
            }
        ]
    }
}
```


[Back to top](#Documentation)
## Academy statistic
---
Role: `read`
Permission: `statistic`

### Index statistic by position

**URL**: `GET` localhost/academies/statistic/position

**Success response example:**
```json
[
    {
        "academy": {
            "id": 1,
            "name": "Business to business",
            "abbreviation": "B2B",
            "created_at": "2021-12-23 08:01:42"
        },
        "statistic": [
            {
                "position": "Negotiation skills",
                "count": 3
            },
            {
                "position": "Sales techniques",
                "count": 2
            },
            {
                "position": "objections overcoming skills",
                "count": 1
            },
            {
                "position": "presentations: tool for selling ideas",
                "count": 2
            },
            {
                "position": "business communication skills",
                "count": 4
            }
        ]
    },
    {
        "academy": {
            "id": 2,
            "name": "Internet of things",
            "abbreviation": "IoT",
            "created_at": "2021-12-23 08:01:42"
        },
        "statistic": [
            {
                "position": "IoT devices testing",
                "count": 0
            },
            {
                "position": "embedded programming",
                "count": 2
            },
            {
                "position": "web programming",
                "count": 3
            },
            {
                "position": "technical support",
                "count": 1
            },
            {
                "position": "cad design engineering",
                "count": 1
            },
            {
                "position": "electronics engineering",
                "count": 1
            }
        ]
    }
]
```


[Back to top](#Documentation)
### Show statistic by position

**URL**: `GET` localhost/academies/``{id}``/statistic/position


**Success response example:**
```json
[
    {
        "academy": {
            "id": 1,
            "name": "Business to business",
            "abbreviation": "B2B",
            "created_at": "2021-12-23 08:01:42"
        },
        "statistic": [
            {
                "position": "Negotiation skills",
                "count": 3
            },
            {
                "position": "Sales techniques",
                "count": 2
            },
            {
                "position": "objections overcoming skills",
                "count": 1
            },
            {
                "position": "presentations: tool for selling ideas",
                "count": 2
            },
            {
                "position": "business communication skills",
                "count": 4
            }
        ]
    }
]
```


[Back to top](#Documentation)
### Index statistic by education institution

**URL**: `GET` localhost/api/academies/statistic/education_institution


**Success response example:**
```json
[
    {
        "academy": {
            "id": 1,
            "name": "Business to business",
            "abbreviation": "B2B",
            "created_at": "2021-12-23 08:01:42"
        },
        "statistic": [
            {
                "education_institution": "Kaunas University of Technology",
                "count": 4
            },
            {
                "education_institution": "Vilnius Gediminas technical university",
                "count": 2
            }
        ]
    },
    {
        "academy": {
            "id": 2,
            "name": "Internet of things",
            "abbreviation": "IoT",
            "created_at": "2021-12-23 08:01:42"
        },
        "statistic": [
            {
                "education_institution": "Kaunas University of Technology",
                "count": 1
            },
            {
                "education_institution": "Vilnius Gediminas technical university",
                "count": 3
            }
        ]
    }
]
```



[Back to top](#Documentation)
### Show statistic by education institution

**URL**: `GET` localhost/api/academies/``{id}``/statistic/education_institution


**Success response example:**
```json
[
    {
        "academy": {
            "id": 1,
            "name": "Business to business",
            "abbreviation": "B2B",
            "created_at": "2021-12-23 08:01:42"
        },
        "statistic": [
            {
                "education_institution": "Kaunas University of Technology",
                "count": 4
            },
            {
                "education_institution": "Vilnius Gediminas technical university",
                "count": 2
            }
        ]
    }
]
```


[Back to top](#Documentation)
### Index statistic by gender

**URL**: `GET` localhost/api/academies/statistic/gender


**Success response example:**
```json
[
    {
        "academy": {
            "id": 1,
            "name": "Business to business",
            "abbreviation": "B2B",
            "created_at": "2021-12-23 08:01:42"
        },
        "statistic": [
            {
                "gender": "male",
                "count": 2
            },
            {
                "gender": "female",
                "count": 4
            }
        ]
    },
    {
        "academy": {
            "id": 2,
            "name": "Internet of things",
            "abbreviation": "IoT",
            "created_at": "2021-12-23 08:01:42"
        },
        "statistic": [
            {
                "gender": "male",
                "count": 2
            },
            {
                "gender": "female",
                "count": 2
            }
        ]
    }
]
```


[Back to top](#Documentation)
### Show statistic by gender

**URL**: `GET` localhost/api/academies/``{id}``/statistic/gender


**Success response example:**
```json
[
    {
        "academy": {
            "id": 1,
            "name": "Business to business",
            "abbreviation": "B2B",
            "created_at": "2021-12-23 08:01:42"
        },
        "statistic": [
            {
                "gender": "male",
                "count": 2
            },
            {
                "gender": "female",
                "count": 4
            }
        ]
    }
]
```


[Back to top](#Documentation)
### Index statistic by course

**URL**: `GET` localhost/api/academies/statistic/course


**Success response example:**
```json
[
    {
        "academy": {
            "id": 1,
            "name": "Business to business",
            "abbreviation": "B2B",
            "created_at": "2021-12-23 08:01:42"
        },
        "statistic": [
            {
                "course": "first stage 1",
                "count": 1
            },
            {
                "course": "first stage 2",
                "count": 1
            },
            {
                "course": "first stage 3",
                "count": 1
            },
            {
                "course": "first stage 4",
                "count": 0
            },
            {
                "course": "second stage 1",
                "count": 1
            },
            {
                "course": "second stage 2",
                "count": 0
            },
            {
                "course": "graduated bachelor",
                "count": 1
            },
            {
                "course": "graduated masters",
                "count": 1
            },
            {
                "course": "not studying",
                "count": 0
            }
        ]
    },
    {
        "academy": {
            "id": 2,
            "name": "Internet of things",
            "abbreviation": "IoT",
            "created_at": "2021-12-23 08:01:42"
        },
        "statistic": [
            {
                "course": "first stage 1",
                "count": 0
            },
            {
                "course": "first stage 2",
                "count": 0
            },
            {
                "course": "first stage 3",
                "count": 0
            },
            {
                "course": "first stage 4",
                "count": 1
            },
            {
                "course": "second stage 1",
                "count": 1
            },
            {
                "course": "second stage 2",
                "count": 0
            },
            {
                "course": "graduated bachelor",
                "count": 0
            },
            {
                "course": "graduated masters",
                "count": 1
            },
            {
                "course": "not studying",
                "count": 1
            }
        ]
    }
]
```


[Back to top](#Documentation)
### Show statistic by course

**URL**: `GET` localhost/api/academies/``{id}``/statistic/course


**Success response example:**
```json
[
    {
        "academy": {
            "id": 1,
            "name": "Business to business",
            "abbreviation": "B2B",
            "created_at": "2021-12-23 08:01:42"
        },
        "statistic": [
            {
                "course": "first stage 1",
                "count": 1
            },
            {
                "course": "first stage 2",
                "count": 1
            },
            {
                "course": "first stage 3",
                "count": 1
            },
            {
                "course": "first stage 4",
                "count": 0
            },
            {
                "course": "second stage 1",
                "count": 1
            },
            {
                "course": "second stage 2",
                "count": 0
            },
            {
                "course": "graduated bachelor",
                "count": 1
            },
            {
                "course": "graduated masters",
                "count": 1
            },
            {
                "course": "not studying",
                "count": 0
            }
        ]
    }
]
```


[Back to top](#Documentation)
### Index statistic by status

**URL**: `GET` localhost/api/academies/statistic/status


**Success response example:**
```json
[
    {
        "academy": {
            "id": 1,
            "name": "Business to business",
            "abbreviation": "B2B",
            "created_at": "2021-12-23 08:01:42"
        },
        "statistic": [
            {
                "status": "candidate",
                "count": 6
            },
            {
                "status": "called for interview",
                "count": 0
            },
            {
                "status": "interviewed",
                "count": 0
            },
            {
                "status": "accepted for internship",
                "count": 0
            },
            {
                "status": "employed",
                "count": 0
            },
            {
                "status": "not accepted for internship",
                "count": 0
            },
            {
                "status": "not employed",
                "count": 0
            },
            {
                "status": "declined",
                "count": 0
            },
            {
                "status": "other",
                "count": 0
            }
        ]
    },
    {
        "academy": {
            "id": 2,
            "name": "Internet of things",
            "abbreviation": "IoT",
            "created_at": "2021-12-23 08:01:42"
        },
        "statistic": [
            {
                "status": "candidate",
                "count": 4
            },
            {
                "status": "called for interview",
                "count": 0
            },
            {
                "status": "interviewed",
                "count": 0
            },
            {
                "status": "accepted for internship",
                "count": 0
            },
            {
                "status": "employed",
                "count": 0
            },
            {
                "status": "not accepted for internship",
                "count": 0
            },
            {
                "status": "not employed",
                "count": 0
            },
            {
                "status": "declined",
                "count": 0
            },
            {
                "status": "other",
                "count": 0
            }
        ]
    }
]
```


[Back to top](#Documentation)
### Show statistic by status

**URL**: `GET` localhost/api/academies/``{id}``/statistic/status


**Success response example:**
```json
[
    {
        "academy": {
            "id": 1,
            "name": "Business to business",
            "abbreviation": "B2B",
            "created_at": "2021-12-23 08:01:42"
        },
        "statistic": [
            {
                "course": "first stage 1",
                "count": 1
            },
            {
                "course": "first stage 2",
                "count": 1
            },
            {
                "course": "first stage 3",
                "count": 1
            },
            {
                "course": "first stage 4",
                "count": 0
            },
            {
                "course": "second stage 1",
                "count": 1
            },
            {
                "course": "second stage 2",
                "count": 0
            },
            {
                "course": "graduated bachelor",
                "count": 1
            },
            {
                "course": "graduated masters",
                "count": 1
            },
            {
                "course": "not studying",
                "count": 0
            }
        ]
    }
]
```


[Back to top](#Documentation)
### Index statistic by application date

**URL**: `GET` localhost/api/academies/statistic/application_date


**Success response example:**
```json
[
    {
        "academy": {
            "id": 1,
            "name": "Business to business",
            "abbreviation": "B2B",
            "created_at": "2021-12-23 08:01:42"
        },
        "statistic": [
            {
                "application_date": "2021-12-13",
                "count": 1
            },
            {
                "application_date": "2021-12-12",
                "count": 1
            },
            {
                "application_date": "2021-11-25",
                "count": 1
            },
            {
                "application_date": "2021-12-19",
                "count": 1
            },
            {
                "application_date": "2021-12-18",
                "count": 1
            },
            {
                "application_date": "2021-12-23",
                "count": 1
            }
        ]
    },
    {
        "academy": {
            "id": 2,
            "name": "Internet of things",
            "abbreviation": "IoT",
            "created_at": "2021-12-23 08:01:42"
        },
        "statistic": [
            {
                "application_date": "2021-12-04",
                "count": 2
            },
            {
                "application_date": "2021-12-19",
                "count": 1
            },
            {
                "application_date": "2021-12-02",
                "count": 1
            }

        ]
    }
]
```


[Back to top](#Documentation)
### Show statistic by application date

**URL**: `GET` localhost/api/academies/``{id}``/statistic/application_date


**Success response example:**
```json
[
    {
        "academy": {
            "id": 1,
            "name": "Business to business",
            "abbreviation": "B2B",
            "created_at": "2021-12-23 08:01:42"
        },
        "statistic": [
            {
                "application_date": "2021-12-13",
                "count": 2
            },
            {
                "application_date": "2021-12-12",
                "count": 1
            },
            {
                "application_date": "2021-11-25",
                "count": 1
            },
            {
                "application_date": "2021-12-19",
                "count": 1
            },
            {
                "application_date": "2021-12-18",
                "count": 1
            },
            {
                "application_date": "2021-12-23",
                "count": 1
            }
        ]
    }
]
```


[Back to top](#Documentation)
### Index statistic by month

**URL**: `GET` localhost/api/academies/statistic/month/`{month_number}`


**Success response example:**
```json
[
    {
        "academy": {
            "id": 1,
            "name": "Business to business",
            "abbreviation": "B2B",
            "created_at": "2021-12-23 08:01:42"
        },
        "statistic": [
            {
                "application_date": "2021-12-13",
                "count": 1
            },
            {
                "application_date": "2021-12-12",
                "count": 1
            },
            {
                "application_date": "2021-11-25",
                "count": 1
            },
            {
                "application_date": "2021-12-19",
                "count": 1
            },
            {
                "application_date": "2021-12-18",
                "count": 1
            },
            {
                "application_date": "2021-12-23",
                "count": 1
            }
        ]
    },
    {
        "academy": {
            "id": 2,
            "name": "Internet of things",
            "abbreviation": "IoT",
            "created_at": "2021-12-23 08:01:42"
        },
        "statistic": [
            {
                "application_date": "2021-12-04",
                "count": 2
            },
            {
                "application_date": "2021-12-19",
                "count": 1
            },
            {
                "application_date": "2021-12-02",
                "count": 1
            }

        ]
    }
]
```


[Back to top](#Documentation)
### Show statistic by month

**URL**: `GET` localhost/api/academies/``{id}``/statistic/month/`{month_number}`



**Success response example:**
```json
[
    {
        "academy": {
            "id": 1,
            "name": "Business to business",
            "abbreviation": "B2B",
            "created_at": "2021-12-23 08:01:42"
        },
        "count_statistic": {
            "month": 11,
            "candidates_count": 1
        },
        "course_statistic": [
            {
                "course": "first stage 1",
                "count": 0
            },
            {
                "course": "first stage 2",
                "count": 1
            },
            {
                "course": "first stage 3",
                "count": 0
            },
            {
                "course": "first stage 4",
                "count": 0
            },
            {
                "course": "second stage 1",
                "count": 0
            },
            {
                "course": "second stage 2",
                "count": 0
            },
            {
                "course": "graduated bachelor",
                "count": 0
            },
            {
                "course": "graduated masters",
                "count": 0
            },
            {
                "course": "not studying",
                "count": 0
            }
        ],
        "education_institution_statistic": [
            {
                "education_institution": "Kaunas University of Technology",
                "count": 0
            },
            {
                "education_institution": "Vilnius Gediminas technical university",
                "count": 1
            }
        ],
        "position_statistic": [
            {
                "position": "Negotiation skills",
                "count": 1
            },
            {
                "position": "Sales techniques",
                "count": 0
            },
            {
                "position": "objections overcoming skills",
                "count": 0
            },
            {
                "position": "presentations: tool for selling ideas",
                "count": 0
            },
            {
                "position": "business communication skills",
                "count": 1
            }
        ],
        "status_statistic": [
            {
                "status": "candidate",
                "count": 1
            },
            {
                "status": "called for interview",
                "count": 0
            },
            {
                "status": "interviewed",
                "count": 0
            },
            {
                "status": "accepted for internship",
                "count": 0
            },
            {
                "status": "employed",
                "count": 0
            },
            {
                "status": "not accepted for internship",
                "count": 0
            },
            {
                "status": "not employed",
                "count": 0
            },
            {
                "status": "declined",
                "count": 0
            },
            {
                "status": "other",
                "count": 0
            }
        ],
        "gender_statistic": [
            {
                "gender": "male",
                "count": 0
            },
            {
                "gender": "female",
                "count": 1
            }
        ]
    }
]
```


[Back to top](#Documentation)
