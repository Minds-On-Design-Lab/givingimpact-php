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

    $gi = new \MODL\GivingImpact('MY-APPLICATION-NAME', 'MY_PRIVATE_KEY');

## Available Methods

The following is a list of available methods; however, for full details about each method, please refer to the [API Documentation](http://givingimpact.com/docs).

 * campaigns
   * create new
   * fetch single
   * fetch multiple
   * fetch related opportunities
   * fetch related donations
   * fetch related statistic logs
 * opportunities
   * create new
   * fetch single
   * fetch multiple
   * fetch related donations
   * fetch related statistic logs
 * donations
   * create offline
 * custom checkout

## Docs

Keep in mind, the Giving Impact API Library uses a fluent interface, so you can easily chain your methods together.

### Common

For a full list of all Campaign, Opportunity, Donation or Stat properties, please refer to the [API Documentation](http://givingimpact.com/docs).

Campaigns, Opportunities, Donations and Shares all share the following methods

**limit(int limit)**

Limit the number of results returned

    $campaigns = $gi->campaign
        ->limit(5);

**offset(int offset)**

Skip a number of records, useful in combination with `limit` for pagination

    $campaigns = $gi->campaign
        ->limit(10)
        ->offset(5);

**sort(string sort_by)**

Sort the returned results. By default will sort by the provided property ascending. You can change the sort direction by appending `|desc` to the end of the property.

    $campaigns = $gi->campaign
        ->sort('created_at');  // Sorts by created time ASCENDING

    $campaigns = $gi->campaign
        ->sort('created_at|desc'); // Sorts by created time DESCENDING

**status(boolean stats)**

Get only campaigns/opportunites of a particular status. By default the API returns only items with a status of `active`. Can be `active`, `inactive` or `both`.

    $campaigns = $gi->campaign
        ->status('active');

**fetch([string token])**

This is the last method you call in any query. This tells the library to construct the full URI and query the API. Once you call this method all the other filters will be reset.

    $campaigns = $gi->campaign
        ->status('both')
        ->limit(10)
        ->fetch();

    foreach( $campaigns as $campaign ) {
        echo $campaign->title;
    }

Passing a Campaign, Opportunity or Donation token (depending on the model) will return a specific campaign, opportunity or donation.

    $campaign = $gi->campaign
        ->fetch('123456');

    $donation = $gi->donation
        ->fetch('987654');

### Campaigns

Specific methods for Campaigns

**save()**

Create a new campaign. Simply set the properties of a new campaign object to any combination of the following:

 * title **REQUIRED**
 * description **REQUIRED**
 * youtube_id
 * donation_target **REQUIRED**
 * hash_tag
 * status
 * has_giving_opportunities
 * display_donation_target
 * display_donation_total
 * enable_donation_levels
 * donation_levels
 * custom_fields
 * header_font
 * campaign_color
 * donation_minimum
 * analytics_id
 * image_type
 * image_file
 * receipt
   * send_receipt
   * email_org_name
   * reply_to_address
   * bcc_address
   * street_address
   * street_address_2
   * city
   * state
   * postal_code
   * country
   * receipt_body

For example

    $newCampaign = $gi->campaign;
    $newCampaign->title             = 'My campaign';
    $newCampaign->description       = 'Check out my campaign';
    $newCampaign->donation_target   = '1000';
    $newCampaign->image_type        = 'image/jpg';
    $newCampaign->image_file        = '[BASE64 ENCODED STRING]';
    $newCampaign->receipt           = array
        'send_receipt'      => TRUE,
        'email_org_name'    => 'My Org'
    )
    $newCampaign->save();

**opportunities**

Fetch Opportunities for this Campaign. Note, this is called is a property, not a method

    $campaign = $gi->campaign
        ->fetch('ABC123');

    $opportunities = $campaign
        ->opportunities
        ->limit(10)
        ->fetch();

**donations**

Fetch Donations for this Campaign. Note, this is called is a property, not a method

    $campaign = $gi->campaign
        ->fetch('ABC123');

    $donations = $campaign
        ->donations
        ->limit(10)
        ->fetch();

**stats**

Fetch Stats for this Campaign. Note, this is called is a property, not a method

    $campaign = $gi->campaign
        ->fetch('ABC123');

    $stats = $campaign
        ->stats
        ->limit(4)
        ->fetch();

### Opportunities

Specific methods for Opportuniites

**save()**

Create a new opportunity. Simply set the properties of a new opportunity object to any combination of the following:

  * campaign_token **REQURED**
  * title **REQUIRED**
  * description **REQUIRED**
  * youtube_id
  * donation_target **REQUIRED**
  * hash_tag
  * status
  * image_url
  * campaign
  * image_type
  * image_file

For example

    $newOpportunity = $gi->opportunity;
    $newOpportunity->title             = 'My new giving opp';
    $newOpportunity->description       = 'Check out my opportunity';
    $newOpportunity->donation_target   = '1000';
    $newOpportunity->save();

**campaign**

Fetch this Opportunity's parent Campaign. Note, this is called is a property, not a method

    $campaign = $gi->opportunity
        ->fetch('ABC123')
        ->campaign;

**donations**

Fetch Donations for this Opportunity. Note, this is called is a property, not a method

    $opportunity = $gi->opportunity
        ->fetch('ABC123');

    $donations = $opportunity
        ->donations
        ->limit(10)
        ->fetch();

**stats**

Fetch Stats for this Opportunity. Note, this is called is a property, not a method

    $opportunity = $gi->opportunity
        ->fetch('ABC123');

    $stats = $opportunity
        ->stats
        ->limit(4)
        ->fetch();

### Donations

Specific methods for Donations

**save()**

Create a new **offline** donation.

  * first_name
  * last_name
  * billing_address1
  * billing_city
  * billing_state
  * billing_postal_code
  * billing_country
  * donation_total **Must be 5 or the campaign/opportunity minimum donation amount**
  * contact
  * email_address
  * offline
  * campaign **REQUIRED**
  * opportunity **OR REQUIRED**
  * donation_date

For example

    $newDonation = $gi->donation;
    $newDonation->first_name      = 'Test';
    $newDonation->last_name       = 'Testerson';
    $newDonation->offline         = TRUE;
    $newDonation->donation_total  = 25;
    $newDonation->donation_date   = time();
    $newDonation->save();

**campaign**

Fetch this Donations's parent Campaign. Note, this is called is a property, not a method

    $campaign = $gi->donation
        ->fetch('ABC123')
        ->campaign;

**opportunity**

Fetch this Donations's parent Opportunity, if it exists. Note, this is called is a property, not a method

    $campaign = $gi->donation
        ->fetch('ABC123')
        ->opportunity;

### Stats

Fetch Statistics for a Campaign or Opportunity

**campaign**

Fetch this Stat log's parent Campaign. Note, this is called is a property, not a method

**opportunity**

Fetch this Stat log's parent Opportunity, if it exists. Note, this is called is a property, not a method

## Example

    require_once "givingimpact-php/MODL/GivingImpact.php";

    $gi = new \MODL\GivingImpact('MY-APPLICATION-NAME', 'MY_PRIVATE_KEY');

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


* 1.0 2013-08-15 - Added support for custom campaign fields
* 1.0 - Initial Release
