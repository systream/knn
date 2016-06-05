<?php

namespace Systream\Knn;


use Systream\Knn\Exception\InvalidNodeValueException;
use Systream\Repository\Model\ModelAbstract;

class Node extends ModelAbstract implements NodeInterface
{
	/** 
	 * @var array 
	 */
	protected $distanceCache = array();

	/**
	 * @param null|string $name
	 * @return array
	 */
	public function getCoordinate($name = null)
	{
		if ($name !== null) {
			return isset($this->data[$name]) ? $this->data[$name] : null;
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
	 * @param array $neighboursFieldRanges
	 * @return float
	 */
	public function getDistance(NodeInterface $node, array $neighboursFieldRanges = array())
	{
		$nodeHashId = $node->getId();

		if (isset($this->distanceCache[$nodeHashId])) {
			return $this->distanceCache[$nodeHashId];
		}
		
		$totalDistance = 0;

		foreach ($node->getCoordinate() as $name => $value) {
			$oneCoordinatesDistance = $this->data[$name] - $value;

			if (isset($neighboursFieldRanges[$name])) {
				$oneCoordinatesDistance = $oneCoordinatesDistance / $neighboursFieldRanges[$name];
			}

			$totalDistance += $oneCoordinatesDistance * $oneCoordinatesDistance;
		}

		$result = sqrt($totalDistance);
		$this->distanceCache[$nodeHashId] = $result;
		return $result;
	}

	/**
	 * @return string
	 */
	public function getId()
	{
		$id = '';
		foreach ($this->data as $field => $value) {
			$id .= $field . ':' . $value . '|';
		}

		return $id;
	}
}