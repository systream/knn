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
	 * @param NodeInterface $node
	 */
	public function addNode(NodeInterface $node)
	{
		$this->nodes[] = $node;
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
			function (NodeInterface $a, NodeInterface $b) use ($node) {
				return $a->getDistance($node) > $b->getDistance($node);
			}
		);
		return array_slice($this->nodes, 0, $count);
	}
}