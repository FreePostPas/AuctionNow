<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	* Config file for options relatives to AuctionNow.
	* Do not forget to edit information about connection to database into database.php
*/

/*
	* Min_level_to_bid define the level required to perform a bid or an immediat buy
	* When a character is under the minimum level, he can only see offers
	* Notice: you can set a value under the server limit, but the server side part will not valid the bid or buy will not
	* be valided (try to be alway at least equals to the server value, this type of exception is not truly catched)
*/
$config['min_level_to_bid'] = 10;

$config['soap_adress'] = '127.0.0.1';
$config['soap_port'] = '7878'; //On prod server, may be you should set an other port
$config['soap_username'] = 'admin';
$config['soap_password'] = 'admin';


/* End of file auctionnow.php */
/* Location: ./application/config/auctionnow.php */