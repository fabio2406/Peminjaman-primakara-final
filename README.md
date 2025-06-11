## Tutorial Running Local

- Install Composer min version : 2.7.2
- Install PHP min version : 8.2
- Install Node 

## Tutorial Install Depedency

- Install composer and depedency project
```console
composer install
```
```console
npm install
```
- Copy .env.example and paste change name to .env
```console
php artisan key:generate
```
- Create database : sistem_peminjaman_barang_ruangan
- Import sql file sistem_peminjaman_barang_ruangan.sql to db 

## Tutorial Running Local
- Open two terminal type
 ```console
php artisan serve
```
AND
```console
npm run dev
```