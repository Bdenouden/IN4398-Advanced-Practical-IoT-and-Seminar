<?php

class Page extends Config
{

    private $controller;

    public function __construct()
    {

        $this->controller = new Controller();

        try {

            Config::__construct();

            $_SESSION['user']['object'] = new User();

            $this->build();

        } catch (exception $e) {

            if ($this->_c('debug')) {

                echo 'An error occurred: ' . $e->getMessage();

            } else {

                $this->loadController('e404');

            }

        }

    }

    private function build()
    {

        try {

            $page_uri_data = $this->parseURI();
            $page_uri = $page_uri_data[0];

            $this->page_model = $this->loadModel('page');

            $page_data = $this->page_model->searchPage('/' . $page_uri);

            ob_start();

            if (count($page_data) == 0) {

                $this->loadView('template/header');
                $this->loadController('e404');
                $this->loadView('template/footer');
            }
            else {

                $page_data = $page_data[0];

                if (!$this->isInitialSetupCompleted()) { // Initial setup not completed, redirect to /setup

                    $this->loadView('template/header');
                    $this->loadController('setup');
                    $this->loadView('template/footer');

                } elseif ($this->checkLoginRequirement($page_data, $page_uri)) {

                    switch ($page_data['page_type']) {

                        case 'default':
                            if (!$this->redirectAJAX($page_data)) {

                                $this->loadView('template/header');
                                $this->loadController($page_data['page_name']);
                                $this->loadView('template/footer');
                            }
                            break;

                        case 'api':
                            if (!$this->redirectAJAX($page_data)) {

                                $this->loadController($page_data['page_name']);
                            }
                            break;

                    }
                }
            }

        } catch (exception $e) {

            throw new SystemException($e->getMessage());

        }

    }

    private function redirectAJAX($page_data)
    {

        $_POST = array_merge($_POST, $_GET);

        //check for POST ajax request, load the controller and execute the function specified in ACTION
        if ((isset($_POST['AJAX']) && $_POST['AJAX'] == 1)) {

            $controller = $this->loadController($page_data['page_name'], array(), 'request');

            $form = new Form();

            $allowed_actions = get_class_methods($controller);

            $action = $_POST['ACTION'];

            if (in_array($action, $allowed_actions)) {

                $controller->$action();

                return true;

            } else {

                throw new SystemException('Invalid action call');

            }

        }

        return false;
    }

    private function parseURI()
    {

        $uri_complete = $_SERVER['REQUEST_URI'];

        if (strstr($uri_complete, '..')) {

            throw new exception('security error');

        }

        $split = explode('?', $uri_complete);
        $uri = $split[0];

        $uri_parts = explode('/', $uri);
        unset($uri_parts[0]);

        if (count($uri_parts) == 0) {

            $uri_parts[] = $uri;

        }

        if (strstr(end($uri_parts), ':')) {

            $total_get = end($uri_parts);

            $get_parameters = explode(':', end($uri_parts));

            $_GET = array_merge($_GET, $get_parameters);

            if (count($get_parameters) != 1) {

                foreach ($get_parameters as $key => $get_parameter) {

                    if ($key % 2 == 1) {

                        $_GET[$get_parameters[$key - 1]] = $get_parameter;

                    }

                }

            }

            unset($uri_parts[count($uri_parts)]);

            $uri = implode('/', $uri_parts);
            $_GET = array_merge($_GET, $get_parameters);

        } else {

            $uri = implode('/', $uri_parts);

            $total_get = "";
        }


        return array($uri, $total_get);


    }

    public static function isInitialSetupCompleted()
    {
        return file_exists("setup.lock");
    }


    /** Checks for member type and if member is logged in. Redirects to login or 403 depending on login status
     * @return bool
     */
    public function checkLoginRequirement($page_data, $page_uri)
    {

        if ($page_data['page_login_required'] == 1) {

            if (User::session_exists()) {
                if (User::userMinimalAccessLevel($page_data['page_user_member_type'])) {
                    return true;
                } else {
                    $this->loadView('template/header');
                    $this->loadController('e403', array(1));
                    $this->loadView('template/footer');
                    return false;
                }
            } else {
                Auth::redirect('/login/next:' . str_replace('/', '-', $page_uri));
                return false;
            }

        } else {
            return true;
        }

    }

    public function __call($method, $args)
    {
        return call_user_func_array(array($this->controller, $method), $args);
    }


}

