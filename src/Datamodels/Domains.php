<?php
/**
 *  ██████╗██╗      ██████╗ ██╗   ██╗██████╗  ██████╗ ███╗   ██╗██╗██╗  ██╗
 * ██╔════╝██║     ██╔═══██╗██║   ██║██╔══██╗██╔═══██╗████╗  ██║██║╚██╗██╔╝
 * ██║     ██║     ██║   ██║██║   ██║██║  ██║██║   ██║██╔██╗ ██║██║ ╚███╔╝
 * ██║     ██║     ██║   ██║██║   ██║██║  ██║██║   ██║██║╚██╗██║██║ ██╔██╗
 * ╚██████╗███████╗╚██████╔╝╚██████╔╝██████╔╝╚██████╔╝██║ ╚████║██║██╔╝ ██╗
 *  ╚═════╝╚══════╝ ╚═════╝  ╚═════╝ ╚═════╝  ╚═════╝ ╚═╝  ╚═══╝╚═╝╚═╝  ╚═╝
 *
 * Project: cloudonix-php | Domains.php
 * Creator: Nir Simionovich <nirs@cloudonix.io> | 2019-06-27
 */

namespace Cloudonix\Datamodels;

use Cloudonix\Helpers\DomainGetter;
use Cloudonix\Helpers\DomainSetter;
use Cloudonix\Client as Client;
use Cloudonix\LazyDatamodel as LazyDatamodel;
use Exception;

/**
 * Cloudonix API.Core Client - Domains Datamodel CRUD Interface
 *
 * @package Cloudonix
 */
class Domains implements LazyDatamodel
{
	public $client;
	public $name;
	public $id;

	protected $domainGetter;
	protected $domainSetter;

	public function __construct(Client $client)
	{
		if (!$client)
			throw new Exception('Datamodel construction error', 500, null);
		$this->client = $client;
		$this->client->getSelf();
	}

	/**
	 * Create a new Cloudonix Domain
	 *
	 * @return DomainSetter $object The created Cloudonix Domain Object
	 */
	public function create(): DomainSetter
	{
		$this->domainSetter = new DomainSetter($this->client, 'create');
		return $this->domainSetter;
	}

	/**
	 * Update an existing Cloudonix Domain
	 *
	 * @return DomainSetter $object The created Cloudonix Domain Object
	 */
	public function update(): DomainSetter
	{
		$this->domainSetter = new DomainSetter($this->client, 'update');
		return $this->domainSetter;
	}

	/**
	 * Get a domain by domain ID or domain name
	 *
	 * @return DomainGetter
	 */
	public function get(): DomainGetter
	{
		$this->domainGetter = new DomainGetter($this->client, 'get');
		return $this->domainGetter;
	}

	/**
	 * Delete a domain by domain ID or domain name
	 *
	 * @return DomainSetter $object An empty response for success
	 */
	public function delete(): DomainSetter
	{
		$this->domainSetter = new DomainSetter($this->client, 'delete');
		return $this->domainSetter;
	}

	/**
	 * Create a domain alias
	 *
	 * @return DomainSetter $object The created Cloudonix Domain Alias Object
	 */
	public function createAlias(): DomainSetter
	{
		$this->domainSetter = new DomainSetter($this->client, 'createAlias');
		return $this->domainSetter;
	}

	/**
	 * Delete a domain alias
	 *
	 * @return DomainSetter $object An empty response for success
	 */
	public function deleteAlias(): DomainSetter
	{
		$this->domainSetter = new DomainSetter($this->client, 'deleteAlias');
		return $this->domainSetter;
	}

	/**
	 * List aliases of a domain
	 *
	 * @return DomainSetter $object An empty response for success
	 */
	public function listAliases(): DomainGetter
	{
		$this->domainGetter = new DomainGetter($this->client, 'listAliases');
		return $this->domainGetter;
	}

	/**
	 * Create a Domain API key
	 *
	 * @return DomainSetter $object A Cloudonix API key object
	 */
	public function createApikey(): DomainSetter
	{
		$this->domainSetter = new DomainSetter($this->client, 'createApikey');
		return $this->domainSetter;
	}

	/**
	 * Update a Domain API key
	 *
	 * @return DomainSetter A Cloudonix API key object
	 */
	public function updateApikey(): DomainSetter
	{
		$this->domainSetter = new DomainSetter($this->client, 'updateApikey');
		return $this->domainSetter;
	}

	/**
	 * Delete a Domain API key
	 *
	 * @return DomainSetter $object An empty response for success
	 */
	public function deleteApikey(): DomainSetter
	{
		$this->domainSetter = new DomainSetter($this->client, 'deleteApikey');
		return $this->domainSetter;
	}

	/**
	 * Get a Domain API key list
	 *
	 * @return DomainGetter
	 */
	public function getApikeys(): DomainGetter
	{
		$this->domainGetter = new DomainGetter($this->client, 'getApikeys');
		return $this->domainGetter;
	}
}