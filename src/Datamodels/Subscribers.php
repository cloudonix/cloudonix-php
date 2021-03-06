<?php
/**
 *  ██████╗██╗      ██████╗ ██╗   ██╗██████╗  ██████╗ ███╗   ██╗██╗██╗  ██╗
 * ██╔════╝██║     ██╔═══██╗██║   ██║██╔══██╗██╔═══██╗████╗  ██║██║╚██╗██╔╝
 * ██║     ██║     ██║   ██║██║   ██║██║  ██║██║   ██║██╔██╗ ██║██║ ╚███╔╝
 * ██║     ██║     ██║   ██║██║   ██║██║  ██║██║   ██║██║╚██╗██║██║ ██╔██╗
 * ╚██████╗███████╗╚██████╔╝╚██████╔╝██████╔╝╚██████╔╝██║ ╚████║██║██╔╝ ██╗
 *  ╚═════╝╚══════╝ ╚═════╝  ╚═════╝ ╚═════╝  ╚═════╝ ╚═╝  ╚═══╝╚═╝╚═╝  ╚═╝
 *
 * Project: cloudonix-php | Subscribers.php
 * Creator: Nir Simionovich <nirs@cloudonix.io> | 2019-06-29
 */

namespace Cloudonix\Datamodels;

use Cloudonix\Helpers\SubscriberGetter;
use Cloudonix\Helpers\SubscriberSetter;
use Cloudonix\Client as Client;
use Cloudonix\LazyDatamodel as LazyDatamodel;
use Exception;

/**
 * Cloudonix API.Core Client - Subscribers Datamodel CRUD Interface
 *
 * @package Cloudonix
 */
class Subscribers implements LazyDatamodel
{
	public $client;
	public $name;
	public $id;

	protected $subscriberGetter;
	protected $subscriberSetter;

	public function __construct(Client $client)
	{
		if (!$client)
			throw new Exception('Datamodel construction error', 500, null);
		$this->client = $client;

	}

	/**
	 * Create a Subscriber
	 *
	 * @return SubscriberSetter The created subscriber object
	 */
	public function create(): SubscriberSetter
	{
		$this->subscriberSetter = new SubscriberSetter($this->client, 'create');
		return $this->subscriberSetter;
	}

	/**
	 * Update a Subscriber
	 *
	 * @return SubscriberSetter The updated subscriber object
	 */
	public function update(): SubscriberSetter
	{
		$this->subscriberSetter = new SubscriberSetter($this->client, 'update');
		return $this->subscriberSetter;
	}

	/**
	 * Get Subscriber (or list of)
	 *
	 * @return SubscriberGetter A subscriber (or list of) object
	 */
	public function get(): SubscriberGetter
	{
		$this->subscriberGetter = new SubscriberGetter($this->client);
		return $this->subscriberGetter;
	}

	/**
	 * Delete a Subscriber
	 *
	 * @return SubscriberSetter True on success
	 */
	public function delete(): SubscriberSetter
	{
		$this->subscriberSetter = new SubscriberSetter($this->client, 'delete');
		return $this->subscriberSetter;
	}

	/**
	 * Create a Subscriber API key in a domain - not applicable
	 *
	 * @return false
	 */
	public function createApikey()
	{
		return false;
	}

	/**
	 * Update a Subscriber API key in a domain - not applicable
	 *
	 * @return false
	 */
	public function updateApikey()
	{
		return false;
	}

	/**
	 * Delete a Subscriber API key in a domain - not applicable
	 *
	 * @return false
	 */
	public function deleteApikey()
	{
		return false;
	}

	/**
	 * Get a Subscriber API key in a domain - not applicable
	 *
	 * @return false
	 */
	public function getApikeys()
	{
		return false;
	}
}