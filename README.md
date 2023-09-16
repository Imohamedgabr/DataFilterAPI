## Running the Application Using Docker

Execute the following commands to build and run the application:

```bash
docker-compose build app
docker-compose up -d
```

## Running Tests

Execute the following command to run the tests:

```bash
php artisan test
```

To run the tests from outside of the Docker container, use the following command:

```bash
docker-compose exec app php artisan test
```

## Interacting with the API

You can interact with the API using curl or Postman. Here's an example curl command:

```bash
curl --location --request GET 'localhost:8000/api/users'
```

## Design and Implementation Considerations

In the development of this application, several key principles and strategies were emphasized:

1. **Clean Code**: Code has been written with a focus on readability and maintainability.
2. **Exception Handling**: Robust exception handling mechanisms are in place to ensure graceful error handling and prevent application crashes.
3. **Type Linting**: Types are enforced in functions to catch potential type-related issues during compile-time.
4. **Performance Optimization**: An early return strategy is used where applicable to reduce unnecessary computations and improve efficiency.
5. **Memory Management**: Chunked file reading techniques are used to process large files without exceeding memory constraints.
6. **Scalability**: A modular DataProviderZ system is introduced for easy addition of new data providers. The addition can be done by modifying the configuration file (`/config/filepaths.php`) without impacting the core codebase.
7. **Testing**: Unit and feature tests are incorporated to validate the correctness and functionality of the codebase.
8. **Docker Integration**: Docker is used to provide a consistent and reproducible development and deployment environment, streamlining the setup process and facilitating collaboration among team members.
