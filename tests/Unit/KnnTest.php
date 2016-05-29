<?php

namespace Tests\Systream\Unit;


use Systream\Knn;

class KnnTest extends KnnTestAbstract
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
	 * @param array $nodes
	 * @param Knn\Node $testNode
	 * @param Knn\Node $expectedNode
	 * @tests
	 * @dataProvider multiDimensionDataProvider
	 */
	public function multiDimension(array $nodes, Knn\Node $testNode, Knn\Node $expectedNode)
	{
		$knn = new Knn();
		foreach ($nodes as $node) {
			$knn->addNode($node);
		}

		$neighbours = $knn->getNeighbours($testNode, 1);

		$this->assertEquals(
			array(
				$expectedNode->toArray()
			),
			array(
				$neighbours[0]->toArray()
			)
		);
	}

	/**
	 * @return array
	 */
	public function multiDimensionDataProvider()
	{
		return array(
			array(
				array(
					$this->createNodeWithData(10,10),
					$this->createNodeWithData(12,12),
					$this->createNodeWithData(10,12),
					$this->createNodeWithData(5,10)
				),
				$this->createNodeWithData(9,10),
				$this->createNodeWithData(10,10)
			), // -----

			array(
				array(
					$this->createNodeWithData(-10,-10),
					$this->createNodeWithData(0,2),
					$this->createNodeWithData(2,-3301231232),
					$this->createNodeWithData(-3123123,131231231231231231230)
				),
				$this->createNodeWithData(-1,3),
				$this->createNodeWithData(0,2),
			), // -----

			array(
				array(
					$this->createNodeWithData(-10,-10, -10),
					$this->createNodeWithData(0,2, 0),
					$this->createNodeWithData(2,-3301231232, 12),
					$this->createNodeWithData(-3123123,131231231231231231230, 0)
				),
				$this->createNodeWithData(1,-1, 2),
				$this->createNodeWithData(0,2, 0),
			), // -----

			array(
				array(
					$this->createNodeWithData(-10, -10, -10, 10),
					$this->createNodeWithData(0, 2, 0, 2),
					$this->createNodeWithData(2, -3301231232, 12, 1123123),
					$this->createNodeWithData(-3123123, 131231231231231231230, 0, -31)
				),
				$this->createNodeWithData(1,-1, 2, 0),
				$this->createNodeWithData(0, 2, 0, 2),
			), // -----
		);
	}
}
