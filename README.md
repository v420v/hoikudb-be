# 保育DB バックエンド

## ローカル環境構築方法
```
$ git clone ...
$ cd ...
```

.env を以下の内容で作成
## .env
```
VOLUME=.data

MYSQL_DATABASE=hoikudb
MYSQL_ROOT_PASSWORD=secret
MYSQL_USER=hoikudb
MYSQL_PASSWORD=hoikudb
```

## Laravel の設定　
```
$ cp ./src/.env.example ./src/.env
$ php artisan migrate
$ php artisan db:seed
```
