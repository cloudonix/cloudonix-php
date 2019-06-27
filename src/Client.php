<?php
/**
 *  ██████╗██╗      ██████╗ ██╗   ██╗██████╗  ██████╗ ███╗   ██╗██╗██╗  ██╗
 * ██╔════╝██║     ██╔═══██╗██║   ██║██╔══██╗██╔═══██╗████╗  ██║██║╚██╗██╔╝
 * ██║     ██║     ██║   ██║██║   ██║██║  ██║██║   ██║██╔██╗ ██║██║ ╚███╔╝
 * ██║     ██║     ██║   ██║██║   ██║██║  ██║██║   ██║██║╚██╗██║██║ ██╔██╗
 * ╚██████╗███████╗╚██████╔╝╚██████╔╝██████╔╝╚██████╔╝██║ ╚████║██║██╔╝ ██╗
 *  ╚═════╝╚══════╝ ╚═════╝  ╚═════╝ ╚═════╝  ╚═════╝ ╚═╝  ╚═══╝╚═╝╚═╝  ╚═╝
 *
 * Project: cloudonix-php | Client.php
 * Creator: nirs | 2019-06-26
 */


namespace Cloudonix;

use Exception;
use Opis\Cache\Drivers\File;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ServerException as GuzzleServerException;
use GuzzleHttp\Exception\ClientException as GuzzleClientException;

/**
 * Cloudonix API.Core Client - Command and Control REST Client
 *
 * @package Cloudonix
 */
class Client
{
	/** @var string Directory to store cache files, defaults to `/tmp` */
	public $cacheDirectory = '/tmp';

	/** @var object The Cache Manager Object */
	public $cacheHandler;

	/** @var string The Cloudonix API.Core Endpoint URL */
	public $httpEndpoint = 'https://api.cloudonix.io';

	/** @var string The library identification string */
	public $httpClientIdent = 'cloudonix-php library 0.1';

	/** @var object Previously initiated Cloudonix\Client Object */
	public $handler;

	/** @var object Guzzle HTTP Client Connector */
	public $httpConnector;

	/** @var array HTTP Headers to be used with all Guzzle HTTP Client requests */
	public $httpHeaders;

	public $apikey;
	public $tenantName;
	public $tenantId;

	protected $tenantsInterface;
	protected $domainsInterface;
	protected $applicationsInterface;
	protected $subscribersInterface;
	protected $trunksInterface;

	/**
	 * Client constructor.
	 * @param string $cacheDirectory A designated Cache Memory directory - default '/tmp'
	 * @param string $httpEndpoint An alternative Cloudonix API Endpoint - default 'https://api.cloudonix.io'
	 * @throws Exception In case of library init error
	 */
	public function __construct($apikey = null, $httpEndpoint = null, $cacheDirectory = null)
	{
		try {

			$this->httpEndpoint = (($httpEndpoint != null) && (strlen($httpEndpoint))) ? $httpEndpoint : $this->httpEndpoint;
			$this->cacheDirectory = (($cacheDirectory != null) && (strlen($cacheDirectory))) ? $cacheDirectory : sys_get_temp_dir();
			$this->cacheHandler = new File($this->cacheDirectory);

			$mySanityCheckValue = uniqid("", TRUE);
			$this->cacheHandler->write('mySanityValue', $mySanityCheckValue);
			$mySanityReadValue = $this->cacheHandler->read('mySanityValue');
			if ($mySanityCheckValue != $mySanityReadValue)
				throw new Exception('Cache engine not properly working, bailing out', 500);
			$this->cacheHandler->clear();

			$this->init($apikey);

		} catch (Exception $e) {
			die($e->getMessage() . '  code: ' . $e->getCode());
		}
	}

	/**
	 * Client Destructor
	 */
	public function __destruct()
	{
		$this->cacheHandler->clear();
	}

