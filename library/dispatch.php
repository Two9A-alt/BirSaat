<?php
/**
 * BirSaat Framework: Dispatch and routing
 *
 * Multiple schemes of URL are supported:
 *   Homepage: http://hostname/
 *   Action in the index controller: http://hostname/action
 *   Action: http://hostname/controller/action
 *   Action with params: http://hostname/[controller/]action(/param/value)+
 *
 * @author Imran Nazar <tf@imrannazar.com>
 */

class bsDispatch
{
    private $controller;
    private $action;
    private $params = array();

    /**
     * Set the path to use for routing
     * @param path string The path to use for routing
     * @throws bsException if a path is not passed, or is malformed
     */
    public function set_path($path)
    {
        if (!isset($path)) {
            throw new bsException('Path not set; framework may be misconfigured');
        }

        $parts = parse_url($path);
        $path_chunks = explode('/', $parts['path']);

        if (count($path_chunks) == 1 && $path_chunks[0] == '') {
            // Dispatching for the homepage action
            $this->controller = 'index';
            $this->action = 'index';
        } else {
            if (count($path_chunks) == 1) {
                // Dispatching for /action
                $this->controller = 'index';
            } else {
                // Dispatching for /controller/action[(/key/val)*]
                $this->controller = strtolower(array_shift($path_chunks));
            }

            $this->action = strtolower(array_shift($path_chunks));

            // Any remaining chunks in the path must be key/val pairs;
            // if an odd number of chunks remains, URL is malformed
            if (count($path_chunks)) {
                if (count($path_chunks) & 1) {
                    throw new bsException('Malformed URL during dispatch');
                }

                do {
                    $key = strtolower(array_shift($path_chunks));
                    $val = strtolower(array_shift($path_chunks));
                    $this->params[$key] = $val;
                } while (count($path_chunks));
            }
        }
    }

    /**
     * Get the controller name
     * @return string Name of the controller currently set for dispatch
     */
    public function get_controller()
    {
        return $this->controller;
    }

    /**
     * Get the action name
     * @return string Name of the action currently set for dispatch
     */
    public function get_action()
    {
        return $this->action;
    }

    /**
     * Get the parameters passed in the path
     * @return array Map of parameters parsed from the path
     */
    public function get_params()
    {
        return $this->params;
    }

    /**
     * Route the requested URL to the controller
     * @return bsView The view built by the controller, ready for rendering
     * @throws bsException if dispatch failed, or the action was not found
     */
    public function route()
    {
        if (!$this->controller || !$this->action) {
            throw new bsException('Controller or action could not be dispatched');
        }

        try {
            $cname = ucfirst($this->controller) . 'Controller';
            $controller = new $cname;
        }
        catch (Exception $e) {
            throw new bsException('Controller not found: '.$this->controller, 404);
        }

        $view = $controller->get_view();

        if (strpos($this->action, '.') !== false) {
            // A formatted extension was requested
            $aparts = explode('.', $this->action);
            if (count($aparts) > 2) {
                // Too many dots! We've no idea what this means
                throw new bsException('Unsupported action: '.$this->action);
            }

            $aname = $aparts[0];
            $view->set_formatter($aparts[1]);
        } else {
            $aname = $this->action;
        }

        $amethod = $aname . 'Action';

        if (!$controller || !method_exists($controller, $amethod)) {
            throw new bsException('Action not found: '.$this->action, 404);
        }

        $viewname = $controller->$amethod($this->params);
        if (!$viewname) {
            $viewname = $aname;
        }
        $view->set_file($viewname);

        return $view;
    }
}

