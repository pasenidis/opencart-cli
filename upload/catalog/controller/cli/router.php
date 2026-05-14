<?php

/**
 * CLI Router
 * @author Dionysis Pasenidis
 * @link https://github.com/pasenidis
 * @version 1.0
 *
 * @property Cli $cli
 */
class ControllerCliRouter extends Controller
{
    public function index()
    {
        return $this->cli->router();
    }
}
