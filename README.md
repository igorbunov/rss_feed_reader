composer install
npm install && npm run dev
copy .evn.example to .env
set db credentials

php artisan migrate:fresh --seed

php artisan serve

email: admin@admin.com
pass: password

email: admin2@admin.com
pass: password

