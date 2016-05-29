<?php

namespace Tests\Systream\Unit;


use Systream\Knn;

abstract class KnnTestAbstract extends \PHPUnit_Framework_TestCase
{
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

	/**
	 * @param array $values
	 * @return Knn\Node you can pass mukti
	 */
	protected function createNodeWithData(...$values)
	{
		$node = new Knn\Node();
		$char = 'a';
		foreach ($values as $value) {
			$node->$char = $value;
			$char++;
		}
		return $node;
	}
}
