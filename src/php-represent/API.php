<?php

namespace PHPRepresent;

use PHPRepresent\Interfaces\APIInterface;

class API implements APIInterface{

	public $apiUrl = 'https://represent.opennorth.ca';

	public function setRateLimit() {
		// TODO: Implement setRateLimit() method.
	}

	public function get( $path, array $params, $throttle = true ) {
		// TODO: Implement get() method.
	}

	public function getAll( $path, array $params ) {
		// TODO: Implement getAll() method.
	}

	public function postcodes( $postcode = null ) {
		// TODO: Implement postcodes() method.
	}

	public function boundarySets( $name = null, array $params ) {
		// TODO: Implement boundarySets() method.
	}

	public function boundaries( $boundarySet = null, $name = null, $representatives = false, array $params ) {
		// TODO: Implement boundaries() method.
	}

	public function representativeSets( $set = null ) {
		// TODO: Implement representativeSets() method.
	}

	public function representatives( $set = null, array $params ) {
		// TODO: Implement representatives() method.
	}

	public function elections( $set = null ) {
		// TODO: Implement elections() method.
	}

	public function candidates( $set = null, array $params ) {
		// TODO: Implement candidates() method.
	}
}