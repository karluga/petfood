# Kvalifikācijas darbs

## Local setup

1. Clone this repo
2. Install dependencies
```
composer install (est. 2-5min)
```
3. Add MySQL database credentials to `.env` file.
Credentials from Gmail SMTP free mail service (from their server)
 - They can be created in your google account.
 - [Google Account](https://myaccount.google.com/) -> Security -> 2-Step Verification (2FA) -> App Passwords
 - Delete the whitespaces from the password before pasting it in the `.env` file.
 - P.S. they let you see it <u>only once!</u>
 - [YT tutorial](https://www.youtube.com/watch?v=1YXVdyVuFGA&ab_channel=Sombex)
5. Migrations 
```
php artisan migrate
```

## Notes
### NOTE 1
To be able to test the app locally, you have to make a tunnel for Google OAuth login service.
1. Make an [Ngrok](https://ngrok.com/download) account.
2. Follow the steps to make a "tunnel" because google doesnt allow requests from localhost.
Run this in the ngrok command line tool and it will give you a URL for testing. It works until you close the commandline (probably)
```
ngrok http http://localhost:8000

```
Then you have to add these lines to the `config/services.php` file.
```
'google' => [
    'client_id' => 'XXX',
    'client_secret' => 'XXX',
    'redirect' => 'https://3a9b-2a03-ec00-b19b-19d4-14d2-8d38-f5fa-4400.ngrok-free.app/google/callback/'
],
```
3. [Link to Google Cloud console](https://console.cloud.google.com/apis/credentials)
Here you have to add the Ngrok generated URL to the "Authorized redirect URIs" section.
4. I have to warn you that you can only access the app from your new Ngrok URL.

### NOTE 2
To fix the problem with SSL in `vendor/guzzlehttp/guzzle/src/Client.php`
```
$defaults = [
    'allow_redirects' => RedirectMiddleware::$defaultSettings,
    'http_errors' => true,
    'decode_content' => true,
    'verify' => false,
    'cookies' => false,
    'idn_conversion' => false,
];
```
## Server setup
### Using Apache
1. Move all the code to server folder
2. Make an alias from "/" to "/public"