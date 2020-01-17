<?php

namespace Gewaer\Tests\Unit;

use UnitTester;
use Kanvas\Sdk\Util\Util;

class UtilCest
{
    /**
     * @param UnitTester $I
     */
    public function testConvertParamsConditions(UnitTester $I)
    {
        $params = [
                'conditions'=> 'default_company = 3 and is_deleted = 0'
            ];

        $I->assertTrue(is_array(Util::convertParams($params)));
    }

    /**
     * @param UnitTester $I
     */
    public function testConvertParamsConditionsBind(UnitTester $I)
    {
        $params = [
                'conditions'=> 'default_company = ?0 and is_deleted = 0',
                'bind' => ['max@mctekk.com']
            ];

        $I->assertTrue(is_array(Util::convertParams($params)));
    }

    /**
     * @param UnitTester $I
     */
    public function testConvertParamsOrder(UnitTester $I)
    {
        $params = [
                'order'=> 'firstname|desc'
            ];

        $I->assertTrue(is_array(Util::convertParams($params)));
    }

    /**
     * @param UnitTester $I
     */
    public function testConvertParamsLimit(UnitTester $I)
    {
        $params = [
                'limit' => '2'
            ];

        $I->assertTrue(is_array(Util::convertParams($params)));
    }
}
