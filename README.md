# Laravel Installation Steps

1. Install Composer dependencies:
    ```
    composer install
    ```

2. Install NPM dependencies:
    ```
    npm install
    ```

3. Copy `.env.example` to `.env`:
    ```
    cp .env.example .env
    ```

4. Generate application key:
    ```
    php artisan key:generate
    ```

5. Run migrations (optional, if using a database):
    ```
    php artisan migrate --seed
    ```

6. Start the development server:
    ```
    composer run dev
    ```
