# Sofi - A scaffold project for Slim framework

**Sofi** (*Slim out fat in*) is a conceptional scaffold used for developing web apps (sorry, I have never really used it, it derived from a project of mine, I hard coded these components in my project and continuing refining these components after I started my development), it based on [Slim framework 4](https://www.slimframework.com/) and some other common used packages (chiefly symfony family). By using it, you can quickly set up a project and focus on your business.

- [Tutorial](#tutorial)
  - [Configure your project](#configure-your-project)
  - [Run or deploy your project](#run-or-deploy-your-project)
  - [What you still have to do](#what-you-still-have-to-do)
    - [Set up your cache and database](#set-up-your-cache-and-database)
    - [Cookies](#cookies)
  - [Folder structure](#folder-structure)
- [Conceptions](#conceptions)
  - [Bootstrap](#bootstrap)
  - [Request lifecycle](#request-lifecycle)
  - [Exceptions](#exceptions)
- [Dependencies](#dependencies)
  - [DI (Dependency Injection) - PHP-DI](#di-dependency-injection---php-di)
  - [Console - symfony/console](#console---symfonyconsole)
  - [i18n](#i18n)
  - [Miscellaneous](#miscellaneous)
- [Extensions](#extensions)
  - [Doctrine](#doctrine)
    - [Pagination](#pagination)
- [About](#about)
  - [What does Shisa means?](#what-does-shisa-means)


## Tutorial
### Configure your project
The scaffold lies in `app` folder, the root namespace of the project is `App`, you can move your application folder to wherever you want or change the namespace to whatever you like.

If you have no intention to change the application's directory or namespace, you need only to follow these instructions:

1. Set up your configuration lies in your application's [`Configurations`](app/Configurations/) folder.
2. Configure your dependency injection in your application's [`Bootstrap`](app/Bootstrap.php).
3. Register middlewares and routes of your project in your application's [`ApplicationFactory`](app/Application/ApplicationFactory.php)

### Run or deploy your project
You need set a environment variable called `APPLICATION_ENVIRONMENT` to determine what environment you are in. Sofi will load the corresponding configuration file in your application's [`Configurations`](app/Configurations/) folder.

To run your application, just follow [Slim's official document](https://www.slimframework.com/docs/v4/start/web-servers.html)'s instruction.

### What you still have to do
#### Set up your cache and database
This project does not provide any cache, database or ORM configurations, you should choose and configure them by yourselves.

#### Cookies
I have not used cookies in my project, so I did not integrate a convenient cookie library into this project. You have to install a cookie library if you want to use cookie, or just to use `Psr-7`'s verbose cookie setup operation.

### Folder structure

    |- app
        |- Actions
        |- Configurations
        |- Middlewares
        |- ApplicationFactory.php
        |- Bootstrap.php
        |- ConsoleFactory.php
    |- public
    |- src
        |- Sofi
    |- tests
        |- App
        |- Sofi

| Folder | Description                         |
| ------ | ----------------------------------- |
| app    | The root folder of your application |
| public | The web root of your site           |
| src    |                                     |
| - Sofi | The root folder for the project     |
| tests  | The unittest files for the project  |


## Conceptions
### Bootstrap
A class to set up your container, in [`Bootstrap`](src/Sofi/Application/Bootstrap.php) you configure your dependency, determine the environment you are in. Bootstrap is decoupled with your application, you should only set up configurations related to your application in [`ApplicationFactory`](src/Sofi/Application/ApplicationFactory.php) or [`ConsoleFactory`](src/Sofi/Application/ConsoleFactory.php).

### Request lifecycle
It is the core conception of http request handling, to design it I was influenced by many other frameworks. All components related to request handling should inherit from `Lifecycle` class, including `Middlewares`, `Controllers` and `Actions`.

Here are all stages of lifecycle, you can incept any of them in your handler:

* initializeRequest
* prepareRequest
* preRequest
* postRequest
* preResponse
* handleException
* finalize

For the detailed definition of each stage, please see comment in [`Lifecycle`](src/Sofi/HTTP/Lifecycle.php).

### Exceptions
The exception module may be separated into another independent project in the future.


## Dependencies
This project relies on several dependencies, some are necessary, some are not. For those unnecessary dependencies, you can just ignore them (because your code will never trigger nor load them), or remove them from your project.

### DI (Dependency Injection) - [PHP-DI]((http://php-di.org/doc/frameworks/slim.html))
This project uses slim official document recommended `PHP-DI` as the project's dependency container. If you want to use another container, you have to modify the `composer` requirements, and write your own `Bootstrap`.

### Console - [symfony/console](https://symfony.com/doc/current/components/console.html)
This project integrated `symfony/console`. If you have no script executing demand, you can remove it or just ignore it.

### i18n
* [boronczyk/localization-middleware](https://github.com/tboronczyk/localization-middleware)

    Get locale preference from an incoming request, by default, you retrieve the preference by calling `$request->getAttribute('locale')`.

* [symfony/translation](https://symfony.com/doc/current/translation.html)

* [gettext/php-scanner](https://github.com/php-gettext/PHP-Scanner)

### Miscellaneous
* [akrabat/ip-address-middleware](https://github.com/akrabat/ip-address-middleware)
    
    A middleware to get ip address of user. Remove the middleware from `index.php` if you do not need.


## Extensions
### Doctrine
I used [Doctrine ORM](https://www.doctrine-project.org/projects/doctrine-orm/en/2.14/index.html) to develop my project, so I also accumulated some useful tools for Doctrine. If you do not want to use doctrine as your ORM, just remove it from requirement list.

#### Pagination
Because doctrine always only use join statement (not a subquery with in statement) to query relations, when it comes to paginating a OneToMany entity and you want to eager load it, the records number of result dataset would be wrong.

My approach is using two separated queries. The first query to filter and limit records without getting any fields except for the primary key of the from entity. The second query to eager load all records you need by using join statement without using limit statement.

Here is an example:

    $qb = $this->em->createQueryBuilder()
            ->from(Order::class, 'o');

    $result = $this->paginationService->paginate(
        $qb,
        orderBys: [
           new Expr\OrderBy('o.createdAt', 'DESC')
        ],
        joins: [
            'o.items' => 'i'
        ],
        fetchModes: [
            [OrderItem::class, 'i', ClassMetadata::FETCH_EAGER]
        ]
    );


## About
Slim is quite simple, easy to use, middleware and dependency injection help me solve almost all problem we may meet when developing a web application and keep the project tidy. But sometimes the minimalism thinking make us have a lot work to do, we have to find and install a lot of dependencies to make our project works. This scaffold include many functions that we may require from exception handling to i18n, you just need to clone or fork it and start coding.

### What does [Shisa](https://en.wikipedia.org/wiki/Shisa) means?
I visited Okinawa in 2019, I like there very much, I even want to live there after I retire. Shisa is a local lion mascot which locals think can bring them fortune, wealth and peace. You can see it at almost every house there, it looks very lovely.
