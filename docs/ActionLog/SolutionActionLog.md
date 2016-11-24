## Solution Action Log
Log actions that made by solution

* [CSV of Solution](#csv-of-solution)
* [Search solution](#search-solution)
* [Edit solution](#edit-solution)
* [Solution approved](#solution-approved)
* [Solution rejected](#solution-rejected)
* [Solution on shelf](#solution-on-shelf)
* [Solution off shelf](#solution-off-shelf)
* [Solution to program](#solution-to-program)
* [Program to solution](#program-to-solution)
* [Solution pending to program](#solution-pending-to-program)
* [Program pending to solution](#program-pending-to-solution)
* [Cancel pending to program](#cancel-pending-to-program)
* [Cancel pending to solution](#cancel-pending-to-solution)

---

### CSV of Solution

#### Log action
`CSV of Solution {csv_type}`

* csv_type
    `all` or `this`

#### Log data

    {
      "adminer_id": "integer"
    }

---

### Search solution

#### Log action
`Search solution`

#### Log data

    {
      "adminer_id": "integer",
      "search_query": "string"
    }
    
---

### Edit solution

#### Log action
`Edit solution`

#### Log data

    {
      "adminer_id": "integer",
      "solution_id": "integer",
      "origin_data": "array",
      "edit_data": "array"
    }
    
---

### Solution approved

#### Log action
`Solution approved`

#### Log data

    {
      "adminer_id": "integer",
      "solution_id": "integer"
    }
    
---

### Solution rejected

#### Log action
`Solution rejected`

#### Log data

    {
      "adminer_id": "integer",
      "solution_id": "integer"
    }
    
---

### Solution on shelf

#### Log action
`Solution on shelf`

#### Log data

    {
      "adminer_id": "integer",
      "solution_id": "integer"
    }
    
---

### Solution off shelf

#### Log action
`Solution off shelf`

#### Log data

    {
      "adminer_id": "integer",
      "solution_id": "integer"
    }
    
---

### Solution to program

#### Log action
`Solution to program`

#### Log data

    {
      "adminer_id": "integer",
      "solution_id": "integer"
    }
    
---

### Program to solution

#### Log action
`Program to solution`

#### Log data

    {
      "adminer_id": "integer",
      "solution_id": "integer"
    }
    
---

### Solution pending to program

#### Log action
`Solution pending to program`

#### Log data

    {
      "adminer_id": "integer",
      "solution_id": "integer"
    }
    
---

### Program pending to solution

#### Log action
`Program pending to solution`

#### Log data

    {
      "adminer_id": "integer",
      "solution_id": "integer"
    }
    
---

### Cancel pending to program

#### Log action
`Cancel pending to program`

#### Log data

    {
      "adminer_id": "integer",
      "solution_id": "integer"
    }
    
---

### Cancel pending to solution

#### Log action
`Cancel pending to solution`

#### Log data

    {
      "adminer_id": "integer",
      "solution_id": "integer"
    }
    
---
