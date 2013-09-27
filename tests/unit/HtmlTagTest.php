<?php
use WS\Util\HtmlTag;

/**
 * @author: Smotrov Dmitriy <dsxack@gmail.com>
 */

class HtmlTagTest extends PHPUnit_Framework_TestCase {
    protected $tagName = "div";

    /**
     * @return HtmlTag
     */
    public function testCreate() {
        $htmlTag = HtmlTag::create($this->tagName);
        $this->assertInstanceOf('WS\Util\HtmlTag', $htmlTag);
        return $htmlTag;
    }

    /**
     * @depends testCreate
     * @param $htmlTag HtmlTag
     * @return HtmlTag
     */
    public function testAddClass(HtmlTag $htmlTag) {
        $htmlTag->addClass('testClassName');
        $this->assertArrayHasKey("class", $htmlTag->htmlOptions);
        $this->assertEquals('testClassName', $htmlTag->htmlOptions['class']);

        $htmlTag->addClass('testClassName2');
        $this->assertEquals('testClassName testClassName2', $htmlTag->htmlOptions['class']);

        return $htmlTag;
    }

    /**
     * @depends testAddClass
     * @param $htmlTag HtmlTag
     * @return HtmlTag
     */
    public function testRemoveClass(HtmlTag $htmlTag) {
        $htmlTag->removeClass('testClassName');
        $this->assertEquals('testClassName2', $htmlTag->htmlOptions['class']);

        $htmlTag->removeClass('testClassName2');
        $this->assertEquals('', $htmlTag->htmlOptions['class']);

        return $htmlTag;
    }

    /**
     * @depends testCreate
     * @param $htmlTag HtmlTag
     * @return HtmlTag
     */
    public function testAddAttribute(HtmlTag $htmlTag) {
        $htmlTag->addAttr("id", "testId");
        $this->assertArrayHasKey("id", $htmlTag->htmlOptions);
        $this->assertEquals("testId", $htmlTag->htmlOptions["id"]);

        $htmlTag->addAttr("name", "testName");
        $this->assertArrayHasKey("name", $htmlTag->htmlOptions);
        $this->assertEquals("testName", $htmlTag->htmlOptions["name"]);

        return $htmlTag;
    }

    /**
     * @depends testAddAttribute
     * @param $htmlTag HtmlTag
     * @return HtmlTag
     */
    public function testRemoveAttribute(HtmlTag $htmlTag) {
        $this->assertArrayHasKey("id", $htmlTag->htmlOptions);
        $htmlTag->removeAttr("id");
        $this->assertArrayNotHasKey("id", $htmlTag->htmlOptions);

        $this->assertArrayHasKey("name", $htmlTag->htmlOptions);
        $htmlTag->removeAttr("name");
        $this->assertArrayNotHasKey("name", $htmlTag->htmlOptions);

        return $htmlTag;
    }

    /**
     * @depends testCreate
     * @param $htmlTag HtmlTag
     * @return HtmlTag
     */
    public function testGetSetAttribute(HtmlTag $htmlTag) {

        $this->assertArrayNotHasKey("id", $htmlTag->htmlOptions);

        $htmlTag->attr("id", "testId");

        $this->assertArrayHasKey("id", $htmlTag->htmlOptions);
        $this->assertEquals("testId", $htmlTag->htmlOptions["id"]);
        $this->assertEquals("testId", $htmlTag->attr("id"));

        return $htmlTag;
    }

    /**
     * @depends testCreate
     * @param HtmlTag $htmlTag
     * @return HtmlTag
     */
    public function testToString(HtmlTag $htmlTag) {
        $htmlTag
            ->setTagName("a")
            ->attr("href", "#")
            ->addClass("testClassName1")
            ->addClass("testClassName2", "testClassName3");

        $this->assertEquals('<a class="testClassName1 testClassName2 testClassName3" id="testId" href="#"></a>', $htmlTag->toS());

        $htmlTag->begin();
        echo 'testContent';

        $htmlTag->removeClass('testClassName3');
        $htmlTag->attr("href", "#hash");

        $this->assertEquals('<a class="testClassName1 testClassName2" id="testId" href="#hash">testContent</a>', $htmlTag->end(true));

        return $htmlTag;
    }

    /**
     * @param HtmlTag $sourceHtmlTag
     * @return HtmlTag
     * @depends testToString
     */
    public function testCopy(HtmlTag $sourceHtmlTag){
        $destinationHtmlTag = $sourceHtmlTag->copy();

        $this->assertNotEquals(spl_object_hash($sourceHtmlTag), spl_object_hash($destinationHtmlTag));
        $this->assertEquals($sourceHtmlTag, $destinationHtmlTag);
        $sourceHtmlTag->addClass("test");
        $this->assertNotEquals($sourceHtmlTag, $destinationHtmlTag);

        return $sourceHtmlTag;
    }
}