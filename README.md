# Stapler

**Note**: *If you've previously been using this package, then you've been using it with Laravel. This package is no
longer directly coupled to the Laravel framework. As of 1.0.0, Stapler is now framework agnostic. In order to take
advantage of the Laravel specific features provided by the previous Beta releases (service providers, IOC container,
commands, migration generator, etc) , I've created a separate package specifically for the purpose of using Stapler
within Laravel:  [Laravel-Stapler](https://github.com/CodeSleeve/laravel-stapler). If you're using Stapler inside a
Laravel application I strongly recommend you use this package (it will save you a bit of boilerplate).*

Stapler is a php-based framework agnostic file upload package inspired by
the [Ruby Paperclip](https://github.com/thoughtbot/paperclip) gem. It can be used to add file file uploads (as
attachment objects) to your ORM records. While not an exact duplicate, if you've used Paperclip before then you should
feel quite comfortable using this package.

Stapler was created by [Travis Bennett](https://twitter.com/tandrewbennett).

## About Stapler

Stapler works by attaching file uploads to database table records. This is done by defining attachments inside the
table's corresponding model and then assigning uploaded files (from your forms) as properties (named after the
attachments) on the model before saving it. Stapler will listen to the life cycle callbacks of the model (after save,
before delete, and after delete) and handle the file accordingly. In essence, this allows uploaded files to be treated
just like any other property on the model; stapler will abstract away all of the file processing, storage, etc so you
can focus on the rest of your project without having to worry about where your files are at or how to retrieve them.

## Documentation

* [Setup](docs/setup.md)
* [Configuration](docs/configuration.md)
* [Interpolations](docs/interpolations.md)
* [Image Processing](docs/imageprocessing.md)
* [Working with Attachments](docs/attachments.md)
* [Examples](docs/examples.md)
* [Troubleshooting](docs/troubleshooting.md)
* [Contributing](docs/contributing.md)
