# Enum implementation for php < 8.1

### Class designed to behave as close to native php enum as possible. 

```php
use Enderive\Enum;

/**
 * Pure enum
 * 
 * @method static UserStatus ADMIN() 
 * @method static UserStatus MEMBER()
 * @method static UserStatus GUEST()
*/
class UserStatus extends Enum {}

/**
 * Backed enum
*/
class UserStatus extends Enum
{
    private const ADMIN = 1;
    private const MEMBER = 2;
    private const GUEST = 3;
}

UserStatus::ADMIN();
UserStatus::from(1);
UserStatus::tryFrom(1);
UserStatus::cases();

// Enums are singletons
UserStatus::ADMIN() === UserStatus::ADMIN() // => true

$status = UserStatus::from(1);
$status->name // "ADMIN"
$status->value // 1

```