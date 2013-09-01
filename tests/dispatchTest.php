<?php
/**
 * Pagination example: Tests: Framework dispatch
 *
 * Tests parsing of the path passed to dispatch:
 *   Null path (simulating a misconfigured site)
 *   Blank path (homepage)
 *   Action path without a controller
 *   Controller/Action
 *   Controller/Action with parameters
 *   Controller/Action with incomplete parameters
 *
 * @author Imran Nazar <tf@imrannazar.com>
 */

class dispatchTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        include '../library/autoload.php';
        $this->dispatcher = bsFactory::get('dispatch');
    }

    /**
     * @expectedException Exception
     * @expectedExceptionCode 500
     */
    public function testNullPath()
    {
        $this->dispatcher->set_path(null);
    }

    public function testIndex()
    {
        $this->dispatcher->set_path('');
        $this->assertEquals($this->dispatcher->get_controller(), 'index');
        $this->assertEquals($this->dispatcher->get_action(), 'index');
    }

    public function testIndexAction()
    {
        $this->dispatcher->set_path('test1');
        $this->assertEquals($this->dispatcher->get_controller(), 'index');
        $this->assertEquals($this->dispatcher->get_action(), 'test1');
    }

    public function testFullAction()
    {
        $this->dispatcher->set_path('test1/test2');
        $this->assertEquals($this->dispatcher->get_controller(), 'test1');
        $this->assertEquals($this->dispatcher->get_action(), 'test2');
    }

    public function testParams()
    {
        $this->dispatcher->set_path('test1/test2/test3/test4');
        $this->assertEquals($this->dispatcher->get_controller(), 'test1');
        $this->assertEquals($this->dispatcher->get_action(), 'test2');

        $params = $this->dispatcher->get_params();
        $this->assertArrayHasKey('test3', $params);
        $this->assertEquals($params['test3'], 'test4');
    }

    /**
     * @expectedException Exception
     * @expectedExceptionCode 500
     */
    public function testBadParams()
    {
        $this->dispatcher->set_path('test1/test2/test3/test4/test5');
    }
}
