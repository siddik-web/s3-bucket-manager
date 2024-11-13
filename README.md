# S3 Bucket Manager

## Description

The **S3 Bucket Manager** is a Laravel package designed to simplify the management of AWS S3 bucket operations. It provides a straightforward interface for performing various file operations such as listing, retrieving, creating, updating, and deleting files in an S3 bucket.

## Features

- List files in an S3 bucket
- Upload files to an S3 bucket
- Download files from an S3 bucket
- Delete files from an S3 bucket
- Error handling with logging for failed operations

## Requirements

- PHP version: ^7.3|^8.0
- Laravel framework

## Installation

You can install the package via Composer. Run the following command in your terminal:

```bash
composer require siddik-web/s3-bucket-manager
```

## Configuration

After installing the package, you need to publish the configuration file. Run the following command:

```bash
php artisan vendor:publish --provider="RocksCoder\S3BucketManager\S3BucketManagerServiceProvider"
```

This will create a `s3-bucket-manager.php` file in your `config` directory. You can customize the configuration options in this file to suit your needs.

## Usage

To use the S3 Bucket Manager in your Laravel application, you can inject it into your controllers or services using the `app()` function:

```php
$s3BucketManager = app('s3-bucket-manager');
```

## Testing

To run the tests for the package, use the following command:

```bash
composer test
```

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Author

Md Siddiqur Rahman
- Email: siddikcoder@gmail.com
- GitHub: [@siddik-web](https://github.com/siddik-web)

## Links

- [GitHub Repository](https://github.com/siddik-web/s3-bucket-manager)
- [Packagist](https://packagist.org/packages/siddik-web/s3-bucket-manager)

## Issues

If you encounter any issues or have suggestions for improvements, please open an issue on the [GitHub repository](https://github.com/siddik-web/s3-bucket-manager/issues).

## Contributing

We welcome contributions to improve the package. Please see the [CONTRIBUTING.md](CONTRIBUTING.md) file for guidelines on how to submit improvements and bug fixes.
