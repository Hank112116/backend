## Comments Action Log
Log actions that made by comments

* [Search expert comments](#search-expert-comments)
* [Search project comments](#search-project-comments)
* [Search solution comments](#search-solution-comments)
* [Delete comments](#delete-comments)

---

### Search expert comments

#### Log action
`Search expert comments by {search_type}`

* search_type
    `topic_creator` or `profession_name`

#### Log data

    {
      "adminer_id": "integer"
    }

---

### Search project comments

#### Log action
`Search project comments by {search_type}`

* search_type
    `topic_creator` or `owner` or `title`

#### Log data

    {
      "adminer_id": "integer"
    }

---

### Search solution comments

#### Log action
`Search solution comments by {search_type}`

* search_type
    `topic_creator` or `owner` or `title`

#### Log data

    {
      "adminer_id": "integer"
    }

---

### Delete comments

#### Log action
`Delete comments`

#### Log data

    {
      "adminer_id": "integer",
      "comment_id": "integer"
    }

---
