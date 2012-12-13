<?php

namespace MODL\GivingImpact\Model;
use MODL\GivingImpact\Exception as GIException;

class Stats extends \MODL\GivingImpact\Model {

    public $date = false;
    public $donation_count = false;
    public $donation_total = false;
    public $facebook_shares = false;
    public $twitter_shares = false;

    private $stack = array();

	public function __construct($c, $data = false) {
		$this->container = $c;

		if( $data ) {
		    $this->assign($data);
		}
	}

	public function fetch() {
		$rc = $this->container->restClient;

        if( $this->campaign_token ) {
            $rc->url = sprintf(
                '%s/v2/campaigns/%s/stats/log',
                $rc->url,
                $this->campaign_token
            );
        } else {
            $rc->url = sprintf(
                '%s/v2/opportunities/%s/stats/log',
                $rc->url,
                $this->opportunity_token
            );

        }

		$data = $rc->get($this->properties);
		$out = array();

		foreach( $data->stats as $d ) {
		    $out[] = new $this($this->container, $d);
		}

		return $out;
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
