<?php

namespace Systream\Knn;


interface NodeInterface
{
	/**
	 * @param null|string $name
	 * @return array
	 */
	public function getCoordinate($name = null);

	/**
	 * @param NodeInterface $node
	 * @param array $neighboursFieldRanges
	 * @return float
	 */
	public function getDistance(NodeInterface $node, array $neighboursFieldRanges = array());

	/**
	 * @return string
	 */
	public function getId();
}