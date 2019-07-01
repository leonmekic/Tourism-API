# Tourism-API
Za pokretanje migracija i seedera potrebni su sljedeci koraci

1. 
U voyager.php file-u 'autoload_migrations' treba postaviti na false

2.
Pokrenuti migracije -> php artisan migrate

3.
U voyager.php file-u 'autoload_migrations' treba postaviti na true

4.
Pokrenuti migracije -> php artisan migrate

5. 
pokrenuti seedere

6.
pokrenuti komandu php artisan passport:install

7. 
U Models\User.php file-u promjenit constantu SuperAdminId = 1
