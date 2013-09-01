<?php
/**
 * Pagination example: Index controller
 * This only exists to redirect one to the news controller.
 *
 * @author Imran Nazar <tf@imrannazar.com>
 */

class indexController extends bsControllerBase
{
    public function indexAction()
    {
        header('Location: /news/ticker');
    }
}

