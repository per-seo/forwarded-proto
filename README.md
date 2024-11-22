A simple middleware for Slim4 framework to configure proper uri schema. Usage is very simple, just enable this Middleware (in the Middleware part of your Slim4 Project) with:

```php
<?php

use PerSeo\Middleware\ForwardedProto\ForwardedProto;

$app->add(ForwardedProto::class);
```

Simple, isn't it?
