<?php

/**
 * CLI Library
 * @author Dionysis Pasenidis
 * @link https://github.com/pasenidis
 * @version 1.0
 *
 * @property Registry $registry
 */
class Cli
{
    private $registry;
    private $route = '';
    private $options = [];
    private $params = [];

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
        $this->parseArguments();
    }

    private function parseArguments(): void
    {
        if (isset($this->registry->get('request')->server['argv'])) {
            $argv = (array)$this->registry->get('request')->server['argv'];
        } else {
            $argv = [];
        }

        // Skip script name
        array_shift($argv); // cli.php

        // Route
        $this->route = !empty($argv) ? str_replace('../', '', array_shift($argv)) : '';

        // Parse remaining arguments
        while ($argv) {
            $arg = array_shift($argv);

            if (strpos($arg, '--') === 0) {
                $key = substr($arg, 2);

                // --key=value format
                if (strpos($key, '=') !== false) {
                    [$key, $value] = explode('=', $key, 2);
                    $this->options[$key] = $value;
                } elseif (!empty($argv) && strpos($argv[0], '--') !== 0) {
                    // --key value format
                    $this->options[$key] = array_shift($argv);
                } else {
                    // --flag format
                    $this->options[$key] = true;
                }
            } else {
                $this->params[] = $arg;
            }
        }
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param mixed $default
     * @return mixed
     */
    public function getOption(string $key, $default = null)
    {
        return $this->options[$key] ?? $default;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @return Action|null
     */
    public function router()
    {
        $route = $this->route;

        if (empty($route) || $route === 'cli/router') {
            $route = $this->registry->get('config')->get('action_default');
        }

        $result = $this->registry->get('event')->trigger('controller/' . $route . '/before', [&$route, &$this->params]);

        if (!is_null($result)) {
            return $result;
        }

        $action = new Action($route);
        $output = $action->execute($this->registry, $this->params);

        $result = $this->registry->get('event')->trigger('controller/' . $route . '/after', [&$route, &$output]);

        if (!is_null($result)) {
            return $result;
        }

        return $output;
    }

    public function output(string $message): void
    {
        fwrite(STDOUT, $message . PHP_EOL);
    }

    public function error(string $message, bool $exit = false): void
    {
        fwrite(STDERR, $message . PHP_EOL);

        if ($exit) {
            exit(1);
        }
    }
}
