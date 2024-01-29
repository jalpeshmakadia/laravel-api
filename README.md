## Introduction
Build a simple laravel api development environment with docker-compose and redis.


## Usage

### Create an initial Laravel project

1. Git clone & change directory
2. Goto project dire and execute the following command
3. Copy .env.example to .env file
```bash
$ composer install

$ docker-compose up -d
```
3. Go to http://localhost:8000

### Api URLs

#### Create Job 

POST: http://localhost:8000/api/jobs

Sample Data:

JSON request body
```json
{"urls": ["https://reiztech.recruitee.com/o/devops-engineer-3", "https://reiztech.recruitee.com/o/jr-mid-data-engineer"]}
```

#### Get Job Details
GET: http://localhost:8000/api/jobs/{id}

#### Delete Job 
DELETE: http://localhost:8000/api/jobs/{id}

## License
The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
