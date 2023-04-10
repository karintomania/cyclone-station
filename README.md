# Art API
API for my personal project.

# Authentication
There are 2 endpoints for user authentication.

| URI  | METHOD | Desc |
| - | - | - |
| /auth/createAccount  | POST | Create user  |
| /auth/token  | GET | Retrieve bearer token |

## /auth/createAccount
Create a new user and return user id.

### Sample Payload
```
Content-Type: application/json

{
    "name":"test name",
    "email":"test@example.com",
    "password":"password123"
}
```

### Sample Response
The successful request returns the user id of the created account.
```
// created user ID
21
```

## /auth/token
Authentication in this API is done by bearer token.
You can use `/auth/token` endpoint to get the token.

### Sample Payload
```
// Content-Type: application/json

{
    "email":"test@example.com",
    "password":"password123"
}
```

### Sample Response
The successful request returns the token.
```
xxxxxxx0123456789
```

# CRUD for Item
CRUD for Items are done by these endpoints.

| URI  | METHOD | Desc |
| - | - | - |
| /items  | GET | Get list of items  |
| /items  | POST | Add item |
| /items/{id}  | PUT | Update item |
| /items/{id}  | DELETE | Delete item |

## Authentication
These endpoints requires bearer token.
Use `/auth/token` endpoint to get a token and include it in the request's header.

## Get Item List
`/items (GET)` returns the list of registered items under the user.
The sample response looks like:
```
[
    {
        "id": 9,
        "user_id": 19,
        "title": "test title",
        "description": "test description",
        "created_at": "2023-04-10T08:38:43.000000Z",
        "updated_at": "2023-04-10T08:38:43.000000Z",
        "colours": ["#123456", "#234567", "#345678", "#456789"]
    }
    {
        "id": 10,
        "user_id": 19,
        "title": "test title 2",
        "description": "test description 2",
        "created_at": "2023-04-10T08:38:43.000000Z",
        "updated_at": "2023-04-10T08:38:43.000000Z",
        "colours": ["#123456", "#234567", "#345678", "#456789"]
    }
]
```

## Add Item
The sample payload for `/items (POST)` looks like this:
```
// Content-Type: application/json
// Authorization: Bearer XXXXXXXX123456789

{
    "title": "test title",
    "description": "test description",
    "colours": [
        "#123456",
        "#234567",
        "#345678",
        "#456789"
    ]
}
```

## Edit Item
The sample payload for `/items/{id} (PUT)` looks like below:
```
// Content-Type: application/json
// Authorization: Bearer XXXXXXXX123456789

{
    "title": "edited title",
    "description": "edited description",
}
```

***Note***
This endpoint only accepts updates for `title` and `description`. Updates on colours are not supported.

## Delete Item
`/items/{id} (DELETE)` deletes the specified item and colours.

