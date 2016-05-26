<?php

namespace Tests\Systream\Unit;


use Systream\Knn;

class KnnTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @tests
	 */
	public function oneDimension_simple()
	{
		$knn = new Knn();
		$node1 = $this->createNode(array('x' => 10));
		$knn->addNode($node1);
		$node2 = $this->createNode(array('x' => 11));
		$knn->addNode($node2);
		$node3 = $this->createNode(array('x' => 12));
		$knn->addNode($node3);

		$neighbours = $knn->getNeighbours($this->createNode(array('x' => 0)), 1);
		
		$this->assertEquals(
			array(
				$node1
			),
			$neighbours
		);
	}

	/**
	 * @tests
	 */
	public function oneDimension_negative()
	{
		$knn = new Knn();
		$node1 = $this->createNode(array('x' => 10));
		$knn->addNode($node1);
		$node2 = $this->createNode(array('x' => 11));
		$knn->addNode($node2);
		$node3 = $this->createNode(array('x' => -5));
		$knn->addNode($node3);

		$neighbours = $knn->getNeighbours($this->createNode(array('x' => -1)), 1);

		$this->assertEquals(
			array(
				$node3
			),
			$neighbours
		);
	}

	/**
	 * @param array $data
	 * @return Knn\Node
	 */
	protected function createNode(array $data)
	{
		$node = new Knn\Node();
		$node->loadData($data);
		return $node;
	}
}
