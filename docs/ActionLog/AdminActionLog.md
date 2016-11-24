## Admin Action Log
Log actions that made by system admin

* [Log in](#log-in)
* [New member of backend team](#new-member-of-backend-team)
* [New Role of backend team](#new-role-of-backend-team)
* [CSV of Backend team](#csv-of-backend-team)
* [Edit member of backend team](#edit-member-of-backend-team)
* [Delete member of backend team](#delete-member-of-backend-team)
* [Edit Role of backend team](#edit-role-of-backend-team)

---

### Log in

#### Log action
`Log in`

#### Log data

    {
      "adminer_id": "integer",
      "email": "string",
      "success": "boolean"
    }

---

### New member of backend team

#### Log action
`New member of backend team`

#### Log data

    {
      "adminer_id": "integer",
      "backend_member": "integer",
      "name": "string",
      "email": "string",
      "role": "integer",
      "hwtrek_member": "integer",
    }
    
---

### New Role of backend team

#### Log action
`New Role of backend team`

#### Log data

    {
      "adminer_id": "integer",
      "role_id": "integer",
      "name": "string",
      "privilege": "string"
    }
    
---

### CSV of Backend team

#### Log action
`CSV of Backend team`

#### Log data

    {
      "adminer_id": "integer"
    }
    
---

### Edit member of backend team

#### Log action
`Edit member of backend team`

#### Log data

    {
      "adminer_id": "integer",
      "backend_member": "integer",
      "name": "string",
      "email": "string",
      "role": "integer",
      "hwtrek_member": "integer",
    }
    
---

### Delete member of backend team

#### Log action
`Delete member of backend team`

#### Log data

    {
      "adminer_id": "integer"
    }
    
---

### Edit role of backend team

#### Log action
`Edit role of backend team`

#### Log data

    {
      "adminer_id": "integer",
      "role_id": "integer",
      "name": "string",
      "privilege": "string"
    }
    
---
