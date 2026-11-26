
# Biswas IT Firm - Lead Management API

## Project Description
A REST API developed using Laravel and MySQL
to collect and store customer inquiries from
the Biswas IT Firm website contact form.

## Technologies
- PHP
- Laravel
- MySQL
- REST API
- Postman

## Installation

1. Clone the repository.

2. Install dependencies:

   composer install

3. Copy environment file:

   cp .env.example .env

4. Generate application key:

   php artisan key:generate

5. Create MySQL database:

   biswas_lead_db

6. Configure database credentials in .env.

7. Run migrations:

   php artisan migrate

8. Start Laravel server:

   php artisan serve

## API Endpoint

POST /api/leads

## Request Body

{
    "name": "Sukhen Das",
    "email": "sukhen@gmail.com",
    "phone": "01712345678",
    "message": "I need web development services."
}

## Response

201 Created - Lead successfully saved.

422 Unprocessable Content - Validation failed.

## Features

- Customer lead submission
- Input validation
- MySQL database storage
- JSON API response
- Rate limiting
## New Features

- Input Validation
- Success Request Logging
- Failed Request Logging
- Validation Error Logging
- Server Error Logging

## Logging

Laravel application logs are stored in:

storage/logs/laravel.log

The application records:
- Successful lead submissions
- Validation failures
- Server and database errors

## Rate Limiting

Rate limiting has been implemented to protect
the Lead Form API from spam and excessive requests.

### Configuration

- Maximum Requests: 3
- Time Window: 1 Minute
- Middleware: Laravel Throttle
- Limit Scope: Per IP Address

### API Endpoint

POST /api/leads

### Testing Results

- First Request: 201 Created
- 3rd Request: 201 Created
- 4th Request: 429 Too Many Requests
- After Limit Reset: 201 Created
