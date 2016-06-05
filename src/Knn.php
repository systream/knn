<?php

namespace Systream;

use Systream\Knn\NodeInterface;

class Knn
{
	/**
	 * @var NodeInterface[]
	 */
	protected $nodes = array();

	/**
	 * @var array
	 */
	protected $fieldMin = array();

	/**
	 * @var array
	 */
	protected $fieldMax = array();

	/**
	 * @var
	 */
	protected $ranges = array();

	/**
	 * @param NodeInterface $node
	 */
	public function addNode(NodeInterface $node)
	{
		$this->nodes[] = $node;

		foreach ($node->getCoordinate() as $field => $value) {
			if (!isset($this->fieldMin[$field])) {
				$this->fieldMin[$field] = $value;
			}

			if (!isset($this->fieldMax[$field])) {
				$this->fieldMax[$field] = $value;
			}

			if ($this->fieldMin[$field] > $value) {
				$this->fieldMin[$field] = $value;
			}

			if ($this->fieldMax[$field] < $value) {
				$this->fieldMax[$field] = $value;
			}
		}

		$ranges = array();
		foreach ($this->fieldMin as $field => $min) {
			$max = $this->fieldMax[$field];
			$ranges[$field] = $max - $min;
		}

		$this->ranges = $ranges;
	}

	/**
	 * @param NodeInterface $node
	 * @param int $count
	 * @return NodeInterface[]
	 */
	public function getNeighbours(NodeInterface $node, $count = 3)
	{
		usort(
			$this->nodes,
			function (NodeInterface $nodeA, NodeInterface $nodeB) use ($node, &$distanceCache) {
				
				$distanceA = $nodeA->getDistance($node, $this->ranges);
				$distanceB = $nodeB->getDistance($node, $this->ranges);
				
				if ($distanceA == $distanceB) {
					return 0;
				}

				return ($distanceA < $distanceB) ? -1 : 1;
			}
		);
		return array_slice($this->nodes, 0, $count);
	}
}