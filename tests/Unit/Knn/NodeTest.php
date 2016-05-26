<?php

namespace Tests\Systream\Unit\Knn;


use Systream\Knn\Node;

class NodeTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @tests
	 * @param int $value
	 * @dataProvider numericInputs
	 */
	public function acceptNumericValues($value)
	{
		$node = new Node();
		$node->x = $value;
		$this->assertEquals($value, $node->x);
	}

	/**
	 * @tests
	 * @param int $value
	 * @dataProvider numericInputs
	 */
	public function getCoordinate_full($value)
	{
		$node = new Node();
		$node->bar = $value;
		$node->foo = 120;
		$this->assertEquals(
			array(
				'bar' => $value,
				'foo' => 120
			), $node->getCoordinate()
		);
	}

	/**
	 * @tests
	 * @param int $value
	 * @dataProvider numericInputs
	 */
	public function getCoordinate_OneField($value)
	{
		$node = new Node();
		$node->bar = $value;
		$node->foo = 1;
		$this->assertEquals($value, $node->getCoordinate('bar'));
		$this->assertEquals(1, $node->getCoordinate('foo'));
	}

	/**
	 * @tests
	 */
	public function getCoordinate_empty()
	{
		$node = new Node();
		$this->assertEmpty($node->getCoordinate());
		$this->assertInternalType('array', $node->getCoordinate());
	}

	/**
	 * @tests
	 */
	public function getCoordinate_notExistsCoordinate()
	{
		$node = new Node();
		$this->assertNull($node->getCoordinate('fooBar_notExists'));
	}
	
	/**
	 * @return array
	 */
	public function numericInputs()
	{
		return array(
			array(1),
			array(-10000),
			array(2.32323),
			array(1.2e3),
			array(7E-10),
			array(0),
			array(-1.2),
			array(-3.4e3),
			array(-2E-10),

			array('1'),
			array('-10000'),
			array('2.32323'),
			array('1.2e3'),
			array('7E-10'),
			array('0'),
			array('-1.2'),
			array('-3.4e3'),
			array('-2E-10'),
		);
	}

	/**
	 * @tests
	 * @param int $value
	 * @dataProvider nonNumericInputs
	 * @expectedException \Exception
	 */
	public function nonNumericValues($value)
	{
		$node = new Node();
		$node->x = $value;
	}

	/**
	 * @return array
	 */
	public function nonNumericInputs()
	{
		return array(
			array(''),
			array(null),
			array('foo'),
			array(false),
			array(true),
		);
	}

	/**
	 * @tests
	 * @param int $value
	 * @dataProvider numericInputs
	 * @depends acceptNumericValues
	 */
	public function loadData_OnlyNumeric($value)
	{
		$node = new Node();
		$node->loadData(array('x' => $value));
		$this->assertEquals($value, $node->x);
	}

	/**
	 * @tests
	 * @param int $value
	 * @dataProvider nonNumericInputs
	 * @expectedException \Exception
	 * @depends nonNumericValues
	 */
	public function loadData_nonNumericValues($value)
	{
		$node = new Node();
		$node->loadData(array('y' => $value));
	}

}
