<?php
namespace Simonetti\IntegradorFinanceiro\Tests\Source;

use Simonetti\IntegradorFinanceiro\Destination\Destination as FinalDestination;
use Simonetti\IntegradorFinanceiro\Source\Destination as SourceDestination;

/**
 * Class DestinationTest
 * @package Simonetti\IntegradorFinanceiro\Tests\Source
 */
class DestinationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FinalDestination
     */
    protected $finalDestination;

    /**
     * @var SourceDestination\DataMapping
     */
    protected $dataMapping;

    public function setUp()
    {
        $this->finalDestination = $this->getFinalDestination();
        $this->dataMapping = $this->getDataMapping();
    }

    /**
     * @return FinalDestination
     */
    protected function getFinalDestination()
    {
        $identifier = 'test';
        $name = 'test';
        $bridge = 'test';

        return new FinalDestination($identifier, $name, $bridge);
    }

    /**
     * @return SourceDestination\DataMapping
     */
    protected function getDataMapping()
    {
        $columns = [
            'oldKey1' => 'newKey1',
            'oldKey2' => 'newKey2',
            'oldKey3' => 'newKey3',
        ];

        return new SourceDestination\DataMapping($columns);
    }

    /**
     * @return SourceDestination
     */
    protected function getSourceDestination()
    {
        return new SourceDestination($this->finalDestination, $this->dataMapping);
    }

    public function testConstructor()
    {
        $sourceDestination = $this->getSourceDestination();

        $this->assertEquals($this->finalDestination, $sourceDestination->getDestination());
        $this->assertInstanceOf(FinalDestination::class, $sourceDestination->getDestination());
        $this->assertEquals($this->dataMapping, $sourceDestination->getDataMapping());
        $this->assertInstanceOf(SourceDestination\DataMapping::class, $sourceDestination->getDataMapping());
    }

    public function testDestinationDataMappingMustReturnCorrectColumn()
    {
        $sourceDestination = $this->getSourceDestination();

        $key = 'oldKey3';
        $expectedColumn = 'newKey3';

        $this->assertEquals($expectedColumn, $sourceDestination->getColumnByKey($key));
    }

    public function testDestinationDataMappingMustReturnNull()
    {
        $sourceDestination = $this->getSourceDestination();

        $key = 'someDifferentKey';
        $expectedColumn = 'newKey3';

        $receivedColumn = $sourceDestination->getColumnByKey($key);

        $this->assertNotEquals($expectedColumn, $receivedColumn);
        $this->assertNull($receivedColumn);
    }
}