# Template engine

```php
$renderable = new Renderable(
    template: 'My first {{variable}} content',
    variables: [
        'variable' => 'rendered',
    ]
);

$expected = 'My first rendered content';
```