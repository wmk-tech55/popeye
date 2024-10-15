# popeye

## Prerequisites
> This Project Required Composer To Be Installed And PHP 7.3 Or Above
- PHP 7.3 Or Above 
- [Composer](https://getcomposer.org/)

## Online API Docs
 
- [Go Now]( https://documenter.getpostman.com/view/7861723/2s93JwP2zv )

## Installation

### Clone The Project

```bash
$ git clone https://github.com/hamzaomar92/popeye
$ cd popeye
```

### Install Composer Dependencies 

```bash
$ composer install
```
### Create .env Then Edit It

```bash
$ cp .env.example .env
```

### Generate Laravel Key 

```bash
$ php artisan key:generate
```

### Migrate The DB 

```bash
$ php artisan migrate
```
OR

### Migrate The DB With Seed

```bash
$ php artisan migrate --seed
```

### Run The Server

```bash
$ php artisan serve
```