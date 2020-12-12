# PHP File Upload Utils

PHP File Upload Utils is a library that makes it easy to handle uploaded file.   

## Requirements
- PHP >= 7 

## Features
- [x] Handle uploaded file [ Base64 | File ] to temporary folder 
- [x] Auto generate thumbnail with custom quality (0 - 100)
- [x] Move temporary uploaded file and thumbnail to real folder

## Getting started

#### Step 1: Installation
The recommended way to install `PHP File Upload Utils` is through
[Composer](https://getcomposer.org/).

```bash
composer require dicicip/php-otp-auth
```
#### Step 2: Define on your class

```php
<?php

class YourClass
{
    private $fileUtil;
    
    public function __construct()
    {
        $this->fileUtil = new Dicicip\FileUpload\FileUtil('path-to-file-folder', 'relative-path');
    }
}
```

## Documentation

#### Upload file to temporary folder :
```php
public function yourFunction()
{
    $fileInfo = $this->fileUtil->storeBase64ToTemp('[String Base64]', 15);
}
```

#### Move temporary uploaded file and thumbnail to real folder :
```php
public function yourFunction()
{
    $fileInfo = $this->fileUtil->storeTempFileTo('[relative-path]', '[target-relative-directory]');
}
```