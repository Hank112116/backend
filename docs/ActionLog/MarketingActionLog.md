## Marketing Action Log
Log actions that made by marketing

* [Update feature](#update-feature)
* [Update expert list of home page](#update-expert-list-of-home-page)

---

### Update feature

#### Log action
`Update feature`

#### Log data

    {
      "adminer_id": "integer",
      "features": "array",
      ...
    }

---

### Update expert list of home page

#### Log action
`Update expert list of home page`

#### Log data

    {
      "adminer_id": "integer",
      "experts": "array",
      ...
    }

---

### Add object to Low Priority List

#### Log action
`Low priority list add {object} id: {id}`

* object
    `user` or `project` or `solution`

#### Log data

    {
      "adminer_id": "integer",
      ...
    }

---

### Revoke object to Low Priority List

#### Log action
`Low priority list revoke {object} id: {id}`

* object
    `user` or `project` or `solution`

#### Log data

    {
      "adminer_id": "integer",
      ...
    }

---
