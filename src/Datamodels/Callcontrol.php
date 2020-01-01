<?php
/**
 *  ██████╗██╗      ██████╗ ██╗   ██╗██████╗  ██████╗ ███╗   ██╗██╗██╗  ██╗
 * ██╔════╝██║     ██╔═══██╗██║   ██║██╔══██╗██╔═══██╗████╗  ██║██║╚██╗██╔╝
 * ██║     ██║     ██║   ██║██║   ██║██║  ██║██║   ██║██╔██╗ ██║██║ ╚███╔╝
 * ██║     ██║     ██║   ██║██║   ██║██║  ██║██║   ██║██║╚██╗██║██║ ██╔██╗
 * ╚██████╗███████╗╚██████╔╝╚██████╔╝██████╔╝╚██████╔╝██║ ╚████║██║██╔╝ ██╗
 *  ╚═════╝╚══════╝ ╚═════╝  ╚═════╝ ╚═════╝  ╚═════╝ ╚═╝  ╚═══╝╚═╝╚═╝  ╚═╝
 *
 * Project: cloudonix-php | Calls.php
 * Creator: Nir Simionovich <nirs@cloudonix.io> | 2019-06-27
 */

namespace Cloudonix\Datamodels;

use Cloudonix\Client as Client;
use Cloudonix\Helpers\CallcontrolSetter;
use Cloudonix\Helpers\CallcontrolGetter;
use Exception;

/**
 * Cloudonix API.Core Client - Calls Datamodel CRUD Control interface
 *
 * @package Cloudonix
 */
class Callcontrol
{
	public $client;
	public $name;
	public $id;

	protected $callcontrolGetter;
	protected $callcontrolSetter;

	public function __construct(Client $client)
	{
		if (!$client)
			throw new Exception('Datamodel construction error', 500, null);
		$this->client = $client;
		$this->client->getSelf();
	}

	public function initSessionOutbound(): CallcontrolSetter {
		$this->callcontrolSetter = new CallcontrolSetter($this->client, 'initSessionOutbound');
		return $this->callcontrolSetter;
	}

	public function initSessionInbound(): CallcontrolSetter {
		$this->callcontrolSetter = new CallcontrolSetter($this->client, 'initSessionInbound');
		return $this->callcontrolSetter;
	}

	public function sessionUpdate(): CallcontrolSetter {
		$this->callcontrolSetter = new CallcontrolSetter($this->client, 'sessionUpdate');
		return $this->callcontrolSetter;
	}

	public function sessionDestroy(): CallcontrolSetter {
		$this->callcontrolSetter = new CallcontrolSetter($this->client, 'sessionDestroy');
		return $this->callcontrolSetter;
	}

	public function sessionNotifyRinging(): CallcontrolSetter {
		$this->callcontrolSetter = new CallcontrolSetter($this->client, 'sessionNotifyRinging');
		return $this->callcontrolSetter;
	}

	public function sessionGet(): CallcontrolGetter {
		$this->callcontrolGetter = new CallcontrolGetter($this->client, 'sessionGet');
		return $this->callcontrolGetter;
	}

	public function sessionList(): CallcontrolGetter {
		$this->callcontrolGetter = new CallcontrolGetter($this->client, 'sessionList');
		return $this->callcontrolGetter;
	}

}