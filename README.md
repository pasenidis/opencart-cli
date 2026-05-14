# OpenCart CLI

Run OpenCart 3.x controllers from the command line without a web server.

## Installation

Copy the contents of `upload/` into your OpenCart root directory.

## Usage

```bash
php cli.php <route> [options] [params]
```

All routes run in the **catalog** context, using the root `config.php`.

## How it works

1. `cli.php` bootstraps the OpenCart framework with a minimal CLI-specific config (`system/config/cli.php`)
2. The CLI config disables sessions, cookies, and URL handling since they are irrelevant in a terminal
3. The database connection is established as normal, so you have full access to models and data
4. The `Cli` library parses the command line arguments into a route, options, and positional parameters
5. During startup, all `--key=value` options are copied into `$this->request->get`, so any existing controller that reads from `$this->request->get` will work without modification
6. The router dispatches to your controller just like a normal HTTP request would

## Argument formats

The first argument is always the controller route. Everything after is parsed as options or positional parameters.

```bash
# Simple route
php cli.php some/route

# --key=value (equals sign)
php cli.php some/route --id=42

# --key value (space separated)
php cli.php some/route --id 42

# Boolean flags (set to true)
php cli.php some/route --verbose

# Positional parameters (no -- prefix)
php cli.php some/route param1 param2

# Mixed
php cli.php some/route --id=42 --verbose param1
```

## Accessing arguments in controllers

The `Cli` library is available via `$this->cli` in any controller.

### CLI-specific API

```php
class ControllerSomeRoute extends Controller {
    public function index() {
        // All options as an array
        $options = $this->cli->getOptions();

        // Single option with optional default
        $id = $this->cli->getOption('id');
        $verbose = $this->cli->getOption('verbose', false);

        // Positional parameters (arguments without --)
        $params = $this->cli->getParams();

        // Output to stdout
        $this->cli->output('Done.');

        // Output to stderr
        $this->cli->error('Something went wrong.');

        // Output to stderr and exit with code 1
        $this->cli->error('Fatal error.', true);
    }
}
```

### Request GET compatibility

All `--key=value` options are automatically available in `$this->request->get`. This means existing controllers that read from the request work without changes:

```bash
php cli.php some/route --token=abc123 --language_id=1
```

```php
// Both of these will return the same value:
$this->cli->getOption('token');   // 'abc123'
$this->request->get['token'];    // 'abc123'
```

## Cron examples

```
*/5 * * * * php /path/to/cli.php cron/currency_update
0 3 * * *   php /path/to/cli.php cron/cleanup --days=30
0 0 * * 0   php /path/to/cli.php extension/feed/google_sitemap
```

## What is disabled in CLI mode

- **Sessions** — no cookies in a terminal
- **URL handling** — no HTTP_SERVER needed

The database connection, cache, language, and model loading all work as normal.

## File structure

```
upload/
├── cli.php                              # Entry point
├── catalog/controller/cli/
│   ├── startup.php                      # Registers the Cli library, populates request GET
│   ├── router.php                       # Dispatches to the target route
│   └── not_found.php                    # Handles invalid routes
└── system/
    ├── config/cli.php                   # CLI config (DB enabled, sessions/URL disabled)
    └── library/cli.php                  # Cli class (argument parsing, routing, output)
```

## License

MIT
