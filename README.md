predaddy-symfony-validator
==========================
[![Latest Stable Version](https://poser.pugx.org/predaddy/predaddy-symfony-validator/v/stable.png)](https://packagist.org/packages/predaddy/predaddy-symfony-validator)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/szjani/predaddy-symfony-validator/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/szjani/predaddy-symfony-validator/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/6e9918be-77e2-49eb-b9c2-3a26135fcc5c/mini.png)](https://insight.sensiolabs.com/projects/6e9918be-77e2-49eb-b9c2-3a26135fcc5c)
[![Gitter chat](https://badges.gitter.im/szjani/predaddy-symfony-validator.png)](https://gitter.im/szjani/predaddy-symfony-validator)

|master|
|------|
|[![Build Status](https://travis-ci.org/szjani/predaddy-symfony-validator.png?branch=master)](https://travis-ci.org/szjani/predaddy-symfony-validator)|
|[![Coverage Status](https://coveralls.io/repos/szjani/predaddy-symfony-validator/badge.png?branch=master)](https://coveralls.io/r/szjani/predaddy-symfony-validator?branch=master)|

The `ValidationInterceptor` provided by this library helps validating messages posted to predaddy `MessageBus`.
It is based on [Symfony Validator](https://github.com/symfony/Validator) component.

Usage
-----

```php
$bus = SimpleMessageBus::builder()
    ->withInterceptors([new ValidatorInterceptor()])
    ->build();
```

```php
class CreateUser
{
    /**
     * @Assert\Length(min = 3)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @Assert\Email
     * @Assert\NotBlank
     */
    private $email;

    public function __construct($name, $email)
    {
        $this->name = $name;
        $this->email = $email;
    }

    /**
     * @Assert\True(message = "The user should have a Google Mail account")
     */
    public function isGmailUser()
    {
        return false !== strpos($this->email, '@gmail.com');
    }

    public function __toString()
    {
        return Objects::toStringHelper($this)
            ->add('name', $this->name)
            ->add('email', $this->email)
            ->toString();
    }
}
```

```php
try {
    $bus->post(new CreateUser('John Doe', 'john@example.com'));
} catch (ValidationException $e) {
    $e->getViolationList();
}

```

If you use annotation constraints, do not forget to register its namespace:

```php
AnnotationRegistry::registerAutoloadNamespace('Symfony\Component\Validator\Constraint', 'path/to/symfony/library/validator');
```
