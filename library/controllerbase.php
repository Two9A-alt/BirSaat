<?php
/**
 * BirSaat Framework: Base controller
 *
 * The base controller should be inherited from, by classes in controllers/
 *
 * @author Imran Nazar <tf@imrannazar.com>
 */

class bsControllerBase
{
    protected $view;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->view = new bsView();
    }

    /**
     * Retrieve the view used by this controller
     * @return bsView The view object initialised by this controller
     */
    public function get_view()
    {
        return $this->view;
    }
}

