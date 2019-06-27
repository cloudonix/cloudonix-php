<?php
/**
 *  ██████╗██╗      ██████╗ ██╗   ██╗██████╗  ██████╗ ███╗   ██╗██╗██╗  ██╗
 * ██╔════╝██║     ██╔═══██╗██║   ██║██╔══██╗██╔═══██╗████╗  ██║██║╚██╗██╔╝
 * ██║     ██║     ██║   ██║██║   ██║██║  ██║██║   ██║██╔██╗ ██║██║ ╚███╔╝
 * ██║     ██║     ██║   ██║██║   ██║██║  ██║██║   ██║██║╚██╗██║██║ ██╔██╗
 * ╚██████╗███████╗╚██████╔╝╚██████╔╝██████╔╝╚██████╔╝██║ ╚████║██║██╔╝ ██╗
 *  ╚═════╝╚══════╝ ╚═════╝  ╚═════╝ ╚═════╝  ╚═════╝ ╚═╝  ╚═══╝╚═╝╚═╝  ╚═╝
 *
 * Project: cloudonix-php | Tenants.php
 * Creator: nirs | 2019-06-27
 */

namespace Cloudonix;

use Exception;

/**
 * Cloudonix API.Core Client - Tenants Datamodel CRUD Interface
 *
 * @package Cloudonix
 */
class Tenants implements Datamodel
{
	public $client;
	public $name;
	public $id;

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
	 * Create a new Cloudonix Tenant
	 *
	 * @param array $object An tenant create object (represented as an array) as following:
	 * [
	 * 	'name' => 'mandatory_name',
	 * 	'profile' => [ optional array of key-value pairs]
	 * ]
	 * @return object $object The created Cloudonix Tenant Object
	 */
	public function create($object)
	{
		$result = $this->client->httpRequest('POST', '/tenants/', $object);
		return $result;
	}

	/**
	 * Update an existing Cloudonix Tenant
	 *
	 * @param array $object A tenant update object (represented as an array) as following:
	 * [
	 * 	'name' => 'mandatory_name',
	 * 	'profile' => [ optional array of key-value pairs]
	 * ]
	 * @return object $object The updated Cloudonix Tenant Object
	 */
	public function update($object)
	{
		$result = $this->client->httpRequest('PUT', '/tenants/' . $this->client->tenantId, $object);
		return json_decode((string)$result->getBody());
	}

	/**
	 * Get a tenant by Object (Not supported, return error referring to the correct function)
	 *
	 * @param object $object
	 * @return object
	 */
	public function get($object = null)
	{
		$result = $this->client->httpRequest('GET', '/tenants/' . $this->client->tenantId);
		return json_decode((string)$result->getBody());
	}

	/**
	 * Delete a tenant by Object (Not supported)
	 *
	 * @param object $object
	 * @return array
	 */
	public function delete($object)
	{
		$result = [ 'status' => false, 'message' => 'Deleting a tenant is a restricted function'];
		return $result;
	}

	/**
	 * Create a Tenant API key
	 *
	 * @param array $object An API key create object (represented as an array) as following:
	 * [
	 * 	'name' => 'mandatory_name',
	 * ]
	 * @return object A Cloudonix API key datamodel object
	 */
	public function createApikey($object)
	{
		$result = $this->client->httpRequest('POST', '/tenants/' . $this->client->tenantId . '/apikeys', $object);
		return json_decode((string)$result->getBody());
	}

	/**
	 * Update a Tenant API key
	 *
	 * @param array $object An API key create object (represented as an array) as following:
	 * [
	 *  'id' => 'the_apikey_id_to_update',
	 * 	'name' => 'mandatory_name'
	 * ]
	 * @return object A Cloudonix API key datamodel object
	 */
	public function updateApikey($object)
	{
		$result = $this->client->httpRequest('PUT', '/tenants/' . $this->client->tenantId . '/apikeys/' . $object['id'], $object);
		return json_decode((string)$result->getBody());
	}

	/**
	 * Delete a Tenant API key
	 *
	 * @param array $object An API key delete object (represented as an array) as following:
	 * [
	 *  'id' => 'the_apikey_id_to_delete',
	 * ]
	 * @return void
	 */
	public function deleteApikey($object)
	{
		$this->client->httpRequest('DELETE', '/tenants/' . $this->client->tenantId . '/apikeys/' . $object['id']);
	}

	/**
	 * Get a Tenant API key list
	 *
	 * @param null $object
	 * @return mixed
	 */
	public function getApikeys($object = null)
	{
		$result = $this->client->httpRequest('GET', '/tenants/' . $this->client->tenantId . '/apikeys');
		return json_decode((string)$result->getBody());
	}
}