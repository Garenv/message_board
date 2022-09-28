Message Exchange API

Make sure you configure your local DB in the `.env` file since you'll need to GET and POST data.  Example of a locally configured DB is shown below.

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=messageBoard
DB_USERNAME=root
DB_PASSWORD=root
```

***Running the project***

In the project's directory:

1. Run `composer install` to install all the dependencies specified in the `composer.lock` file.

2. Run `php artisan migrate` to migrate the tables and columns to your locally configured DB.

3. Run `php artisan serve` to start the project.

Example of how some endpoints will look like:

- `http://127.0.0.1:8000/api/createThreadMessage/4?userId=2&body=someTest`


- `http://127.0.0.1:8000/api/getThreadMessages/2`

Please refer to the `api.php` file to see how other endpoints are set up.

```
Doug - I haven't found requirement 5 anywhere, the extra feature of your choice
```
