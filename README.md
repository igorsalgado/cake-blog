# Blog Project

## Overview

This application is designed to provide a platform where users can create and manage blog posts, categories, and comments. It utilizes CakePHP for the backend and Vue.js for the frontend.

## Architecture

### Database Schema

![Database diagram](https://github.com/igorsalgado/cake-blog/blob/master/resources/Schema.png?raw=true)

The database schema includes the following tables:

#### `users`
- `id` (INTEGER, Primary Key, Auto Increment)
- `name` (VARCHAR)
- `email` (VARCHAR, Unique)
- `password` (VARCHAR)
- `created_at` (DATETIME)
- `updated_at` (DATETIME)

#### `posts`
- `id` (INTEGER, Primary Key, Auto Increment)
- `title` (VARCHAR)
- `description` (TEXT)
- `user_id` (INTEGER, Foreign Key references `users.id`)
- `category_id` (INTEGER, Foreign Key references `categories.id`)
- `created_at` (DATETIME)
- `updated_at` (DATETIME)

#### `categories`
- `id` (INTEGER, Primary Key, Auto Increment)
- `name` (VARCHAR)
- `created_at` (DATETIME)
- `updated_at` (DATETIME)

#### `comments`
- `id` (INTEGER, Primary Key, Auto Increment)
- `name` (VARCHAR)
- `description` (TEXT)
- `post_id` (INTEGER, Foreign Key references `posts.id`)
- `created_at` (DATETIME)
- `updated_at` (DATETIME)

## API Endpoints

### Posts

- **GET /api/posts**: Retrieve a list of posts.
- **GET /api/posts/{id}**: Retrieve a specific post by ID.
- **POST /api/posts**: Create a new post.
- **PUT /api/posts/{id}**: Update a post by ID.
- **DELETE /api/posts/{id}**: Delete a post by ID.

### Categories

- **GET /api/categories**: Retrieve a list of categories.
- **POST /api/categories**: Create a new category.
- **PUT /api/categories/{id}**: Update a category by ID.
- **DELETE /api/categories/{id}**: Delete a category by ID.

### Comments

- **GET /api/comments**: Retrieve a list of comments.
- **POST /api/comments**: Create a new comment.
- **GET /api/comments/{id}**: Retrieve a specific comment by ID.
- **PUT /api/comments/{id}**: Update a comment by ID.
- **DELETE /api/comments/{id}**: Delete a comment by ID.


## Frontend

### Login.vue

The `Login.vue` component handles user authentication. It includes fields for:
- **Email:** The user's email address.
- **Password:** The user's password.

### RegisterForm.vue

The `RegisterForm.vue` component handles user registration. It includes fields for:
- **Name:** The user's full name.
- **Email:** The user's email address.
- **Password:** The user's password.
- **Password Confirmation:** Confirmation of the entered password.

### PostForm.vue

The `PostForm.vue` component is used for adding. It includes fields for:
- Title
- Description
- Category

### CommentForm.vue

The `CommentForm.vue` component is used for adding comments. It includes fields for:
- Name
- Description

### CategoryForm.vue

The `CategoryForm.vue` component is used for adding categories. It includes fields for:
- Name

### PostDetail.vue

The `PostDetail.vue` component displays the details of a post and includes the `CommentForm` for adding new comments associated with the post.


## Installation

1. **Clone the Repository**
   ```bash
   git clone https://github.com/igorsalgado/cake-blog.git
    ```

2. **Navigate to the Project Directory**
   ```bash
   cd .\cake-blog\
    ```

3. **Install Dependecies**

- For Backend (CakePHP)

    ```bash
      composer install
    ```

- For Frontend (Vue.js)

    ```bash
      cd .\webroot\vue\
    ```

    ```bash
      npm install
    ```
4. **Configuration**

- Read and edit the environment specific `config/app_local.php` and set up the
`'Datasources'` and any other configuration relevant for your application.
Other environment agnostic settings can be changed in `config/app.php`.

5. Run Migrations
```bash
bin/cake migrations migrate
```

6. Run the Development Server
- For CakePHP:

```bash
bin/cake server -p 8765
```
- For Vue.Js:

```bash
cd .\webroot\vue\
```

```bash
npm run serve
```

- Then visit `http://localhost:5174/` to see the home page.

## Considerações

### Resiliência

Para garantir que nosso sistema lide com erros, adotamos algumas práticas:

- **Tratamento de Erros:** No frontend e no backend, temos uma abordagem para lidar com erros. Evitando que o sistema falhe sem explicação.
- **Mensagens de Erro:** Quando algo dá errado, mostramos mensagens de erro para que os usuários saibam o que aconteceu e o que podem fazer a seguir.

### Performance

Para garantir que o sistema funcione de forma rápida, podemos usar algumas métodos para otimização:

- **Otimização de Consultas:** Nós otimizamos as consultas ao banco de dados e usamos índices para acelerar as buscas.
- **Cache:** Para dados que são acessados com frequência, podemos usar cache para evitar consultas repetidas ao banco de dados, melhorando assim a velocidade do sistema.

### Segurança

Para manter nossos dados e APIs seguras, podemos implementar as seguintes medidas:

- **Autenticação e Autorização:** Garantindo que apenas usuários autorizados possam acessar certas áreas do sistema.
- **Validação e Sanitização de Entrada:** Verificamos e limpamos os dados recebidos dos usuários para evitar injeções SQL e outros tipos de ataques.

### Simultaneidade

Para lidar com muitos usuários acessando o sistema ao mesmo tempo, adotamos as seguintes práticas:

- **Transações de Banco de Dados:** Usamos transações para garantir que as operações no banco de dados sejam realizadas corretamente, mesmo quando várias pessoas estão usando o sistema ao mesmo tempo.
- **Controle de Taxa (Rate Limiting):** Implementando mecanismos para limitar o número de requisições que um usuário pode fazer em um curto período, prevenindo abusos e ajudando a manter o sistema estável.
