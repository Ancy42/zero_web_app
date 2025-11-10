# Laravel Repository Pattern

![GitHub issues](https://img.shields.io/github/issues/joynal-a/maker?style=flat-square)
![GitHub forks](https://img.shields.io/github/forks/joynal-a/maker?style=flat-square)
![GitHub stars](https://img.shields.io/github/stars/joynal-a/maker?style=flat-square)
![GitHub license](https://img.shields.io/github/license/joynal-a/maker?style=flat-square)



## 🚀 Overview
This repository offers a ready-to-use implementation of the Repository Pattern in Laravel. The repository pattern separates the logic that retrieves data from a model and allows for better flexibility and scalability when interacting with databases.

## 🌟 Requirements
- **PHP** ^8.x
- **Laravel** ^8.x

## ✨ Suggestion
- It's recommended to start with a fresh Laravel project when using this repository pattern template, but integration into existing projects is seamless as well.

## 📦 How To Install
Install this package effortlessly using Composer:

```bash
composer require joynala/maker
```
## 🛠️ Features
- **Repository Pattern:** A clean, modular implementation of the Repository Pattern for abstracting database queries and data access.

- **Single Responsibility:** Keeps your controllers lightweight by moving database logic to repositories.

- **Service Layer Integration:** Easily integrate with a service layer for better logic handling.

- **Model/Models Creation:** You can create one or multiple models, along with their migrations and controllers, using a single command.

- **Repository/Repositories Creation:** You can create one or multiple repositories for multiple models using a single command. For instance, to create just a single repository.


- **Unit Testable:** The repository pattern makes your database interactions easier to test in isolation.

## 🚀 What Happens After Installing This Package?
- The `php artisan make:model` command has undergone a luxurious transformation, featuring an added repository pattern.
  - **Command Excellence:**
    ```bash
    php artisan make:model ExampleModel -m
    ```
    ```bash
    php artisan make:model ExampleModel1 ExampleModel2 ExampleModel3 -m
    ```
    ```bash
    php artisan make:model ExampleModel -mc
    ```
    ```bash
    php artisan make:model ExampleModel1 ExampleModel2 ExampleModel3 -mc
    ```
    ```bash
    php artisan make:model ExampleModel -mcr
    ```
    ```bash
    php artisan make:model ExampleModel1 ExampleModel2 ExampleModel3 -mcr
    ```

- The `php artisan make:repository` command has undergone a luxurious transformation, featuring an added repository pattern.
  - **Command Excellence:**
    ```bash
    php artisan make:repository ExampleModel
    ```
    ```bash
    php artisan make:repository ExampleModel1 ExampleModel2 ExampleModel3
    ```

    Enjoy the ability to create a single model or multiple models at once, complete with migration/migrations. The system gracefully prompts you, inquiring if you wish to create a repository alongside the model.
  
Embark on your Laravel journey with the elegance and sophistication of Laravel Boiler Template! 
🌟 Add stars, fork the project, and engage with the community. 🚀
