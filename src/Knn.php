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
		$ranges = $this->ranges;
		usort(
			$this->nodes,
			function (NodeInterface $nodeA, NodeInterface $nodeB) use ($node, $ranges) {
				return $nodeA->getDistance($node, $ranges) > $nodeB->getDistance($node, $ranges);
			}
		);
		return array_slice($this->nodes, 0, $count);
	}

	/**
	 * @param string $field
	 * @return int|mixed
	 */
	protected function getFieldRange($field)
	{
		if (!isset($this->fieldMin[$field])) {
			return 0;
		}

		return $this->fieldMax[$field] - $this->fieldMin[$field];
	}
}