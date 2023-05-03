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
