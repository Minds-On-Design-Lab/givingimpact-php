givingimpact-php
=========================

## Overview

**Developed By:** Minds On Design Lab - http://mod-lab.com<br />
**Version:** 1.0<br />
**Copyright:** 2012 Minds On Design Lab<br />
**License:** Licensed under the MIT license - Please refer to LICENSE<br />

## Requirements

* PHP 5.3+
* PHP cURL with SSL support

**Note:** This library will not work in PHP 5.2 and below.

## Example

    require_once "givingimpact-php/MODL/GivingImpact.php";

    $gi = new \MODL\GivingImpact('MY-APPLICATION-NAME', 'MY_KEY');

    $campaigns = $gi->campaign
        ->limit(10)
        ->fetch();

    $opportunities = $campaigns[3]->opportunities
        ->fetch();

    foreach( $opportunities as $opportunity ) {
        $donations = $opportunity->donations
            ->limit(5)
            ->fetch();

        foreach( $donations as $d ) {
            echo $d->first_name.' '.$d->last_name.'<br />';
        }
    }

You can then copy the resulting files to your CodeIgniter application's `application/libraries` directory.

## Changelog

* 1.0 - Initial Release