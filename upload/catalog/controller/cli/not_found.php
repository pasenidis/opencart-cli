<?php

/**
 * CLI
 * @author Dionysis Pasenidis
 * @link https://github.com/pasenidis
 * @version 1.0
 *
 * @property Cli $cli
 */
class ControllerCliNotFound extends Controller
{
    public function index(): void
    {
        $this->cli->error('Route not found.', true);
    }
}
