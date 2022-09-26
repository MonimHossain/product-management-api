## Steps of Runing this project

- First of all, clone this repository.
- Then, run the following command : composer install
- Go to root directory and copy .env.example and paste it there.
- Rename the .env.example file to .env
- Create a mysql database named "production_management_db"
- Then, run the following command : php artisan migrate.
- Then, run the following command : php artisan passport:install.
- It will give to id. Remember the Second ID's client_id, client_secret. It will be needed in UI repository.
- Last but not the least, run : php artisan serve
- Backend is good to go.

