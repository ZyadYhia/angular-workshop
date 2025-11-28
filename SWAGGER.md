# Swagger API Documentation

This project uses **L5-Swagger** (darkaonline/l5-swagger) to automatically generate OpenAPI/Swagger documentation for all API endpoints and database schemas.

## Accessing the Documentation

Once your Laravel development server is running, you can access the interactive Swagger UI at:

```
http://localhost:8000/api/documentation
```

Or if using a different port/domain, replace accordingly:

```
http://your-domain.com/api/documentation
```

## Features

The Swagger documentation includes:

### 📝 API Endpoints Documentation

-   **Authentication** - User registration, login, logout, token refresh, profile management
-   **Categories** - CRUD operations for skill categories
-   **Skills** - CRUD operations for skills within categories
-   **Exams** - CRUD operations, exam taking, question viewing, and submission

### 🗄️ Database Schema Documentation

All models are documented with their database table structures:

-   **User** - User accounts with authentication
-   **Category** - Skill categories (translatable)
-   **Skill** - Skills within categories (translatable)
-   **Exam** - Exams for each skill (translatable)
-   **Question** - Exam questions with multiple choice options (translatable)

### 🔐 Authentication

The API uses **JWT Bearer token** authentication. In the Swagger UI:

1. Click the "Authorize" button (lock icon)
2. Enter your JWT token in the format: `Bearer your-token-here`
3. All authenticated endpoints will now include your token

## Generating Documentation

After making changes to API endpoints or model schemas, regenerate the documentation:

```bash
php artisan l5-swagger:generate
```

## Configuration

The L5-Swagger configuration file is located at:

```
config/l5-swagger.php
```

Key settings:

-   **Title**: Angular Workshop API Documentation
-   **Security**: JWT Bearer authentication
-   **Format**: JSON (OpenAPI 3.0)
-   **Output**: `storage/api-docs/api-docs.json`

## Auto-generation in Development

To automatically regenerate documentation on each request during development, update your `.env` file:

```env
L5_SWAGGER_GENERATE_ALWAYS=true
```

**Note**: Set this to `false` in production for better performance.

## API Annotations

All API endpoints are documented using PHP 8 attributes in the controllers:

-   `#[OA\Get]`, `#[OA\Post]`, `#[OA\Put]`, `#[OA\Delete]` - HTTP methods
-   `#[OA\RequestBody]` - Request payload documentation
-   `#[OA\Response]` - Response documentation
-   `#[OA\Parameter]` - Path/query parameters

All models use `#[OA\Schema]` attributes to document their database structure.

## Testing Endpoints

The Swagger UI provides a "Try it out" feature for each endpoint:

1. Click on any endpoint to expand it
2. Click "Try it out"
3. Fill in the required parameters
4. Click "Execute"
5. View the response below

## Additional Resources

-   [L5-Swagger Documentation](https://github.com/DarkaOnLine/L5-Swagger)
-   [OpenAPI Specification](https://swagger.io/specification/)
-   [Swagger UI Documentation](https://swagger.io/tools/swagger-ui/)
