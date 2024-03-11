# NPARKS Plants

## Installation

1. Clone the repository
```sh
git clone https://github.com/hiskiapp/nparks-plants.git
```

2. Navigate to the project directory
```sh
cd nparks-plants
```

3. Install the dependencies
```sh
composer install
```

4. Create a new .env file
```sh
cp .env.example .env
```

5. Generate a new application key
```sh
php artisan key:generate
```

6. Create a new postgresql database and update the .env file with your database credentials
```sh
DB_CONNECTION=pgsql
DB_HOST=
DB_PORT=5432
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

7. Run the migrations and seed the database
```sh
php artisan migrate --seed
```

8. Start the server
```sh
php artisan serve
```

## Usage
### Testing
To run the tests, simply run the following command:
```sh
php artisan test
```

## Database
![Main Database](https://github.com/hiskiapp/nparks-plants/blob/main/docs/database.png?raw=true)
Explanation:
- field_groups: Stores groups of fields, including a unique identifier and a name.
- fields: Contains individual field information, linking to their respective field group.
- plant_fields: Links plants to their attributes stored in fields, supporting multiple data types.
- plants: Holds information about plants, including an image and common names.

I made the fields dynamic so that in the future, any changes won't require hardcoding updates; simply adding new data to the database will suffice.

## API
### Field Groups
#### GET /api/v1/field-groups
Returns a list of field groups (for filter dropdown)

Request
```url
http://localhost:8000/api/v1/field-groups
```

Response
```json
{
    "status": 200,
    "data": [
        {
            "id": 33,
            "name": "Name"
        },
        {
            "id": 34,
            "name": "Biogeography"
        },
        {
            "id": 35,
            "name": "Classification and Characteristics"
        }
    ]
}
```

### Fields
#### GET /api/v1/fields
Returns a list of fields (for filter dropdown too). Can filter by field_group_id (optional).

Request
```url
http://localhost:8000/api/v1/fields?field_group_id=34
```

Response
```json
{
    "status": 200,
    "data": [
        {
            "field_group_name": "Biogeography",
            "name": "Native Distribution",
            "type": "text",
            "options": null
        },
        {
            "field_group_name": "Biogeography",
            "name": "Native Habitat",
            "type": "select",
            "options": [
                "Options 1",
                "Options 2"
            ]
        },
        {
            "field_group_name": "Biogeography",
            "name": "Minimal Weight",
            "type": "number",
            "options": [],
        }
    ]
}
```

### Plants
#### POST /api/v1/plants
Retrieve a list of plants.
Explanation:
- The main reason for using POST is to be able to send a more complex JSON body.
- The design of these parameters was inspired by the [Notion API](https://developers.notion.com/reference/post-database-query).

Request
```url
http://localhost:8000/api/v1/plants
```

Raw Body
```json
{
    "page": 1,
    "search": null, // search by name or common_name
    "filters": [
        {
            "field": "Synonyms",
            "text": {
                "contains": "nisi"
            }
        },
        {
            "field": "Tree or Palm - Trunk Diameter Unit",
            "select": {
                "equals": "corporis"
            }
        },
        {
            "field": "Tree or Palm - Trunk Diameter Unit",
            "number": {
                "greater_than_or_equal_to": 3
            }
        },
    ],
    "sorts": [
        {
            "field": "name",
            "direction": "asc"
        }
    ]
}
```
Available filters:
- text
    - contains
- select
    - equals
- number
    - equals
    - greater_than
    - greater_than_or_equal_to
    - less_than
    - less_than_or_equal_to

Available sorts:
- field
    - name
    - common_name
- direction
    - asc
    - desc


Response
```json
{
    "status": 200,
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 600,
        "per_page": 10,
        "total": 6000
    },
    "data": [
        {
            "id": 20,
            "image": "https://via.placeholder.com/640x480.png/0066ee?text=plants+at",
            "name": "Et Placeat Saepe",
            "common_name": "Quo Non Adipisci"
        },
        {
            "id": 21,
            "image": "https://via.placeholder.com/640x480.png/00ccaa?text=plants+nihil",
            "name": "Amet Accusantium Cum",
            "common_name": "Fugit Blanditiis Minus"
        }
        // .. more data
    ]
}
```

### Plant Detail
#### GET /api/v1/plants/{id}
Returns a single plant detail.
Explanation:
- The field groups contain fields with their respective values.
- The field values are dynamic, supporting both text and number data types.
- I use text_value and number_value to maintain data type consistency.

Request
```url
http://localhost:8000/api/v1/plants/20
```

Response
```json
{
    "status": 200,
    "data": {
        "id": 20,
        "image": "https://via.placeholder.com/640x480.png/0066ee?text=plants+at",
        "name": "Et Placeat Saepe",
        "common_name": "Quo Non Adipisci",
        "field_groups": [
            {
                "field_group_name": "Name",
                "fields": [
                    {
                        "name": "Common Names",
                        "type": "text",
                        "text_value": "Culpa voluptas aliquam fugiat numquam delectus.",
                        "number_value": null
                    }
                    // .. more fields
                ]
            },
            {
                "field_group_name": "Biogeography",
                "fields": [
                    {
                        "name": "Sightings in Singapore",
                        "type": "select",
                        "text_value": "quam",
                        "number_value": null
                    }
                    // .. more fields
                ]
            },
            {
                "field_group_name": "Classification and Characteristics",
                "fields": [
                    {
                        "name": "Chromosome Number",
                        "type": "text",
                        "text_value": "fugiat",
                        "number_value": null
                    }
                    // .. more fields
                ]
            }
        ]
    }
}
```
