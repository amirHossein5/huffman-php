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

It is not production ready!
