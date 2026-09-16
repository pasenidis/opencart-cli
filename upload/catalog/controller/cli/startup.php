<?php

/**
 * CLI Startup
 * @author Dionysis Pasenidis
 * @link https://github.com/pasenidis
 * @version 1.0
 *
 * @property Registry $registry
 * @property Request $request
 */
class ControllerCliStartup extends Controller
{
    public function index(): void
    {
        $cli = new Cli($this->registry);

        // Populate request GET with CLI options so existing controllers work
        foreach ($cli->getOptions() as $key => $value) {
            $this->request->get[$key] = $value;
        }

        $this->request->get['route'] = $cli->getRoute();

        $this->registry->set('cli', $cli);
    }
}
