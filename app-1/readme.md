# API Reference

## Notes

### Note Object Example

```javascript
{
    "id": 1, // Read Only
    "title": "Title of a note",
    "text": "Text of a note",
    "created_at": "2022-06-07 22:55:26", // Read Only
    "updated_at": "2022-06-07 22:55:26"  // Read Only
}
```

### Getting all Notes

**GET** `/app-1/notes`

Sample response:

```javascript
{
    "_status": true,
    "notes": [] // Array of Notes
}
```

### Adding a new Note

**POST** `/app-1/note/create`

Request body (JSON-encoded):

```javascript
{
    "title": "Title of a new note",
    "text": "Text of a new note"
}
```

Sample response:

```javascript
{
    "_status": true,
    "note": {
        "id": 2,
        "title": "Title of a new note",
        "text": "Text of a new note",
        "created_at": "2022-06-07 22:55:26",
        "updated_at": "2022-06-07 22:55:26"
    }
}
```

### Updating existed Note

**POST** `/app-1/note/{id}/create` where `{id}` is an `id` of updating Note

Request body (JSON-encoded):

```javascript
{
    "title": "Updated title",
    "text": "Edited text"
}
```

Sample response:

```javascript
{
    "_status": true,
    "note": {
        "id": 2,
        "title": "Updated title",
        "text": "Edited text",
        "created_at": "2022-06-07 22:55:26",
        "updated_at": "2022-06-07 23:05:56"
    }
}
```

### Deleting existed Note

**POST** `/app-1/note/{id}/delete` where `{id}` is an `id` of updating Note

Request body can be empty

Sample response:

```javascript
{
    "_status": true
}
```


