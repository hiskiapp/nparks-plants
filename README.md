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
