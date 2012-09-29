<?php

	class PostalAddress{

		public $streetAddress;

		public $addressLocality;

		public $addressRegion;

		public $postalCode;

		public $id;		

		public function __construct(){

			$this->streetAddress='';

			$this->addressLocality='';

			$this->addressRegion='';

			$this->id= rand();

		}

	}

	

	class AggregateOffer{

		public $lowPrice;

		public $highPrice;

		public $id;

		public function __construct(){

			$this->lowPrice= '';

			$this->id= rand();

		}

	}

	

	class AggregateRating{

		public $reviewCount;

		public $rating;

		public $id;

		public function __construct(){

			$this->reviewCount= '';			

			$this->rating= new Rating();

			$this->id= rand();

		}

	}

	

	class Offer{

		public $price;

		public $availability;

		public $aggregateOffer;

		public $id;

		public function __construct(){

			$this->price= '';

			$this->availability= '';

			$this->aggregateOffer= new AggregateOffer;

			$this->id= rand();

		}

	}

	

	class Rating{

		public $ratingValue;

		public $bestRating;

		public $worstRating;

		public $id;

		public function __construct(){

			$this->ratingValue= '';

			$this->bestRating= '';

			$this->worstRating= '';			

			$this->id= rand();

		}

	}

?>