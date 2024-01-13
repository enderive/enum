# Enum implementation for 

### Class designed to behave as close to native php enum as possible. 

```php
use Enderive\Enum;

/**
 * @method static UserStatus ADMIN() 
 * @method static UserStatus MEMBER()
 * @method static UserStatus GUEST()
*/
class UserStatus extends Enum
{
    // Pure enum
}

// Backed enum
class UserStatus extends Enum
{
    private const ADMIN = 1;
    private const MEMBER = 2;
    private const GUEST = 3;
}
```