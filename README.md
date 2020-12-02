# SP API PHP Sandbox
I guess you just like me, prefer to see working implementation instead of reading some documentation.
So here you are! Working PHP implementation of SP API. 
This sandbox will show you all initial steps you need to do to lunch SP API in your project.
In my project we use Symfony to manage dependencies and properties and store AccessToken in DB.
Feel free to contribute to this repo!

## Requirements
* Install [Composer](https://getcomposer.org/)
* Complete this guide -> [Registering as a developer](https://github.com/amzn/selling-partner-api-docs/blob/main/guides/developer-guide/SellingPartnerApiDeveloperGuide.md#registering-as-a-developer)
* Complete this guide -> [Registering your Selling Partner API application](https://github.com/amzn/selling-partner-api-docs/blob/main/guides/developer-guide/SellingPartnerApiDeveloperGuide.md#registering-your-selling-partner-api-application)

## How to run?
* Run `composer install`
* Fill out [.env](.env) file
* Follow [Self Authorization](https://github.com/amzn/selling-partner-api-docs/blob/main/guides/developer-guide/SellingPartnerApiDeveloperGuide.md#self-authorization) and receive `refresh_token`
* Add `refresh_token` to [.env](.env) file
* Run `index.php` and you should see a `var_dump` of orders data from API