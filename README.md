# File uploader

Upload a large number of files that will be harmoniously distributed
in folders, which will allow access even through the terminal

###

Imagine you have a store with goods, each product has a slider
with photos. While there are not many products, you can easily open 
and view them all, either through a file manager or console. 

Now
imagine, in one folder there are more than 8 thousand (or more) photos
of products of users, etc., and you open this folder, no one will have 
a hard drive and / or little RAM.

# Installation

> The only thing you need to know is that it's just an add-on to the regular one 
Laravel `Storage` Facade, but when you save a file, it gets a multi-level 
nesting of folders in the path

Require this package with composer using the following command:


```bash
composer require hexide-digital/file-uploader
```
Add the following class to the `providers` array in `config/app.php`:
```php
HexideDigital\FileUploader\FileUploaderServiceProvider::class,
```

Add the following alias to the `aliases` array in `config/app.php`:
```php
'FileUploader' => HexideDigital\FileUploader\Facades\FileUploader::class,
```

# Usage

... is simple, use as a `Storage` 

Most used method is `put`
His arguments

 - `$file` - the file itself that you want to save;
 - `$type` - nested folder, where store file (for example `images`, `files`, `pdf`);
 - `$module` - can be empty, but it is recommended to specify for convenient search;
 - `$uniq_id` - if you want to save the `files` in one place, for example, `images` 
for one directory for the product, that you can say the product id and `image` 
will be saved near other product images or files,


