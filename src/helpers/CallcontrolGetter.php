<?php
/**
 *  ██████╗██╗      ██████╗ ██╗   ██╗██████╗  ██████╗ ███╗   ██╗██╗██╗  ██╗
 * ██╔════╝██║     ██╔═══██╗██║   ██║██╔══██╗██╔═══██╗████╗  ██║██║╚██╗██╔╝
 * ██║     ██║     ██║   ██║██║   ██║██║  ██║██║   ██║██╔██╗ ██║██║ ╚███╔╝
 * ██║     ██║     ██║   ██║██║   ██║██║  ██║██║   ██║██║╚██╗██║██║ ██╔██╗
 * ╚██████╗███████╗╚██████╔╝╚██████╔╝██████╔╝╚██████╔╝██║ ╚████║██║██╔╝ ██╗
 *  ╚═════╝╚══════╝ ╚═════╝  ╚═════╝ ╚═════╝  ╚═════╝ ╚═╝  ╚═══╝╚═╝╚═╝  ╚═╝
 *
 * Project: cloudonix-php | CallcontrolGetter.php
 * Creator: Nir Simionovich <nirs@cloudonix.io> | 2019-06-29
 */

namespace Cloudonix\Helpers;

use Exception;
use Cloudonix\Client as Client;
use Cloudonix\Exceptions\DatamodelBuilderException as DatamodelBuilderException;
use Cloudonix\Exceptions\MissingDomainIdException;

class CallcontrolGetter
{
	public $domainId = false;
	public $client;
	public $name;
	public $id;

	private $sessionToken = false;

	public function __construct(Client $client, $action)
	{
		try {
			if (!$client)
				throw new Exception('Datamodel Helper construction error', 500, null);

			$this->client = $client;
			$this->action = $action;
			$this->baseQuery = '/calls/';

		} catch (Exception $e) {
			die("Exception: " . $e->getMessage() . " File: " . $e->getFile() . " Line: " . $e->getLine());
		}
	}

	public function byDomainId($param)
	{
		$this->domainId = $param;
		return $this;
	}

	public function byDomainName($param)
	{
		return $this->byDomainId($param);
	}

	public function byDomain($param)
	{
		return $this->byDomainId($param);
	}

	public function bySessionToken($param)
	{
		$this->sessionToken = $param;
		return $this;
	}


	public function run()
	{
		try {
			if (!$this->domainId)
				throw new WorkflowViolation('`byDomainId|byDomainName|byDomain` MUST be called before `run`', 500, null);

			switch (strtolower($this->action)) {
				case "sessionget":
					if (!$this->sessionToken)
						throw new WorkflowViolation('`setSessionToken` MUST be called before `run`', 500, null);

					$result = $this->client->httpRequest('GET', $this->baseQuery . $this->domainId . '/sessions/' . $this->sessionToken, $this->actionData);
					break;
				case "sessionlist":
					$result = $this->client->httpRequest('GET', $this->baseQuery . $this->domainId . '/sessions', $this->actionData);
					break;
			}

			return $result;
		} catch (Exception $e) {
			die("Exception: " . $e->getMessage() . " File: " . $e->getFile() . " Line: " . $e->getLine());
		}
	}

}