<?php
/**
 *  ██████╗██╗      ██████╗ ██╗   ██╗██████╗  ██████╗ ███╗   ██╗██╗██╗  ██╗
 * ██╔════╝██║     ██╔═══██╗██║   ██║██╔══██╗██╔═══██╗████╗  ██║██║╚██╗██╔╝
 * ██║     ██║     ██║   ██║██║   ██║██║  ██║██║   ██║██╔██╗ ██║██║ ╚███╔╝
 * ██║     ██║     ██║   ██║██║   ██║██║  ██║██║   ██║██║╚██╗██║██║ ██╔██╗
 * ╚██████╗███████╗╚██████╔╝╚██████╔╝██████╔╝╚██████╔╝██║ ╚████║██║██╔╝ ██╗
 *  ╚═════╝╚══════╝ ╚═════╝  ╚═════╝ ╚═════╝  ╚═════╝ ╚═╝  ╚═══╝╚═╝╚═╝  ╚═╝
 *
 * Project: cloudonix-php | Dnids.php
 * Creator: nirs | 2019-06-28
 */

namespace Cloudonix;

use Exception;

/**
 * Cloudonix API.Core Client - DNIDs Datamodel CRUD Interface
 *
 * @package Cloudonix
 */
class Dnids implements LazyDatamodel
{
	public $client;
	public $name;
	public $id;

	protected $dnidGetter;
	protected $dnidSetter;

	public function __construct(Client $client)
	{
		try {
			if (!$client)
				throw new Exception('Datamodel construction error', 500, null);
			$this->client = $client;

		} catch (Exception $e) {
			die("Exception: " . $e->getMessage() . " File: " . $e->getFile() . " Line: " . $e->getLine());
		}
	}

	/**
	 * Create a DNID in a Domain
	 *
	 * @return DnidSetter $object The DNID (or list) retrieved from the datamodel
	 */
	public function create(): DnidSetter
	{
		if (!$this->dnidSetter) {
			$this->dnidSetter = new DnidSetter($this->client, 'create');
		}
		return $this->dnidSetter;
	}

	/**
	 * Update a DNID in a Domain
	 *
	 * @return DnidSetter $object The DNID (or list) retrieved from the datamodel
	 */
	public function update(): DnidSetter
	{
		if (!$this->dnidSetter) {
			$this->dnidSetter = new DnidSetter($this->client, 'update');
		}
		return $this->dnidSetter;
	}

	/**
	 * Get DNID information from a Domain
	 *
	 * @return DnidGetter $object The DNID (or list) retrieved from the datamodel
	 */
	public function get(): DnidGetter
	{
		if (!$this->dnidGetter) {
			$this->dnidGetter = new DnidGetter($this->client);
		}
		return $this->dnidGetter;
	}

	/**
	 * Delete a DNID in a Domain designated by an ID
	 *
	 * @return DnidSetter $object The DNID (or list) retrieved from the datamodel
	 */
	public function delete(): DnidSetter
	{
		if (!$this->dnidSetter) {
			$this->dnidSetter = new DnidSetter($this->client, 'delete');
		}
		return $this->dnidSetter;
	}

	/**
	 * Create a new API key in the data model - Not Applicable
	 *
	 * @param $object
	 * @return bool
	 */
	public function createApikey()
	{
		return false;
	}

	/**
	 * Update an existing API key object in the data model - Not Applicable
	 *
	 * @param $object
	 * @return bool
	 */
	public function updateApikey()
	{
		return false;
	}

	/**
	 * Delete an existing API key object from the data model - Not Applicable
	 *
	 * @param $object
	 * @return bool
	 */
	public function deleteApikey()
	{
		return false;
	}

	/**
	 * Get a list of currently available API keys in the data model - Not Applicable
	 *
	 * @param $object
	 * @return bool
	 */
	public function getApikeys()
	{
		return false;
	}
}