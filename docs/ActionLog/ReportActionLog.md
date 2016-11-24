## Report Action Log
Log actions that made by report

* [Search registration report](#search-registration-report)
* [Search comment report](#search-comment-report)
* [Search project report](#search-project-report)
* [Search event report](#search-event-report)
* [Search questionnaire report](#search-questionnaire-report)
* [Edit event application memo](#edit-event-application-memo)
* [Approve event application](#approve-event-application)

---

### Search registration report

#### Log action
`Search registration report`

#### Log data

    {
      "adminer_id": "integer",
      "search_query": "string"
    }
    
---

### Search comment report

#### Log action
`Search comment report`

#### Log data

    {
      "adminer_id": "integer",
      "search_query": "string"
    }
    
---

### Search project report

#### Log action
`Search project report`

#### Log data

    {
      "adminer_id": "integer",
      "search_query": "string"
    }
    
---

### Search event report

#### Log action
`Search event report`

#### Log data

    {
      "adminer_id": "integer",
      "event_id": "integer"
      "search_query": "string"
    }
    
---

### Search questionnaire report

#### Log action
`Search questionnaire report`

#### Log data

    {
      "adminer_id": "integer",
      "event_id": "integer"
      "search_query": "string"
    }
    
---

### Edit event application memo

#### Log action
`Edit event application memo`

#### Log data

    {
      "adminer_id": "integer",
      "request_data": "string",
    }
    
---

### Approve event application

#### Log action
`Approve event application`

#### Log data

    {
      "adminer_id": "integer",
      "request_data": "string",
    }
    
---
