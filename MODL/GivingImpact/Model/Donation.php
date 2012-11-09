<?php

namespace MODL\GivingImpact\Model;
use MODL\GivingImpact\Exception as GIException;

class Donation extends \MODL\GivingImpact\Model {

    public $first_name = false;
    public $last_name = false;
    public $billing_address1 = false;
    public $billing_city = false;
    public $billing_state = false;
    public $billing_postal_code = false;
    public $billing_country = false;
    public $individual_total = false;
    public $donation_level = false;
    public $contact = false;
    public $email_address = false;
    public $referrer = false;
    public $offline = false;
    public $created_at = false;
    public $twitter_share = false;
    public $fb_share = false;
    public $campaign = false;
    public $opportunity = false;
    public $custom_responses = false;

	protected $path = false;

    private $stack = array();

    private $campaign_token;
    private $opportunity_token;

	public function __construct($c, $data = false) {
		$this->container = $c;

		if( $data ) {
		    $this->assign($data);
		}
	}

	public function fetch($token = false) {
		if( $token ) {
            return false;
		}

		$rc = $this->container->restClient;

        if( !$this->campaign_token && !$this->opportunity_token ) {
            $rc->url = sprintf(
                '%s/v2/donations',
                $rc->url
            );
        } elseif( $this->campaign_token ) {
            $rc->url = sprintf(
                '%s/v2/campaigns/%s/donations',
                $rc->url,
                $this->campaign_token
            );
        } else {
            $rc->url = sprintf(
                '%s/v2/opportunities/%s/donations',
                $rc->url,
                $this->opportunity_token
            );

        }

		$data = $rc->get($this->properties);
		$out = array();

		foreach( $data->donations as $d ) {
		    $out[] = new $this($this->container, $d);
		}

		return $out;
	}

    public function create($data) {

        if( !is_array($data) ) {
            throw new GIException('Expected array');
            return;
        }

        if( !array_key_exists('campaign', $data) && !array_key_exists('opportunity', $data) ) {
            throw new GIException('No parent campaign found');
            return;
        }

        $rc = $this->container->restClient;
        $rc->url = sprintf(
            '%s/v2/donations',
            $rc->url
        );

        $return = $rc->post($data);

        return new $this($this->container, $return->donation);
    }

	public function campaign($token) {
	    $this->campaign_token = $token;

	    return $this;
	}

	public function opportunity($token) {
	    $this->opportunity_token = $token;

	    return $this;
	}

    public function __get($k) {
        $f = sprintf('__%s', $k);
        if( is_callable(array($this, $f)) ) {
            return call_user_func(array($this, $f));
        }
    }
}
