<?php

namespace Test\Systream\Performance;


use Systream\Knn;
use Tests\Systream\Unit\KnnTestAbstract;

class KnnTest extends KnnTestAbstract
{

	/**
	 * @tests
	 */
	public function test10000Node3dimension()
	{

		$nodes = array();
		for ($x = 0; $x < 10000; $x++) {
			$nodes[] =
				$this->createNodeWithData(
					rand(0, 100000),
					rand(-1000, 9999999999),
					rand(-1212312312, 23123123123)
				);
		}

		$testNode = $this->createNodeWithData(
			rand(0, 100000),
			rand(-1000, 9999999999),
			rand(-1212312312, 23123123123)
		);

		$startTime = microtime(true);
		$knn = new Knn();
		foreach ($nodes as $node) {
			$knn->addNode($node);
		}

		$this->assertLessThanOrEqual(0.15, microtime(true)-$startTime, sprintf('%d node adding should be less thean 150ms', $x));

		$knn->getNeighbours($testNode);
		
		$this->assertLessThanOrEqual(2, microtime(true)-$startTime);

	}
}