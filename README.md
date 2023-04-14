## How to Run 
- Rename `.env.example` file to .env inside your project root and fill the database information.
- Open the console and cd your project root directory
- Run `composer install` or `php composer.phar install`
- Run `php artisan key:generate`
- Run `php artisan migrate`
- Run `php artisan serve`
- Access end point using postman or insomnia


## API Endpoints

### Posts
| Methods        | Endpoints                                | Description                           |
| :------------- | :----------                              | :-----------                          |
| `POST`         | /login                                   | Login User must given body request    |
| `POST`         | /register                                | Create User must given body request   |

### Posts - Role (Admin)
| Methods        | Endpoints                                | Description                           |
| :------------- | :----------                              | :-----------                          |
| `GET`          | /posts                                   | Get all post include comment          |
| `POST`         | /posts                                   | Create post must given body request   |
| `GET`          | /posts/{post_id}                         | Get specified post include comment    |
| `POST`         | /posts/{post_id}                         | Update post must given body request   |
| `DELETE`       | /posts/{post_id}                         | Edit post required body request       |

### Comments - Role (Admin,User)
| Methods        | Endpoints                                | Description                                      |
| :------------- | :----------                              | :-----------                                     |
| `POST`         | /comments                                | Create comment in post must given body request   |



## Scope
- Event & Listener
- Database migration
- Passport
- Redis queue