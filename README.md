## Installation

```sh
git clone https://github.com/amirHossein5/huffman-php.git
cd huffman-php
composer install
```

## Compression

```php
php src/compress.php file-path output-file-path
```
Example:
```php
php src/compress.php data.txt build/huffman.binary
```

## UnCompression

```php
php src/decompress.php compressed-file-path output-file-path
```
Example:
```php
php src/decompress.php build/huffman.binary build/output.txt
```

It may not be production ready!
