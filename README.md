## Setup
---
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

## Documentation
---
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

### Authenthication
---
Only admin can register new users. A registered user will receive an email with a generated password and an option to change it.
Logged in user will receive a token through which he will be authenthicated

#### Registration
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

#### Login

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

#### Send password reset link
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


#### Reset password

**URL**: `POST` localhost/api/reset_password

**Body parameters**:
type: form-data
| Parameter             | type(value) | Description                                                                                   | Required |
| --------------------- | ----------- | --------------------------------------------------------------------------------------------- | -------- |
| email                 | string      | -                                                                                             |  true        |
| password              | string      | -                                                                                             |     true     |
| password_confirmation | string      | -                                                                                             |        true  |
| token                 | string      | password reset token that is mailed to user in `Register` or `Send password reset link` route |        true  |

**Success response example:**
```json
{
    "message": "Password reset successfully"
}
```

### Roles
---
Role: `admin`

#### Index roles

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

#### Show role

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

#### Assign roles

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

#### Remove roles

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

### Permission
---
Role: `admin`

#### Index permissions

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

#### Show permission

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

#### Assign permissions

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

#### Remove permissions

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

### Users
Role: `admin`

#### Index users

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


#### Show user

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

### Candidates
---
Permission: `candidate`

#### Index candidates
Role: `read`

Retrieve a list of all candidates

**URL**: `GET` localhost/api/candidates

**Query parameters**:

| Parameter        | type(value)                 | Description                                                                                   | Required |
| ---------------- | --------------------------- | --------------------------------------------------------------------------------------------- | -------- |
| group_by_academy | int(0 or 1)                 | Specifies whether results should be grouped based on the academy                              | false    |
| search           | string                      | Search query whose terms try to match candidate `name`, `surnname`,`email`,`phone` properties | false    |
| date_from        | date                        | Only candidates whose application date is higher or equal will be returned                    | false    |
| date_to          | date                        | Only candidates whose application date is less than will be returned                          | false    |
| positions[]      | array\<int(position_id)>    | Only candidates who apply to the specified positions will be returned                         | false    |
| academy          | int(academy_id)             | Only candidates  who apply to the specified academy will be returned                          | false    |
| course           | string([course](#Courses)) | Only candidates who apply to the specified course will be returned                            | false    |


#### Show candidate
Role: `read`

Retrieves candidate

**URL**: `GET` localhost/api/candidates/{id}

**Query parameters**: None

#### Store candidate

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

#### Update candidate
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

#### Candidate import
Role: `post`

Imports candidate data from csv. Example file candidate_import_example.csv stored in project files.

**URL**: `POST` localhost/api/candidates/import

**Body parameters**:
type: form-data

| Parameter      | type(value) | Description                                                                                               | Required |
| -------------- | ----------- | --------------------------------------------------------------------------------------------------------- | -------- |
| candidate_data | file.csv    | Candidate properties must be defined according to the same rules as in [store candiate](#Store-candidate) | true     |
|                |             |                                                                                                           |          |
|                |             |                                                                                                           |          |

#### Export CV
Role: `read`

Retrieves  candidate CV file.pdf

**URL**: `GET` localhost/api/candidate/{id}/export_cv

**Query parameters**: None

#### Export candidates
Role: `read`

Retrieves  all candidates in file.xlsr

**URL**: `GET` localhost/api/candidates/export

**Query parameters**: None

### Education institution
---
Permission: `education_institution`
#### Index education institutions
Retrieves a list of all education institutions

**URL**: `GET` localhost/api/education_institutions

**Query parameters**: None

#### Show education institution
Retrieves education institutiton

**URL**: `GET` localhost/api/education_institutions/{id}

**Query parameters**: None

#### Store education institution

**URL**: `POST` localhost/api/education_institution

**Body parameters**:
type: form-data|x-www-form-urlencoded

| Parameter    | type(value) | Description | Required |
| ------------ | ----------- | ----------- | -------- |
| name         | string      | -           | true     |
| abbreviation | string      | -           | false    | 

#### Update education institution

**URL**: `PUT` localhost/api/education_institution/{id}

**Body parameters**:
type: x-www-form-urlencoded

| Parameter    | type(value) | Description | Required |
| ------------ | ----------- | ----------- | -------- |
| name         | string      | -           | false    |
| abbreviation | string      | -           | false    | 

### Academy
---
#### Index academies
Retrieve a list of all academies

**URL**: `GET` localhost/api/academies

**Query parameters**: None

#### Show academy
Retrieves academy

**URL**: `GET` localhost/api/academies/{id}

**Query parameters**: None

#### Show academy positions
Retrieves academy with positions

**URL**: `GET` localhost/api/academy/{id}/positions

**Query parameters**: None
#### Store academy

**URL**: `POST` localhost/api/academy

**Body parameters**:
type: form-data|x-www-form-urlencoded

| Parameter    | type(value) | Description | Required |
| ------------ | ----------- | ----------- | -------- |
| name         | string      | -           | true     |
| abbreviation | string      | -           | false    | 

#### Update academy

**URL**: `PUT` localhost/api/academy/{id}

**Body parameters**:
type: x-www-form-urlencoded

| Parameter    | type(value) | Description | Required |
| ------------ | ----------- | ----------- | -------- |
| name         | string      | -           | false    |
| abbreviation | string      | -           | false    |
|              |             |             |          |
### Positions
---
#### Index positions
Retrieve a list of all positions

**URL**: `GET` localhost/api/positions

**Query parameters**: None

#### Show position
Retrieves positions

**URL**: `GET` localhost/api/positions/{id}

**Query parameters**: None

#### Store position

**URL**: `POST` localhost/api/academy

**Body parameters**:
type: form-data|x-www-form-urlencoded

| Parameter    | type(value)            | Description | Required |
| ------------ | ---------------------- | ----------- | -------- |
| name         | string                 | -           | true     |
| abbreviation | string                 | -           | false    |
| academies[]  | array<int(academy_id)> | -           | true     | 

#### Update academy

**URL**: `PUT` localhost/api/academy/{id}

**Body parameters**:
type: x-www-form-urlencoded

| Parameter    | type(value)            | Description | Required |
| ------------ | ---------------------- | ----------- | -------- |
| name         | string                 | -           | false    |
| abbreviation | string                 | -           | false    |
| academies[]  | array<int(academy_id)> | -           | false    | 


### Academy statistic
---
Role: `Read`
Permission: `Statistic`

#### Index statistic by position

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


#### Show statistic by position

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


#### Index statistic by education institution

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

#### Show statistic by education institution

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

#### Index statistic by gender

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

#### Show statistic by gender

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

#### Index statistic by course

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

#### Show statistic by course

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

#### Index statistic by status

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

#### Show statistic by status

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

#### Index statistic by application date

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

#### Show statistic by application date

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

#### Index statistic by month

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

#### Show statistic by month

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

