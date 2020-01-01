<?php
/**
 *  ██████╗██╗      ██████╗ ██╗   ██╗██████╗  ██████╗ ███╗   ██╗██╗██╗  ██╗
 * ██╔════╝██║     ██╔═══██╗██║   ██║██╔══██╗██╔═══██╗████╗  ██║██║╚██╗██╔╝
 * ██║     ██║     ██║   ██║██║   ██║██║  ██║██║   ██║██╔██╗ ██║██║ ╚███╔╝
 * ██║     ██║     ██║   ██║██║   ██║██║  ██║██║   ██║██║╚██╗██║██║ ██╔██╗
 * ╚██████╗███████╗╚██████╔╝╚██████╔╝██████╔╝╚██████╔╝██║ ╚████║██║██╔╝ ██╗
 *  ╚═════╝╚══════╝ ╚═════╝  ╚═════╝ ╚═════╝  ╚═════╝ ╚═╝  ╚═══╝╚═╝╚═╝  ╚═╝
 *
 * Project: cloudonix-php | CallcontrolSetter.php
 * Creator: Nir Simionovich <nirs@cloudonix.io> | 2019-07-02
 */
namespace Cloudonix\Helpers;

use Exception;
use Cloudonix\Client as Client;
use Cloudonix\Exceptions\WorkflowViolation;

class CallcontrolSetter
{
	public $baseFilter = "?";
	public $baseQuery = false;
	public $client;
	public $name;
	public $id;

	private $action = false;
	private $actionData = [];
	private $domainIdent = false;
	private $outboundSubscriber = false;
	private $sessionToken = false;
	private $disposition = false;

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

	public function setDomainId($param)
	{
		$this->domainIdent = (int)$param;
		return $this;
	}

	public function setDomainName($param)
	{
		$this->domainIdent = $param;
		return $this;
	}

	public function setDomain($param)
	{
		return (is_numeric($param)) ? $this->setDomainId($param) : $this->setDomainName($param);
	}

	public function setOutboundSubscriber($param)
	{
		$this->outboundSubscriber = $param;
		return $this;
	}

	public function setOutboundDestination($param)
	{
		$this->actionData['destination'] = $param;
		return $this;
	}

	public function setInboundSubscriber($param)
	{
		$this->actionData['destination'] = $param;
		return $this;
	}

	public function setInboundCallerId($param)
	{
		$this->actionData['caller-id'] = $param;
		return $this;
	}

	public function setInboundCallbackUrl($param)
	{
		$this->actionData['callback'] = $param;
		return $this;
	}

	public function setOutboundTimelimit($param)
	{
		$this->actionData['timeLimit'] = $param;
		return $this;
	}

	public function setOutboundCharges($sellrate = 0, $sellrate_minimum = 1, $sellrate_increment = 1)
	{
		$this->actionData['routing']['sellrate'] = $sellrate;
		$this->actionData['routing']['sellrate_minimum'] = $sellrate_minimum;
		$this->actionData['routing']['sellrate_increment'] = $sellrate_increment;
		return $this;
	}

	public function setOutboundRoute($param)
	{
		$outboundRouteDataCount = 0;
		foreach ($param as $key => $value) {
			switch ($key) {
				case "provider_id":
					$outboundRouteDataCount++;
					break;
				default:
					break;
			}
		}

		if ($outboundRouteDataCount != 9)
			throw new WorkflowViolation('Provided route definition is incomplete', 500, null);

		$this->actionData['routes'][] = $param;
		return $this;
	}

	public function setOutboundMetadata($param)
	{
		$this->actionData['profile'] = $param;
		return $this;
	}

	public function setSessionToken($param)
	{
		$this->sessionToken = $param;
		return $this;
	}

	public function pushOutboundRoute($param)
	{
		return $this->setOutboundRoute($param);
	}

	public function setDestructionCause($param)
	{
		switch (strtolower($param)) {
			case "timeout":
			case "denied":
			case "busy":
			case "completed":
			case "nocredit":
				$this->disposition = $param;
				break;
			default:
				$this->disposition = "unknown";
				break;
		}
		return $this;
	}


	public function run()
	{

		try {

			if ((!$this->domainIdent) || (!strlen($this->domainIdent)))
				throw new WorkflowViolation('`setDomainId|setDomainName|setDomain` MUST be called before `run`', 500, null);

			switch (strtolower($this->action)) {
				case "initSessionOutbound":
					if (!$this->outboundSubscriber)
						throw new WorkflowViolation('`setOutboundSubscriber` MUST be called before `run`', 500, null);

					if (!array_key_exists('destination', $this->action))
						throw new WorkflowViolation('`setOutboundDestination` MUST be called before `run`', 500, null);

					$result = $this->client->httpRequest('POST',
						$this->baseQuery . $this->domainIdent . '/outgoing/' . $this->subscriberMsisdn, $this->actionData);
					break;
				case "initSessionInbound":
					if (!array_key_exists('destination', $this->actionData))
						throw new WorkflowViolation('`setInboundSubscriber` MUST be called before `run`', 500, null);

					$result = $this->client->httpRequest('POST',
						$this->baseQuery . $this->domainIdent . '/incoming/' . $this->actionData['destination'], $this->actionData);

					break;
				case "sessionUpdate":
					if (!$this->sessionToken)
						throw new WorkflowViolation('`setSessionToken` MUST be called before `run`', 500, null);

					if (array_key_exists('destination', $this->action))
						throw new WorkflowViolation('`setOutboundDestination` is an illegal action for `sessionUpdate`', 500, null);

					$result = $this->client->httpRequest('PATCH',
						$this->baseQuery . $this->domainIdent . '/sessions/' . $this->sessionToken, $this->actionData);

					break;
				case "sessionDestroy":
					if (!$this->sessionToken)
						throw new WorkflowViolation('`setSessionToken` MUST be called before `run`', 500, null);

					if (!$this->disposition)
						throw new WorkflowViolation('`setDestructionCause` MUST be called before `run`', 500, null);

					$result = $this->client->httpRequest('DELETE',
						$this->baseQuery . $this->domainIdent . '/sessions/' . $this->sessionToken, $this->actionData);

					break;
				case "sessionNotifyRinging":

					if (!$this->sessionToken)
						throw new WorkflowViolation('`setSessionToken` MUST be called before `run`', 500, null);

					if (!array_key_exists('destination', $this->actionData))
						throw new WorkflowViolation('`setInboundSubscriber` MUST be called before `run`', 500, null);

					$result = $this->client->httpRequest('GET',
						$this->baseQuery . $this->domainIdent . '/ringing/' . $this->actionData['destination'] . '/' . $this->sessionToken, $this->actionData);

					break;
				default:
					return false;
					break;

			}
			return $result;

		} catch (Exception $e) {
			die("Exception: " . $e->getMessage() . " File: " . $e->getFile() . " Line: " . $e->getLine());
		}
	}


}