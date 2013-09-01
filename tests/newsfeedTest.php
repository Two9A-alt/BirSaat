<?php
/**
 * Pagination example: Tests: News feed
 *
 * Tests parsing of a sample news feed, and verifies that
 * each element is what it should be.
 *
 * @author Imran Nazar <tf@imrannazar.com>
 */

class newsfeedTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        include '../library/autoload.php';
        $this->feed = new NewsFeedModel('feed.json');
    }

    public function testCount()
    {
        $this->assertEquals($this->feed->count(), 7);
    }

    public function testURLs()
    {
        $urls = array(
            '/news/world-asia-pacific-11808378',
            '/news/uk-england-11806723',
            '/news/health-11800017',
            '/news/world-asia-pacific-11808242',
            '/news/uk-11799713',
            '/news/uk-11807382',
            '/iplayer/episode/b00w8j06/Tennis_World_Tour_Finals_2010_Day_1/'
        );

        foreach ($urls as $key => $val) {
            $this->assertEquals($this->feed[$key]['url'], $val);
        }
    }
}

