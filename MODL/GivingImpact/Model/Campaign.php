<?php

namespace MODL\GivingImpact\Model;
use MODL\GivingImpact\Exception as GIException;

class Campaign extends \MODL\GivingImpact\Model {

    public $id_token = false;
    public $title = false;
    public $description = false;
    public $youtube_id = false;
    public $donation_total = false;
    public $donation_target = false;
    public $givlink = false;
    public $donation_url = false;
    public $share_url = false;
    public $shares_fb = false;
    public $shares_twitter = false;
    public $hash_tag = false;
    public $status = false;
    public $has_giving_opportunities = false;
    public $display_total = false;
    public $display_current = false;
    public $has_campaign_levels = false;
    public $campaign_levels = false;
    public $image_url = false;
    public $custom_fields = false;
    public $widget = false;
    public $donation_minimum = false;
    public $send_receipt = false;
    public $email_org_name = false;
    public $reply_to_address = false;
    public $bcc_address = false;
    public $street_address = false;
    public $street_address_2 = false;
    public $city = false;
    public $state = false;
    public $postal_code = false;
    public $country = false;
    public $receipt = false;

	protected $path = 'v2/campaigns';

    private $stack = array();

	public function __construct($c, $data = false) {
		$this->container = $c;

		if( $data ) {
		    $this->assign($data);
		}
	}

    public function create($data) {

        if( !is_array($data) ) {
            throw new GIException('Expected array');
            return;
        }

        $rc = $this->container->restClient;
        $rc->url = sprintf(
            '%s/%s', $rc->url, $this->path
        );

        $return = $rc->post($data);

        return new $this($this->container, $return->campaign);
    }

    public function save() {
        if( !$this->id_token ) {
            throw new GIException('Please use create method');
            return;
        }

        $data = array();
        foreach( $this->publicProperties() as $prop ) {
            $data[$prop] = $this->$prop;
        }

        $rc = $this->container->restClient;
        $rc->url = sprintf(
            '%s/%s/%s', $rc->url, $this->path, $this->id_token
        );

        $return = $rc->post($data);

        return new $this($this->container, $return->campaign);
    }

	public function fetch($token = false) {
		if( $token ) {
			$data = parent::fetch($token);
            return new $this($this->container, $data->campaign);
		}

		$rc = $this->container->restClient;
		$rc->url = $rc->url.'/'.$this->path;

		$data = $rc->get($this->properties);
		$out = array();
		foreach( $data->campaigns as $c ) {
		    $out[] = new $this($this->container, $c);
		}

		return $out;
	}

    public function __get($k) {
        $f = sprintf('__%s', $k);
        if( is_callable(array($this, $f)) ) {
            return call_user_func(array($this, $f));
        }
    }

    public function __opportunities() {
        if( !$this->id_token ) {
            return false;
        }

        if( array_key_exists('opps', $this->stack) ) {
            return $this->stack['opps'];
        }

        $opps = $this->container->opportunity
            ->campaign($this->id_token);

        $this->stack['opps'] = $opps;

        return $opps;
    }

    public function __donations() {
        if( !$this->id_token ) {
            return false;
        }

        if( array_key_exists('donations', $this->stack) ) {
            return $this->stack['donations'];
        }

        $donations = $this->container->donation
            ->campaign($this->id_token);

        $this->stack['donations'] = $donations;

        return $donations;
    }

}
