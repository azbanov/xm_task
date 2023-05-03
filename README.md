# XM Testing Task Application
[XM.COM](https://www.xm.com)
This application developed on PHP v8.2, with Symfony Framework v6.2.
Application is fully dockerized. 

Set **RAPIDAPI_KEY**, **MAILER_DSN** and **MAIL_FROM** variables in `.env` file.

For Unix users will be convenient to use `Makefile` commands like: `make --command--`

Windows users can check docker commands in `Makefile`.

For starting application in `dev` mode use `make start.dev`

For starting application in `prod` mode use `make start.prod`

Execute tests, static analysis and code sniffer in `dev` mode.

How to start application:
* Set environment variables in .env file.
* `make start.prod`
* Take some coffee break
* `make composer.install`
* Open `http://localhost:8083` in browser

### Enjoy!!!

# Task defintion
PHP Exercise - v21.0.5
In this exercise you need to create a PHP application that will have a form with the following fields:
● Company Symbol
● Start Date (YYYY-mm-dd) ● End Date (YYYY-mm-dd) ● Email
The available options for the Company Symbol field can be found here: https://pkgstore.datahub.io/core/nasdaq-listings/nasdaq-listed_json/data/a5bc7580d6176d60ac0 b2142ca8d7df6/nasdaq-listed_json.json
After the form submission, the following actions must be done:
1) Validate the form both on client and server side and display the appropriate messages on both cases. Validation rules:
● Company Symbol: required, valid symbol
● Start Date: required, valid date, less or equal than End Date, less or equal than current
date
● End Date: required, valid date, greater or equal than Start Date, less or equal than
current date
● Email: required, valid email
2) Display on screen the historical quotes for the submitted Company Symbol in the given date range in table format. Table columns:
Date | Open | High | Low | Close | Volume
Historical data should be retrieved using “https://yh-finance.p.rapidapi.com/stock/v3/get-historical-data” API.
Example:
https://yh-finance.p.rapidapi.com/stock/v3/get-historical-data?symbol=AMRN&region=US
Header Parameters:
● X-RapidAPI-Key: ab73dfec93mshd406a6d1fc5ee32p11dab3jsn43a805065966
● X-RapidAPI-Host: yh-finance.p.rapidapi.com
Required Parameters: ● symbol
Optional Parameters: ● region
NOTE: Historical data service requires the token X-RapidAPI-Key which is provided in the same email with this document.
3) Based on the Historical data retrieved, display on screen a chart of the Open and Close prices.
4) Send to the submitted Email an email message, using as: ● Subject: the submitted company’s name
○ i.e. for submitted Company Symbol = GOOG => Company’s Name = Google ● Body: Start Date and End Date
○ i.e. From 2020-01-01 to 2020-01-31
Notes
● The exercise can be developed using plain PHP, but PHP framework (Symfony, Laravel etc) is preferred
● Tests are mandatory part of the exercise
● The user must enter Start Date and End Date using jQuery UI datepicker or any other
plugin/component with similar functionality (http://jqueryui.com/datepicker/)
● You can use any chart library like Chart.js (https://www.chartjs.org/)
● Keep in mind that we cannot change “/etc/hosts” or
“C:\Windows\System32\drivers\etc\hosts” on our PCs
● Create a GitHub repository and share it with the account:
phpinterviews@trading-point.com
● Have fun
Bonus
Any of the following will be considered a bonus but are not required
● Use Docker
● Have 100% testing coverage. Backend and Frontend
● Use Dependency Injection
