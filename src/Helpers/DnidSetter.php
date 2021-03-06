<?php
/**
 *  ██████╗██╗      ██████╗ ██╗   ██╗██████╗  ██████╗ ███╗   ██╗██╗██╗  ██╗
 * ██╔════╝██║     ██╔═══██╗██║   ██║██╔══██╗██╔═══██╗████╗  ██║██║╚██╗██╔╝
 * ██║     ██║     ██║   ██║██║   ██║██║  ██║██║   ██║██╔██╗ ██║██║ ╚███╔╝
 * ██║     ██║     ██║   ██║██║   ██║██║  ██║██║   ██║██║╚██╗██║██║ ██╔██╗
 * ╚██████╗███████╗╚██████╔╝╚██████╔╝██████╔╝╚██████╔╝██║ ╚████║██║██╔╝ ██╗
 *  ╚═════╝╚══════╝ ╚═════╝  ╚═════╝ ╚═════╝  ╚═════╝ ╚═╝  ╚═══╝╚═╝╚═╝  ╚═╝
 *
 * Project: cloudonix-php | DnidSetter.php
 * Creator: Nir Simionovich <nirs@cloudonix.io> | 2019-06-29
 */

namespace Cloudonix\Helpers;

use Exception;
use Cloudonix\Client as Client;
use Cloudonix\Exceptions\DatamodelBuilderException;
use Cloudonix\Exceptions\MissingAdditionalDataException;
use Cloudonix\Exceptions\MissingApplicationIdException;
use Cloudonix\Exceptions\MissingDnidIdException;
use Cloudonix\Exceptions\MissingDomainIdException;

class DnidSetter
{
	public $baseFilter;
	public $baseQuery = false;
	public $domainId = false;
	public $dnids;
	public $client;
	public $name;
	public $id;

	private $applicationId = false;
	private $dnidNumber = false;
	private $dnidSource = false;
	private $dnidId = false;
	private $action;
	private $actionData = [];

	public function __construct(Client $client, $action)
	{
		if (!$client)
			throw new DatamodelBuilderException('Datamodel Helper construction error', 500, null);

		$this->client = $client;
		$this->action = $action;
	}

	public function setApplication($application)
	{
		$this->applicationId = $application;
		$this->baseQuery .= '/applications/' . $application . '/dnids';
		return $this;
	}

	public function byApplication($application)
	{
		return $this->setApplication($application);
	}

	public function setApplicationId($applicationId)
	{
		return $this->setApplication($applicationId);
	}

	public function byApplicationId($applicationId)
	{
		return $this->setApplication($applicationId);
	}

	public function setDnid($dnid)
	{
		$this->dnidNumber = true;
		$this->actionData['dnid'] = $dnid;
		return $this;
	}

	public function setDnidId($dndId)
	{
		$this->dnidId = $dndId;
		return $this;
	}

	public function setSource($source)
	{
		$this->dnidSource = true;
		$this->actionData['source'] = $source;
		return $this;
	}

	public function setActive($active)
	{
		$this->actionData['active'] = (int)$active;
		return $this;
	}

	public function setPrefix($prefix)
	{
		$this->actionData['prefix'] = $prefix;
		return $this;
	}

	public function setExpression($expression)
	{
		$this->actionData['expression'] = $expression;
		return $this;
	}

	public function setAsteriskCompatible($expression)
	{
		$this->actionData['asteriskCompatible'] = $expression;
		return $this;
	}

	public function setKey($key, $value)
	{
		$this->actionData[$key] = $value;
		return $this;
	}

	public function setDomainId($domainId)
	{
		$this->domainId = $domainId;
		$this->baseQuery = '/domains/' . $this->domainId;
		return $this;
	}

	public function run()
	{
		if ((!$this->domainId) || (!$this->baseQuery))
			throw new MissingDomainIdException('`setDomainId` MUST be called before `run`', 500, null);

		if (!$this->applicationId)
			throw new MissingApplicationIdException('`setApplicationId` MUST be called before `run`', 500, null);

		switch (strtolower($this->action)) {
			case "create":
			case "update":

				if ((!$this->dnidNumber) && (!$this->dnidSource))
					throw new MissingDnidIdException('`setDnid` and `setSource` MUST be called before `run`', 500, null);

				if ($this->dnidSource) {
					if ((!array_key_exists('prefix', $this->actionData)) &&
						(!array_key_exists('expression', $this->actionData)) &&
						(!array_key_exists('asteriskCompatible', $this->actionData))
					)
						throw new MissingAdditionalDataException('`setSource` MUST be followed by either `setPrefix`, `setExpression` or `setAsteriskCompatible` before `run`', 500, null);
				}

				$httpAction = (strtolower($this->action) == "create") ? "POST" : "PUT";
				$result = $this->client->httpRequest($httpAction, $this->baseQuery, $this->actionData);
				break;
			case "delete":

				if (!$this->dnidId)
					throw new MissingAdditionalDataException('`setDnidId` MUST be called on `delete` methods before `run`', 500, null);

				$result = $this->client->httpRequest('DELETE', $this->baseQuery . '/' . $this->dnidId);
				break;
			default:
				return false;
				break;
		}
		return $result;

	}


}