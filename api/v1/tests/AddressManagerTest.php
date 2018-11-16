<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Rest\models\Address;
use Rest\Utils\AddressManager;

class AddressManagerTest extends TestCase
{
    public function testCreateAddressObject()
    {
        $data = [
            'label' => 'My Home Address',
            'country' => 'USA',
            'city' => 'New York',
        ];

        $expectedObject = new Address();
        $expectedObject->setLabel('My Home Address');
        $expectedObject->setCountry('USA');
        $expectedObject->setCity('New York');

        $addressManager = new AddressManager();

        $this->assertEquals($expectedObject, $addressManager->createFromArray($data));
    }

    /**
     * @dataProvider underscoreProvider
     */
    public function testConvertNameFromUnderscoredToCamelcased($underscored, $camelcased)
    {
        $address = new Address(['country' => '22', 'label' => 'My Label']);
        $this->assertEquals($camelcased, $address->nameCamelCase($underscored));
    }

    public function underscoreProvider()
    {
        return [
            ['some_property', 'someProperty'],
            ['two_underscores_property', 'twoUnderscoresProperty'],
            ['nullUnderscores', 'nullUnderscores'],
        ];
    }
}