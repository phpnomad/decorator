<?php
namespace PHPNomad\Decorator;

trait Decorator
{
    /**
     * @var mixed
     */
    protected $decoratedInstance;
    protected array $allowedMethods = [];

    /**
     * __call is triggered when invoking inaccessible methods in an object context.
     * This simply forwards any call to a method not found in the current class to the decoratedInstance.
     *
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $method, array $arguments)
    {
        if (in_array($method, $this->allowedMethods) && method_exists($this->decoratedInstance, $method)) {
            return call_user_func_array([$this->decoratedInstance, $method], $arguments);
        }
        trigger_error('Call to undefined method ' . __CLASS__ . '::' . $method, E_USER_ERROR);
    }
}