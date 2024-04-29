# Template engine
## Examples
Simple example:
```php
$renderable = new Renderable(
    template: 'My first {{variable}} content',
    variables: [
        'variable' => 'rendered',
    ]
);

echo $renderer->render($renderable); // 'My first rendered content'
```

Object examples:
```php
$renderable = new Renderable(
    template: 'My name is {{ obj.name }}!',
    variables: [
        'obj' => $obj = new stdClass(),
    ]
);
$obj->name = 'Templator';

echo $renderer->render($renderable); // 'My name is Templator!'
```

```php
$renderable = new Renderable(
    template: 'My name is {{ obj.name.first }} {{ obj.name.last }}!',
    variables: [
        'obj' => new class () {
            public object $name;

            public function __construct()
            {
                $this->name = new class () {
                    public string $first = 'Templator';
                    public string $last = 'Symfony';
                };
            }
        },
    ]
);

echo $renderer->render($renderable); // 'My name is Templator Symfony!'
```

Array example:
```php
$renderable = new Renderable(
    template: 'My name is {{ obj.name.first }} {{ obj.name.last }}!',
    variables: [
        'obj' => [
            'name' => [
                'first' => 'ivan',
                'last' => 'petrov',
            ],
        ],
    ]
);

echo $renderer->render($renderable); // 'My name is ivan petrov!'
```

Filter example:
```php
$renderable = new Renderable(
    template: 'My name is {{ obj.name.first | classify }} {{ obj.name.last | classify }}!',
    variables: [
        'obj' => [
            'name' => [
                'first' => 'ivan',
                'last' => 'petrov',
            ],
        ],
    ]
);

echo $renderer->render($renderable); // 'My name is Ivan Petrov!'
```

Filters example:
```php
$renderable = new Renderable(
    template: '{{a | classify}} {{b | constantize}}, {{c | pluralize | classify}} {{d | pluralize}}',
    variables: [
        'a' => 'hello',
        'b' => 'world',
        'c' => 'summer',
        'd' => 'day',
    ]
);

echo $renderer->render($renderable); // 'Hello WORLD, Summers days'
```