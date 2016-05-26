<?php

namespace Systream\Knn;


use Systream\Knn\Exception\InvalidNodeValueException;
use Systream\Repository\Model\ModelAbstract;

class Node extends ModelAbstract implements NodeInterface
{
	/**
	 * @var float
	 */
	protected $distance;

	/**
	 * @param null|string $name
	 * @return array
	 */
	public function getCoordinate($name = null)
	{
		if ($name !== null) {
			return $this->$name;
		}
		return $this->toArray();
	}


	/**
	 * @param string $name
	 * @param int $value
	 * @throws InvalidNodeValueException
	 */
	function __set($name, $value)
	{
		$this->checkNumericValue($name, $value);

		parent::__set($name, $value);
	}

	/**
	 * @param array $data
	 * @return Void
	 * @throws InvalidNodeValueException
	 */
	public function loadData(array $data)
	{
		foreach ($data as $name => $value) {
			$this->checkNumericValue($name, $value);
		}
		parent::loadData($data);
	}

	/**
	 * @param $name
	 * @param $value
	 * @throws InvalidNodeValueException
	 */
	protected function checkNumericValue($name, $value)
	{
		if (!is_numeric($value)) {
			throw new InvalidNodeValueException(sprintf('%s value %s is not numeric.', $name, $value));
		}
	}

	/**
	 * @param NodeInterface $node
	 * @return float
	 */
	public function getDistance(NodeInterface $node)
	{
		$coordinatesDistance = array();

		foreach ($node->getCoordinate() as $name => $value) {
			$coordinatesDistance[$name] = $this->getCoordinate($name) - $value;
		}

		$totalDistance = 0;
		foreach ($coordinatesDistance as $oneCoordinatesDistance) {
			$totalDistance += $oneCoordinatesDistance * $oneCoordinatesDistance;
		}

		return sqrt($totalDistance);
	}
}