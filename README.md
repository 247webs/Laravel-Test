# Laravel Test

## Used Technologies

1. `Framework`: Laravel 10
2. `Template Engine`: Blade Template
3. `Environment`: Docker
4. `Database`: Mysql
5. `Mail`: Mailtraip
6. `Payment`: Stripe (Using Laravel Cashier)


## Docker Container details:

1. `LARAVEL` for the Application.
2. `MYSQL` for the Mysql Database.
3. `MAILPIT` for Mailtraip Inbox.


## Command for Local setup

1. docker-compose up -d
2. docker exec -it LARAVEL
3. composer install
4. npm install
5. php artisan migrate --seed
6. npm run dev


## Hosting Server:

1. Application can be accessed here: http://localhost:80
2. Email Inbox can be accessed here: http://localhost:8025


## Default User (With SuperAdmin Role):
	Username: admin@laravel.com
	Password: password


## Default User (Without any Role):
	Username: user@laravel.com
	Password: password


# Glance at the UI

![Image](./images/Login.png?q=1)
![Image](./images/Signup.png?q=2)
![Image](./images/ProductListing.png?q=3)
![Image](./images/Checkout.png?q=4)
![Image](./images/Mail.png?q=5)
![Image](./images/Dashboard.png?q=6)
![Image](./images/UserListing.png?q=7)



## Business Use Cases:

1. Product Listing page:
This is a publically accessible page, which lists all the existing products in the application. Any Logged-in and Non-logged-in user can access this page.

2. Login page: 
To Purchase any product, users must have to login to the system.
A logged in user can see its dashboard where he can see the Credit Card 4 digits, List of products purchased, ability to cancel and purchase product.

3. Signup Page:
Any user can be registered to the application just by entering his full name, email and password. Note: Email verification is not implemented.

4. User Listing Page:
Only super admin can access this page. All the users of the application will be listed here. Any user with “B2C Customer” or “B2B Customer” role can be deactivated by the super admin.

5. Dashboard Page:
User can see his Card Number's last 4 digits, purchase history, and can cancel any purchased product.


## Key Features:

1. When a product is purchased, send an email to the customer: Done.
2. Send an email to the customer if their access is canceled for any reason: Done
3. Payment can be failed, handle this case: Not Covered (Stripe Webhooks can be used to handle this case.)




## Backend System Highlights

1. Docker is used for OS level virtualisation
2. Database migrations are implemented for creating database tables.
3. Database seeder is implemented to create a default user with the SuperAdmin role and a default user without any role.
4. Stripe Configuration Keys are stored in the environment file in the root directory. 
5. Laravel Event Listeners are used for performing asynchronous tasks.


## For Sending Emails

1. About Host: We are using Mailtraip Host for sending emails. To check the incoming emails, you can access the mailtrap inbox at this Url: http://localhost:8025 (At  local instance)
2. About Package: Laravel Default Notification System is used for sending emails.
3. Approach: Sending email is a background task, so, I am using Laravel event listeners for sending emails.


## "Stripe" Integration

- Products: The Products are created at Stripe. For testing purposes, I have created only 2 products.

B2C Product, Price: $10
B2B Product, Price: $20


- Customers: Whenever a new user is registered in our application, the system will create a new customer at Stripe as well.


- Payment Success: We set up the “Payment_intent.payment_success” Webhook, which conveys to our system when the payment is successed.

- Payment Failure: There are many different reasons why a payment does not succeed, like,

1. Payment is declined by the card issuers (Like Card is no longer valid, Customer manually declined the transaction)
2. Payment blocked by the Stripe automated fraud prevention toolset called ”Radar”, which blocks high-risk payments.
3. Invalid payment information sent to Stripe via API.

To track the failed payment status, we can set the webhook with “payment_intent.payment_failed” event. But it's not implemented.


## Note:

- In general behaviour, SuperAdmin do not purchase the products. So the Purchase button is intentinally disabled in this case.



## Not Covered:

1. Due to the given time limit, I did not use the Spatie package for Role based access. Instead I used constants for assigning roles to the users. But of course we can use a package for assigning role based permissions.
2. I primarily focused on implementing the complete checkout process, so I did not focus much on frontend design.



## Stripe Access:
	Username: 247webs@gmail.com
	Password: Any@874523


## Mail trap Access:
	Username: 247webs@gmail.com
	Password: Any@874523
