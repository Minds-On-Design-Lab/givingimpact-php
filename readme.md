givingimpact-php
=========================

## Overview

A PHP library to interact with Giving Impact&trade;. Giving Impact is an online fundraising platform driven by a thoughtful API to allow designers and developers to deliver customized online donation experiences for Non-profits easily, affordable, and flexibly.

For more about Giving Impact and to view our full documentation and learning reasources please visit [givingimpact.com](http://givingimpact.com)


## Library Credits

**Developed By:** Minds On Design Lab - http://mod-lab.com<br />
**Version:** 1.0<br />
**Copyright:** 2012 - 2013 Minds On Design Lab<br />
**License:** Licensed under the MIT license - Please refer to LICENSE<br />

## Requirements

* PHP 5.3+
* PHP cURL with SSL support

**Note:** This library will not work in PHP 5.2 and below.

## Configuration

You will need a valid Giving Impact Account API key, accessible from the Account Settings area. 
    
First, add the library.

    require_once "givingimpact-php/MODL/GivingImpact.php";

Second, create your namespace and set both a name for your site/application followed by your Giving Impact API key.

    $gi = new \MODL\GivingImpact('MY-APPLICATION-NAME', 'MY_KEY');

## Available Methods

The following is a list of available methods; however, for full details about each method, please refer to the [API Documentation](http://givingimpact.com/docs).

## Docs

Coming Soon

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