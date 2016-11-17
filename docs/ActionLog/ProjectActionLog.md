## Project Action Log
Log actions that made by project

* [CSV of Project](#csv-of-project)
* [Search project](#search-project)
* [Edit project](#edit-project)
* [Update status](#update-status)
* [Delete project](#delete-project)
* [Edit internal project memo](#edit-internal-project-memo)
* [Edit project manager](#edit-project-manager)
* [Project schedule released](#project-schedule-released)
* [Send mail of recommend experts](#send-mail-of-recommend-experts)

---

### CSV of Project

#### Log action
`CSV of Project {csv_type}`

* csv_type
    `all` or `this`

#### Log data

    {
      "adminer_id": "integer"
    }

---

### Search project

#### Log action
`Search project`

#### Log data

    {
      "adminer_id": "integer",
      "search_query": "string"
    }
    
---

### Edit project

#### Log action
`Edit project`

#### Log data

    {
      "adminer_id": "integer",
      "project_id": "integer",
      "origin_data": "array",
      "edit_data": "array"
    }
    
---

### Update status

#### Log action
`Update status`

#### Log data

    {
      "adminer_id": "integer",
      "project_id": "integer",
      "status": "string"
    }
    
---

### Delete project

#### Log action
`Delete project`

#### Log data

    {
      "adminer_id": "integer",
      "project_id": "integer"
    }
    
---

### Edit internal project memo

#### Log action
`Edit internal project memo`

#### Log data

    {
      "adminer_id": "integer",
      "project_id": "integer",
      "request_data": "string"
    }
    
---

### Edit project manager

#### Log action
`Edit project manager`

#### Log data

    {
      "adminer_id": "integer",
      "project_id": "integer",
      "request_data": "string"
    }
    
---

### Project schedule released

#### Log action
`Project schedule released`

#### Log data

    {
      "adminer_id": "integer",
      "project_id": "integer"
    }
    
---

### Send mail of recommend experts

#### Log action
`Send mail of recommend experts`

#### Log data

    {
      "adminer_id": "integer",
      "project_id": "integer",
      "recommend_experts": "array"
    }
    
---
