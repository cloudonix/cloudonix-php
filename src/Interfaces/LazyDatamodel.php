<?php
/**
 *  ██████╗██╗      ██████╗ ██╗   ██╗██████╗  ██████╗ ███╗   ██╗██╗██╗  ██╗
 * ██╔════╝██║     ██╔═══██╗██║   ██║██╔══██╗██╔═══██╗████╗  ██║██║╚██╗██╔╝
 * ██║     ██║     ██║   ██║██║   ██║██║  ██║██║   ██║██╔██╗ ██║██║ ╚███╔╝
 * ██║     ██║     ██║   ██║██║   ██║██║  ██║██║   ██║██║╚██╗██║██║ ██╔██╗
 * ╚██████╗███████╗╚██████╔╝╚██████╔╝██████╔╝╚██████╔╝██║ ╚████║██║██╔╝ ██╗
 *  ╚═════╝╚══════╝ ╚═════╝  ╚═════╝ ╚═════╝  ╚═════╝ ╚═╝  ╚═══╝╚═╝╚═╝  ╚═╝
 *
 * Project: cloudonix-php | LazyDatamodel.php
 * Creator: Nir Simionovich <nirs@cloudonix.io> | 2019-06-29
 */

namespace Cloudonix;

interface LazyDatamodel
{
	/**
	 * Create an object in the data model
	 * @return mixed
	 */
	public function create();

	/**
	 * Update an object in the data model
	 * @return mixed
	 */
	public function update();

	/**
	 * Get an object from the data model
	 * @return mixed
	 */
	public function get();

	/**
	 * Delete an object from the data model
	 * @return mixed
	 */
	public function delete();

	/**
	 * Create a new API key in the data model
	 * @return mixed
	 */
	public function createApikey();

	/**
	 * Update an existing API key object in the data model
	 * @return mixed
	 */
	public function updateApikey();

	/**
	 * Delete an existing API key object from the data model
	 * @return mixed
	 */
	public function deleteApikey();

	/**
	 * Get a list of currently available API keys in the data model
	 * @return mixed
	 */
	public function getApikeys();
}