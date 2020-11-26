# dto-parser

Parse simple array data to well defined PHP objects. Very usefull to strict type data before json encoding.

Just extend your DTO object with BaseDTO

Example

```php
class SuccessDTO extends BaseDTO {
    public $message;
    public $id;

    /**
     * SuccessDTO constructor.
     *
     * @param $message
     * @param $id
     */
    public function __construct(string $message, $id = null)
    {
        $this->message = $message;
        $this->id = $id;
    }
}

```

Now, in the controller

```php
$successObject = SuccessDTO::toDTO(['message' => 'success', 'id' => 23]); //Get instance of SuccessDTO class
```

Alternativelly you can use toDTOArray to parse multiple rows
```php
$successes = SuccessDTO::toDTOArray([['message' => 'success', 'id' => 23],['message' => 'error', 'id' => null]]);

```
