<?php
/**
 * Pagination example: Tests: Paginator
 *
 * Tests that the format of the paginator's rendered output is
 * correct in the following scenarios:
 *   No pages
 *   Page 15 of 5: Exception
 *   Page 1: Previous should be disabled
 *   Page N: Next should be disabled
 *   Page 1 of 7: There should be no truncation
 *   Page 6 of 11: There should be no truncation
 *   Page 2 of 25: 7-23 should be missing
 *   Page 24 of 25: 3-19 should be missing
 *   Page 11 of 25: 3-9, 13-23 should be missing
 */

class paginatorTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        include '../library/autoload.php';
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage No pages
     */
    public function testNoPages()
    {
        $pag = new PaginatorModel('', 0, 1);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Current page out of range
     */
    public function testOutOfRange()
    {
        $pag = new PaginatorModel('', 5, 15);
    }

    public function testPreviousDisabled()
    {
        $pag = new PaginatorModel('', 5, 1);
        $out = $this->renderPaginator($pag);
        $this->assertEquals(count($out->li), 7);

        // First element is the Previous button
        $attrs = $out->li[0]->attributes();
        $this->assertContains('previous', (string)$attrs['class']);
        $this->assertContains('disabled', (string)$attrs['class']);
    }

    public function testNextDisabled()
    {
        $pag = new PaginatorModel('', 5, 5);
        $out = $this->renderPaginator($pag);
        $this->assertEquals(count($out->li), 7);

        // Last element is the Next button
        $attrs = $out->li[6]->attributes();
        $this->assertContains('next', (string)$attrs['class']);
        $this->assertContains('disabled', (string)$attrs['class']);
    }

    public function testSevenPages()
    {
        $pag = new PaginatorModel('', 7, 1);
        $out = $this->renderPaginator($pag);
        $this->assertEquals(count($out->li), 9);

        foreach($out->li as $li) {
            $this->assertNotEquals((string)$li->a->span, '...');
        }
    }

    public function testElevenPages()
    {
        $pag = new PaginatorModel('', 11, 6);
        $out = $this->renderPaginator($pag);
        $this->assertEquals(count($out->li), 13);

        foreach($out->li as $li) {
            $this->assertNotEquals((string)$li->a->span, '...');
        }
    }

    public function testRightTrunc()
    {
        $pag = new PaginatorModel('', 25, 2);
        $out = $this->renderPaginator($pag);
        $this->assertEquals(count($out->li), 11);

        $attrs = $out->li[0]->attributes();
        $this->assertContains('previous', (string)$attrs['class']);
        $this->assertContains('enabled', (string)$attrs['class']);

        for ($i = 1; $i <= 6; $i++) {
            $attrs = $out->li[$i]->attributes();
            $this->assertEquals((string)$out->li[$i]->a->span, (string)$i);
            if ($i == 2) {
                $this->assertContains('current', (string)$attrs['class']);
            } else {
                $this->assertNotContains('current', (string)$attrs['class']);
            }
        }

        $this->assertEquals((string)$out->li[7]->a->span, '...');

        $this->assertEquals((string)$out->li[8]->a->span, '24');
        $this->assertEquals((string)$out->li[9]->a->span, '25');

        $attrs = $out->li[10]->attributes();
        $this->assertContains('next', (string)$attrs['class']);
        $this->assertContains('enabled', (string)$attrs['class']);
    }

    public function testLeftTrunc()
    {
        $pag = new PaginatorModel('', 25, 24);
        $out = $this->renderPaginator($pag);
        $this->assertEquals(count($out->li), 11);

        $attrs = $out->li[0]->attributes();
        $this->assertContains('previous', (string)$attrs['class']);
        $this->assertContains('enabled', (string)$attrs['class']);

        $this->assertEquals((string)$out->li[1]->a->span, '1');
        $this->assertEquals((string)$out->li[2]->a->span, '2');

        $this->assertEquals((string)$out->li[3]->a->span, '...');

        for ($i = 4, $j = 20; $i <= 9; $i++, $j++) {
            $attrs = $out->li[$i]->attributes();
            $this->assertEquals((string)$out->li[$i]->a->span, (string)$j);
            if ($i == 8) {
                $this->assertContains('current', (string)$attrs['class']);
            } else {
                $this->assertNotContains('current', (string)$attrs['class']);
            }
        }

        $attrs = $out->li[10]->attributes();
        $this->assertContains('next', (string)$attrs['class']);
        $this->assertContains('enabled', (string)$attrs['class']);
    }

    public function testBothTrunc()
    {
        $pag = new PaginatorModel('', 25, 11);
        $out = $this->renderPaginator($pag);
        $this->assertEquals(count($out->li), 11);

        $attrs = $out->li[0]->attributes();
        $this->assertContains('previous', (string)$attrs['class']);
        $this->assertContains('enabled', (string)$attrs['class']);

        $this->assertEquals((string)$out->li[1]->a->span, '1');
        $this->assertEquals((string)$out->li[2]->a->span, '2');
        $this->assertEquals((string)$out->li[3]->a->span, '...');
        $this->assertEquals((string)$out->li[4]->a->span, '10');

        $attrs = $out->li[5]->attributes();
        $this->assertEquals((string)$out->li[5]->a->span, '11');
        $this->assertContains('current', (string)$attrs['class']);

        $this->assertEquals((string)$out->li[6]->a->span, '12');
        $this->assertEquals((string)$out->li[7]->a->span, '...');
        $this->assertEquals((string)$out->li[8]->a->span, '24');
        $this->assertEquals((string)$out->li[9]->a->span, '25');

        $attrs = $out->li[10]->attributes();
        $this->assertContains('next', (string)$attrs['class']);
        $this->assertContains('enabled', (string)$attrs['class']);
    }

    private function renderPaginator($pag)
    {
        return simplexml_load_string('<ul>'.$pag->render().'</ul>');
    }
}

