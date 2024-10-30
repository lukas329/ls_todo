
API Documentation for ToDo Application

Authentication Routes
---------------------

1. User Registration
- URL: /register
- Method: POST
- Description: Registers a new user account.
- Request Body:
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password",
    "password_confirmation": "password"
}
- Response: 
  - Status: 201 Created
  - Body:
    {
        "message": "User registered successfully",
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com"
        }
    }

2. User Login
- URL: /login
- Method: POST
- Description: Authenticates a user and returns an API token.
- Request Body:
{
    "email": "john@example.com",
    "password": "password"
}
- Response:
  - Status: 200 OK
  - Body:
    {
        "message": "Login successful",
        "token": "your-access-token"
    }


ToDo Routes (Requires Authentication)
-------------------------------------

3. Get All ToDos
- URL: /todos
- Method: GET
- Description: Retrieves a list of ToDos for the authenticated user, including their own and shared items.
- Response:
  - Status: 200 OK
  - Body:
    [
        {
            "id": 1,
            "name": "Buy groceries",
            "description": "Buy milk, bread, and eggs",
            "creation_date": "2023-10-01",
            "done": false,
            "category_id": 1
        }
    ]

4. Create a ToDo
- URL: /todos
- Method: POST
- Description: Creates a new ToDo item for the authenticated user.
- Request Body:
{
    "name": "Complete project",
    "description": "Finish the final tasks",
    "creation_date": "2023-10-15",
    "done": false,
    "category_id": 1
}
- Response:
  - Status: 201 Created
  - Body:
    {
        "id": 2,
        "name": "Complete project",
        "description": "Finish the final tasks",
        "creation_date": "2023-10-15",
        "done": false,
        "category_id": 1
    }

5. Update a ToDo
- URL: /todos/{id}
- Method: PUT
- Description: Updates an existing ToDo item by ID.
- Request Body:
{
    "name": "Updated project name",
    "description": "Update project details",
    "done": true
}
- Response:
  - Status: 200 OK
  - Body:
    {
        "id": 2,
        "name": "Updated project name",
        "description": "Update project details",
        "done": true
    }

6. Soft Delete a ToDo
- URL: /todos/{id}
- Method: DELETE
- Description: Soft deletes a ToDo item.
- Response:
  - Status: 200 OK
  - Body:
    {
        "message": "ToDo item deleted successfully"
    }

7. Permanently Delete a ToDo
- URL: /todos/{id}/delete
- Method: DELETE
- Description: Permanently deletes a soft-deleted ToDo item.
- Response:
  - Status: 200 OK
  - Body:
    {
        "message": "ToDo item permanently deleted"
    }

8. Restore a Deleted ToDo
- URL: /todos/{id}/restore
- Method: POST
- Description: Restores a previously soft-deleted ToDo item.
- Response:
  - Status: 200 OK
  - Body:
    {
        "message": "ToDo item restored successfully"
    }

9. Share a ToDo with Another User
- URL: /todos/{todoId}/share
- Method: POST
- Description: Shares a ToDo item with another user.
- Request Body:
{
    "user_id": 3
}
- Response:
  - Status: 200 OK
  - Body:
    {
        "message": "ToDo shared successfully"
    }

10. Unshare a ToDo with Another User
- URL: /todos/{todoId}/unshare
- Method: POST
- Description: Removes sharing access from a user.
- Request Body:
{
    "user_id": 3
}
- Response:
  - Status: 200 OK
  - Body:
    {
        "message": "ToDo unshared successfully"
    }


Category Routes (Requires Authentication)
-----------------------------------------

11. Create a Category
- URL: /categories
- Method: POST
- Description: Creates a new category.
- Request Body:
{
    "name": "Work"
}
- Response:
  - Status: 201 Created
  - Body:
    {
        "id": 1,
        "name": "Work"
    }

12. Update a Category
- URL: /categories/{id}
- Method: PUT
- Description: Updates an existing category by ID.
- Request Body:
{
    "name": "Personal"
}
- Response:
  - Status: 200 OK
  - Body:
    {
        "id": 1,
        "name": "Personal"
    }

13. Delete a Category
- URL: /categories/{id}
- Method: DELETE
- Description: Deletes a category by ID.
- Response:
  - Status: 200 OK
  - Body:
    {
        "message": "Category deleted successfully"
    }
