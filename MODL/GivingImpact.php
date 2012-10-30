<?php
/**
 * Giving Impact PHP Library
 *
 * Simple, fluent interface to the Giving Impact API
 *
 * @package        	GivingImpact
 * @subpackage    	Libraries
 * @category    	Libraries
 * @author        	Minds on Design Lab, Inc.
 * @created			10/01/2012
 * @license         #
 */

namespace MODL;
use MODL\GivingImpact\Exception as GIException;
use MODL\GivingImpact\RestClient as RestClient;
use MODL\GivingImpact\Model\Campaign as Campaign;
use MODL\GivingImpact\Model\Opportunity as Opportunity;
use MODL\GivingImpact\Model\Donation as Donation;

require_once dirname(__FILE__).'/GivingImpact/Exception.php';
require_once dirname(__FILE__).'/GivingImpact/RestClient.php';
require_once dirname(__FILE__).'/GivingImpact/Model.php';

require_once dirname(__FILE__).'/GivingImpact/Model/Campaign.php';
require_once dirname(__FILE__).'/GivingImpact/Model/Opportunity.php';
require_once dirname(__FILE__).'/GivingImpact/Model/Donation.php';

class GivingImpact {

	protected $components = array(
		'end_point'		=> 'givingimpact.com/api',
		'user_agent'	=> 'Test UA',
		'api_key'		=> false,
	);

	public function __construct($user_agent, $api_key) {

		$this->user_agent = $user_agent;
		$this->api_key = $api_key;

		if( !function_exists('curl_init') ) {
			throw new GIException('CURL extension is required');
			return;
		}

		$v = curl_version();
		if( !($v['features'] & CURL_VERSION_SSL) ) {
			throw new GIException('SSL is required');
			return;
		}

		$this->initialize();
	}

	public function initialize() {

		// build and return new rest client with proper settings
		$this->restClient = function($_) {
			$rc = new RestClient($_);
			$rc->headers = array(
				'X-GI-Authorization: '.$_->api_key,
				'Content-Type: application/json'
			);

			$rc->url = $_->end_point;
			$rc->user_agent = $_->user_agent;

			return $rc;
		};

		$this->campaign = function($_) {
			return new Campaign($_);
		};
		$this->opportunity = function($_) {
			return new Opportunity($_);
		};
		$this->donation = function($_) {
			return new Donation($_);
		};

	}

	public function __get($k) {
		if( is_callable($this->components[$k]) ) {
			return $this->components[$k]($this);
		}

		return $this->components[$k];
	}

	public function __set($k, $v) {
		$this->components[$k] = $v;
	}

}
