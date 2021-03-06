<?php
/**
 *  ██████╗██╗      ██████╗ ██╗   ██╗██████╗  ██████╗ ███╗   ██╗██╗██╗  ██╗
 * ██╔════╝██║     ██╔═══██╗██║   ██║██╔══██╗██╔═══██╗████╗  ██║██║╚██╗██╔╝
 * ██║     ██║     ██║   ██║██║   ██║██║  ██║██║   ██║██╔██╗ ██║██║ ╚███╔╝
 * ██║     ██║     ██║   ██║██║   ██║██║  ██║██║   ██║██║╚██╗██║██║ ██╔██╗
 * ╚██████╗███████╗╚██████╔╝╚██████╔╝██████╔╝╚██████╔╝██║ ╚████║██║██╔╝ ██╗
 *  ╚═════╝╚══════╝ ╚═════╝  ╚═════╝ ╚═════╝  ╚═════╝ ╚═╝  ╚═══╝╚═╝╚═╝  ╚═╝
 *
 * Project: cloudonix-php | DnidsGetter.php
 * Creator: Nir Simionovich <nirs@cloudonix.io> | 2019-06-29
 */

namespace Cloudonix\Helpers;

use Exception;
use Cloudonix\Client as Client;
use Cloudonix\Exceptions\DatamodelBuilderException as DatamodelBuilderException;
use Cloudonix\Exceptions\MissingDomainIdException;

class DnidGetter
{
	public $baseFilter;
	public $baseQuery = false;
	public $domainId = false;
	public $dnids;
	public $client;
	public $name;
	public $id;

	public function __construct(Client $client)
	{
		if (!$client)
			throw new DatamodelBuilderException('Datamodel Helper construction error', 500, null);

		$this->client = $client;
		$this->baseFilter = "?";
	}

	public function byApplication($application)
	{
		$this->baseQuery .= '/applications/' . $application;
		return $this;
	}

	public function byApplicationId($applicationId)
	{
		return $this->byApplicationId($applicationId);
	}

	public function byActive($active)
	{
		$this->baseFilter .= 'by_active=' . (int)$active . '&';
		return $this;
	}

	public function byPrefix($prefix)
	{
		$this->baseFilter .= 'by_prefix=' . $prefix . '&';
		return $this;
	}

	public function byAsteriskPrefix($asteriskPrefix)
	{
		$this->baseFilter .= 'by_asterisk_compatible=' . $asteriskPrefix . '&';
		return $this;
	}

	public function byDomainId($domainId)
	{
		$this->domainId = $domainId;
		$this->baseQuery = '/domains/' . $this->domainId;
		return $this;
	}

	public function run()
	{
		if ((!$this->domainId) || (!$this->baseQuery))
			throw new MissingDomainIdException('`byDomainId` MUST be called before `run`', 500, null);

		$result = $this->client->httpRequest('GET', $this->baseQuery . '/dnids' . $this->baseFilter);
		return $result;
	}

}