	/**
	 * Initialise the Guzzle HTTP Client
	 */
	public function init($apikey)
	{

		$this->apikey = $apikey;

		$this->httpConnector = new GuzzleClient([
			'base_uri' => $this->httpEndpoint,
			'timeout' => 2.0
		]);

		$this->httpHeaders = [
			'Authorization' => 'Bearer ' . $apikey,
			'User-Agent' => $this->httpClientIdent
		];

	}

	public function tenants() {
		if (!$this->tenantsInterface) {
			$this->tenantsInterface = new Tenants($this);
		}
		return $this->tenantsInterface;
	}

	public function domains() {
		if (!$this->domainsInterface) {
			$this->domainsInterface = new Domains($this);
		}
		return $this->domainsInterface;
	}

	public function applications() {
		if (!$this->applicationsInterface) {
			$this->applicationsInterface = new Applications($this);
		}
		return $this->applicationsInterface;
	}

	public function httpRequest($method, $request, $data = null)
	{
		try {
			if ($data != null)
				$this->httpHeaders['Content-Type'] = "application/json";

			switch (strtoupper($method)) {
				case "POST":
					if ($data != null)
						$requestData = ['headers' => $this->httpHeaders, 'json' => $data];
					else
						$requestData = ['headers' => $this->httpHeaders];
					$result = $this->httpConnector->request('POST', $request, $requestData);
					break;
				case "GET":
					$requestData = ['headers' => $this->httpHeaders];
					$result = $this->httpConnector->request('GET', $request, $requestData);
					break;
				case "DELETE":
					$requestData = ['headers' => $this->httpHeaders];
					$result = $this->httpConnector->request('DELETE', $request, $requestData);
					break;
				case "PUT":
					if ($data != null)
						$requestData = ['headers' => $this->httpHeaders, 'json' => $data];
					else
						$requestData = ['headers' => $this->httpHeaders];
					$result = $this->httpConnector->request('PUT', $request, $requestData);
					break;
				case "PATCH":
					if ($data != null)
						$requestData = ['headers' => $this->httpHeaders, 'json' => $data];
					else
						$requestData = ['headers' => $this->httpHeaders];
					$result = $this->httpConnector->request('PATCH', $request, $requestData);
					break;
				default:
					throw new Exception('HTTP Method request not allowed', 500, null);
					break;
			}

			return $result;

		} catch (GuzzleServerException $e) {
			die($e->getMessage() . '  code: ' . $e->getCode());
		} catch (GuzzleClientException $e) {
			die($e->getMessage() . '  code: ' . $e->getCode());
		} catch (Exception $e) {
			die($e->getMessage() . '  code: ' . $e->getCode());
		}
	}


	public function getSelf() {
		try {

			$mySelfKeyResult = $this->httpRequest('GET', 'keys/self');
			$myTenantData = json_decode((string)$mySelfKeyResult->getBody());

			/* Store Tenant Information to Cache */
			$this->cacheHandler->write($this->apikey . '-cxTenantId', $myTenantData->tenantId);
			$this->cacheHandler->write($this->apikey . '-cxTenantName', $myTenantData->name);
			$this->cacheHandler->write($this->apikey . '-cxTenantApikey', $myTenantData->keyId);
			$this->cacheHandler->write($this->apikey . '-cxTenantApiSecret', $myTenantData->secret);

			$this->tenantName = $myTenantData->name;
			$this->tenantId = $myTenantData->tenantId;

			$result = [
				'tenant-name' => $this->tenantName,
				'tenant-id' => $this->tenantId,
				'datamodel' => $myTenantData
			];

			return $result;

		} catch (ServerException $e) {
			die($e->getMessage() . '  code: ' . $e->getCode());
		} catch (ClientException $e) {
			die($e->getMessage() . '  code: ' . $e->getCode());
		} catch (Exception $e) {
			die($e->getMessage() . '  code: ' . $e->getCode());
		}
	}
}