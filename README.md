
# OSIDApi Service



## Instalasi project

```bash
  git clone https://github.com/urmommine/OSIDApiService.git
  cd OSIDApiService
  composer install
  composer update
```
konfigurasi project
```bash
  cp .env.example .env
  php artisan key:generate
  php artisan migrate
```
```bash
Konfigurasi server web desa seperti host,port, usernname,
db_name, dll di .env
dan database.php

```
## Run API Service
```bash
php artisan serve --host=<ipv4-address> --port=<any>
```



    
## Dokumentasi lengkap REST API

[disini!](https://github.com/urmommine/OSIDApiService/blob/main/API_DOCS.md)
[consumer!]([https://github.com/urmommine/OSIDApiService/blob/main/API_DOCS.md](https://github.com/urmommine/OSIDApiConsume))



## Example REST API Usage

#### get token from login via postman

```http
  POST /api/login
```
```json
  {
    "email": "example@email.com",
    "password": "password"
  }
```
| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `-` | `string` | **Required**. Your email and password |

#### Get cluster by desa

```http
  GET /api/penduduk/clusters/detailed?desa=$desaname
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `desa`      | `string` | **Required**. nama desa |



