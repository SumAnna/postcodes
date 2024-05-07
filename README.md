# Postcodes Project

## Description
A stand-alone PHP app that has:<br />
● Console command to download and import UK postcodes into database<br />
● A controller action to add a new store/shop to the database with:<br />
  ○ Name<br />
  ○ Geo coordinates<br />
  ○ open/closed status<br />
  ○ store type (takeaway, shop, restaurant)<br />
  ○ a max delivery distance.<br />
● Controller action to return stores near to a postcode (latitude, longitude) as a JSON API response, that would be suitable for use by a mobile app.<br />
● Controller action to return stores can deliver to a certain postcode as a JSON API response, that would be suitable for use by a mobile app.<br />

## Installation

Clone this repository using:
```bash
git clone https://github.com/SumAnna/postcodes.git
```

Run command:
```bash
cd your-project-name
```

Install dependencies:
```bash
composer install
npm install
```

Create .env file:
```bash
cp .env.example .env
```

Create .env file:
```bash
cp .env.example .env
```

Update .env configuration regarding your needs.<br/>

Run migrations:
```bash
php artisan migrate
```

Run seeds:
```bash
php artisan db:seed
```

Run command:
```bash
php artisan upload:postcodes
```
This will add your jobs to the queue

Run command to make listen to your queues in the order of their priority:
```bash
php artisan queue:work --queue=high,default,low
```
And wait for your command to finish.

Now add stores to your DB (you can add them manually through DB or initiate request to StoreController::add. REMEMBER! If you choose the second option, you'll need to initiate authentification). <br/>

## Usage

Now, when you set up the environment, let's try our API:

Send this request to your server (change {postcode} to valid UK postcode, e.g. M27 9UZ):
```bash
/api/stores/{postcode}
```
This should show you the stores within 1 mile around the area.<br/>

To change the number of miles, just add a new parameter to the route, like this (change {miles} to the miles distance you need, e.g. 2.46262):
```bash
/api/stores/{postcode}/{miles}
```

Send this POST request to our API to get all the stores that are opened and deliver to the postcode (change {postcode} to valid UK postcode, e.g. M27 9UZ):
```bash
/api/delivery/{postcode}
```
