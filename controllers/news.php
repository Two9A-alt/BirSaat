<?php
/**
 * Pagination example: News feed handling
 *
 * @author Imran Nazar <tf@imrannazar.com>
 */

class NewsController extends bsControllerBase
{
    private $feed;

    public function indexAction()
    {
        header('Location: /news/ticker');
    }

    public function tickerAction($params)
    {
        if (!isset($params['page'])) {
            $params['page'] = 1;
        }
        $params['page'] = (int)$params['page'];

        $this->feed = new NewsFeedModel();
        $this->view->entry = $this->feed[$params['page'] - 1];

        if (!count($this->view->entry)) {
            throw new bsException("Couldn't find entry {$params['page']} in the news feed", 404);
        }

        $this->view->paginator = new PaginatorModel(
            '/news/ticker',
            $this->feed->count(),
            $params['page']
        );
        $this->view->add_asset('css', 'ticker.css');
    }
}

