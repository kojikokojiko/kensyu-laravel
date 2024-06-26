# testDB再作成の方法

```
mysql -u root -p

CREATE DATABASE test_db;

SHOW GRANTS FOR 'user'@'%';
FLUSH PRIVILEGES;

migraton実行
php artisan migrate --env=testing
```
