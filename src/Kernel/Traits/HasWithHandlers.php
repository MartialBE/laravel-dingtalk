<?php
namespace Martialbe\LaravelDingtalk\Kernel\Traits;

use Closure;
use Martialbe\LaravelDingtalk\Kernel\Support\Handler;
use Martialbe\LaravelDingtalk\Kernel\Exceptions\InvalidArgumentException;

trait HasWithHandlers
{
    protected $handlers = [];

    /**
     * @return array
     */
    public function getHandlers(): array
    {
        return $this->handlers;
    }

    /**
     * @param mixed $handler
     * @return self
     */
    public function with( $handler ) : self
    {
        return $this->withHandler($handler);
    }

    /**
     * @param mixed $handler
     * @return self
     */
    public function withHandler($handler): self
    {
        $this->handlers[] = $this->createHandlerItem($handler);

        return $this;
    }

    /**
     * @param mixed $handler
     * @return array
     */
    public function createHandlerItem($handler): array
    {
        return [
            'hash' => $this->getHandlerHash($handler),
            'handler' => $this->makeClosure($handler),
        ];
    }

    /**
     * @param mixed $handler
     * @return string
     */
    protected function getHandlerHash($handler): string
    {
        switch (true) {
            case is_string($handler):
                return $handler;
                break;
            case is_array($handler):
                return is_string($handler[0]) ? $handler[0].'::'.$handler[1] : get_class($handler[0]).$handler[1];
                break;
            case $handler instanceof Closure:
                return spl_object_hash($handler);
                break;
            case $handler instanceof Handler:
                return get_class($handler);
                break;
            default:
                throw new InvalidArgumentException('Invalid handler: '.gettype($handler));
                break;
        }
    }

    /**
     * @param mixed $handler
     * @return callable
     */
    protected function makeClosure($handler): callable
    {
        if (is_callable($handler)) {
            return $handler;
        }

        if( ($handler instanceof Handler) && method_exists($handler, '__invoke') ) {
            return fn () => $handler(...func_get_args());
        }

        if (class_exists($handler) && method_exists($handler, '__invoke')) {
            return fn () => (new $handler())(...func_get_args());
        }

        throw new InvalidArgumentException(sprintf('Invalid handler: %s.', $handler));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function prepend($handler): self
    {
        return $this->prependHandler($handler);
    }


    /**
     *
     * @throws InvalidArgumentException
     */
    public function prependHandler($handler): self
    {
        array_unshift($this->handlers, $this->createHandlerItem($handler));

        return $this;
    }

    /**
     *
     * @throws InvalidArgumentException
     */
    public function without($handler): self
    {
        return $this->withoutHandler($handler);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withoutHandler($handler): self
    {
        $index = $this->indexOf($handler);

        if ($index > -1) {
            unset($this->handlers[$index]);
        }

        return $this;
    }

    /**
     *
     * @throws InvalidArgumentException
     */
    public function indexOf($handler): int
    {
        foreach ($this->handlers as $index => $item) {
            if ($item['hash'] === $this->getHandlerHash($handler)) {
                return $index;
            }
        }

        return -1;
    }

    /**
     *
     * @throws InvalidArgumentException
     */
    public function when( $value, $handler): self
    {
        if (is_callable($value)) {
            $value = call_user_func($value, $this);
        }

        if ($value) {
            return $this->withHandler($handler);
        }

        return $this;
    }

    public function handle( $result,  $payload = null)
    {
        $next = $result = is_callable($result) ? $result : fn ( $p) => $result;
        foreach (array_reverse($this->handlers) as $item) {
            $next = fn ($p) => $item['handler']($p, $next) ?? $result($p);
        }

        return $next($payload);
    }

    /**
     *
     * @throws InvalidArgumentException
     */
    public function has($handler): bool
    {
        return $this->indexOf($handler) > -1;
    }

}
