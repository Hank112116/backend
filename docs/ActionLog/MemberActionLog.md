## Member Action Log
Log actions that made by member

* [CSV of Members](#csv-of-members)
* [Search user](#search-user)
* [Edit user](#edit-user)
* [Suspend User](#suspend-user)
* [Unsuspend User](#unsuspend-user)
* [Edit user internal memo](#edit-user-internal-memo)
* [Upload attachment of member](#upload-attachment-of-member)
* [Approve pending expert](#approve-pending-expert)
* [Change user type](#change-user-type)
* [Approve schedule of project](#approve-schedule-of-project)

---

### CSV of Members

#### Log action
`CSV of Members {csv_type}`

* csv_type
    `all` or `this`

#### Log data

    {
      "adminer_id": "integer"
    }

---

### Search user

#### Log action
`Search user`

#### Log data

    {
      "adminer_id": "integer",
      "search_query": "string"
    }
    
---

### Edit user

#### Log action
`Edit user`

#### Log data

    {
      "adminer_id": "integer",
      "user": "integer",
      "origin_data": "array",
      "is_expert": "boolean",
      "edit_data": "array"
    }
    
---

### Suspend User

#### Log action
`Suspend User {user_id}`

#### Log data

    {
      "adminer_id": "integer",
      "user_id": "integer"
    }
    
---

### Unsuspend User

#### Log action
`Unsuspend User {user_id}`

#### Log data

    {
      "adminer_id": "integer",
      "user_id": "integer"
    }
    
---

### Edit user internal memo

#### Log action
`Edit user internal memo`

#### Log data

    {
      "adminer_id": "integer",
      "request_data": "string",
    }
    
---

### Upload attachment of member

#### Log action
`Upload attachment of member`

#### Log data

    {
      "adminer_id": "integer",
      "key": "string",
      "name": "string",
      "url": "url",
      "contentType": "string",
      "size": "integer",
      "lastModified": "integer",
      "previews": "array",
      "attachedType": "string"
    }
    
---

### Approve pending expert

#### Log action
`Approve pending expert {user_id}`

#### Log data

    {
      "adminer_id": "integer",
      "user_id": "integer"
    }
    
---

### Change user type

#### Log action
`Change user type`

#### Log data

    {
      "adminer_id": "integer",
      "user_id": "integer",
      "user_type": "string"
    }
    
---

### Approve schedule of project

#### Log action
`Approve schedule of project`

#### Log data

    {
      "adminer_id": "integer",
      "user_id": "integer",
      "user_type": "string"
    }
    
---
