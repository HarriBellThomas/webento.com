<?php

	include('classes.php');

	class Person{

		public $name;

		public $image;

		public $jobTitle;

		public $workLocation;

		public $telephone;

		public $email;

		public $url;

		public $address;

		public $id;

  	    public function __construct(){

			$this->name = '';

			$this->image = '';

			$this->jobTitle = '';

			$this->workLocation = '';

			$this->telephone = '';

			$this->email = '';

			$this->url = '';

			$this->address = new PostalAddress();	

			$this->id= rand();

		}



		public function microdata(){

			$microdata = '<div class="optimum7-microdata person" itemscope itemtype="http://schema.org/Person">';

				  if ($this->name)

				  $microdata .='<h2 class="name" itemprop="name">'.$this->name.'</h2>';

 				  if ($this->image)

				  $microdata .= '<img class="bio-img" src="'.$this->image.'" itemprop="image" /><br>';			

				  if ($this->jobTitle)

				  $microdata .= '<span class="job-title" itemprop="jobTitle"><b>'.$this->jobTitle.'</b></span><br>';

				  if ($this->workLocation)

				  $microdata .= '<span class="work-location" itemprop="workLocation">'.$this->workLocation.'</span><br>';

				   if ($this->address->streetAddress || $this->address->addressLocality || $this->address->addressRegion){ 

					   $microdata .= '<div class="address" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">';

					   if ($this->address->streetAddress) $microdata .= '<span class="street-address" itemprop="streetAddress">'.$this->address->streetAddress.'</span><br>';

					   if ($this->address->addressLocality) $microdata .= ' <span class="address-locality" itemprop="addressLocality">'.$this->address->addressLocality.'</span>';

					   if ($this->address->addressRegion) $microdata .= ', <span class="address-region" itemprop="addressRegion">'.$this->address->addressRegion.'</span>';

					   if ($this->address->postalCode) $microdata .= ' <span class="address-region" itemprop="postalCode">'.$this->address->postalCode.'</span>';

					   $microdata .= '</div>';

				   }

				  if ($this->telephone)

				  $microdata .= '<span class="telephone" itemprop="telephone">'.$this->telephone.'</span><br>';

				  if ($this->email)

				  $microdata .= '<a class="email" href="mailto:'.$this->email.'" itemprop="email">'.$this->email.'</a><br>';

				  if ($this->url)

				  $microdata .= '<a class="url" href="'.$this->url.'" itemprop="url">'.$this->url.'</a>';

				  $microdata .= '</div>';

			 return $microdata;

		}

	}



	class Place{

		public $name;

		public $url;

		public $description;

		public $address;

		public $id;

		public function __construct(){

			$this->name= '';

			$this->url ='';

			$this->description ='';

			$this->address = new PostalAddress();

			$this->id= rand();

		}

		public function microdata(){

			$microdata = '<div class="optimum7-microdata place" itemscope itemtype="http://schema.org/Place">';

			 if ($this->name)

			 $microdata .= '<span class="name" itemprop="name"><b>'.$this->name.'</b></span><br>';

			 if ($this->description)

			 $microdata .= '<span class="description" itemprop="description"><i>'.$this->description.'</i></span><br>';

			 if ($this->address->streetAddress || $this->address->addressLocality || $this->address->addressRegion){ 

				 $microdata .= '<div class="address" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">';

				 if ($this->address->streetAddress) $microdata .= '<span class="street-address" itemprop="streetAddress">'.$this->address->streetAddress.'</span><br>';

				 if ($this->address->addressLocality) $microdata .= ' <span class="address-locality" itemprop="addressLocality">'.$this->address->addressLocality.'</span>';

				 if ($this->address->addressRegion) $microdata .= ', <span class="address-region" itemprop="addressRegion">'.$this->address->addressRegion.'</span>';

				 if ($this->address->postalCode) $microdata .= ' <span class="postal-code" itemprop="postalCode">'.$this->address->postalCode.'</span>';

				 $microdata .= '</div>';

			 }

			 return $microdata;

		}

	}



	class Review{

		public $name;

		public $author;

		public $datePublished;

		public $description;

		public $reviewRating;

		public $id;		

		public function __construct(){

			$this->name= '';

			$this->author= '';

			$this->datePublished= '';

			$this->description= '';

			$this->reviewRating=new Rating();

			$this->id= rand();

		}

		public function microdata(){

			$microdata .= '<div class="optimum7-microdata reviews" itemscope itemtype="http://schema.org/Review">';

			if ($this->name)

			$microdata .= '<span itemprop="name">'.$this->name.'</span> - by <span itemprop="author">'.$this->author.'</span><br>,	<meta itemprop="datePublished" content="'.$this->datePublished.'">'.$this->datePublished;

			if ($this->ratingValue)		

			$microdata .= '<div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">

					  <meta itemprop="worstRating" content = "'.$this->reviewRating->worstRating.'"/><span itemprop="ratingValue">'.$this->reviewRating->ratingValue.'</span>/<span itemprop="bestRating">'.$this->reviewRating->bestRating.'</span> stars</div>';

			if ($this->description)	

			$microdata .= '<span itemprop="description">'.$this->description.'</span>';

			$microdata .= '</div>';

			return $microdata;

		}

	}



	class Product{

		public $name;

		public $image;

		public $description;

		public $aggregateRating;

		public $offers;

		public $reviews;

		public $id;

		public function __construct(){

			$this->name= '';

			$this->image= '';

			$this->description= '';

			$this->aggregateRating = new  AggregateRating();

			$this->offers= new Offer();

			$this->reviews= new Review();

			$this->id= rand();

		}

		public function microdata(){

			$microdata .= '<div class="optimum7-microdata product" itemscope itemtype="http://schema.org/Product">';

				 if ($this->name)				 

				 $microdata .= '<span class="name" itemprop="name"><b>'.$this->name.'</b></span><br>';

				 if ($this->image)	

				 $microdata .= '<img class="data-img" itemprop="image" src="'.$this->image.'" alt="'.$this->image.'"/><br>';

				 if ($this->ratingValue)	

				 $microdata .= ' <div class="rating" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">

				   <b>Rated</b> <span itemprop="ratingValue">'.$this->aggregateRating->ratingValue.'</span>/<span itemprop="bestRating">'.$this->aggregateRating->bestRating.'</span><b> based on</b> <span itemprop="reviewCount">'.$this->aggregateRating->reviewCount.'</span> customer reviews</div>';				

				  if ($this->offers->price || $this->offers->availability)	

				  $microdata .= '<div class="offers" itemprop="offers" itemscope itemtype="http://schema.org/Offer">

					<span itemprop="price">'.$this->offers->price.'</span><br>

					<span itemprop="availability"/>'.$this->offers->availability.'</span>

				  </div>';

				  if ($this->description)

				   $microdata .= '<b>Product description:</b>

				  <span itemprop="description">'.$this->description.'</span><br>';

				  if ($this->reviews->name){

				  $microdata .= '<b>Customer reviews:</b>

				  <div class="reviews" itemprop="reviews" itemscope itemtype="http://schema.org/Review">

					<span itemprop="name">'.$this->reviews->name.'</span> - by <span itemprop="author">'.$this->reviews->author.'</span>, <meta itemprop="datePublished" content="'.$this->reviews->datePublished.'">'.$this->reviews->datePublished;

					if ($this->reviews->reviewRating->ratingValue)

					 $microdata .= '<div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">

					  <meta itemprop="worstRating" content = "'.$this->reviews->reviewRating->worstRating.'"><span itemprop="ratingValue">'.$this->reviews->reviewRating->ratingValue.'</span>/<span itemprop="bestRating">'.$this->reviews->reviewRating->bestRating.'</span>stars

					</div>';

					if ($this->reviews->reviewRating->description)

					 $microdata .= '<span itemprop="description">'.$this->reviews->reviewRating->description.'</span><br>';

				  $microdata .= ' </div>';

				  }

				 $microdata .= '</div>';

			 return $microdata;

		}

	}

	

	class Event{

		public $name;

		public $url;

		public $startDate;

		public $location;

		public $offers;

		public $id;

		public function __construct(){

			$this->name= '';

			$this->url='';

			$this->startDate='';

			$this->location = new Place();

			$this->offers = new Offer();

			$this->id= rand();

		}

		public function microdata(){

			$microdata .= '<div class="optimum7-microdata event" itemscope itemtype="http://schema.org/Event">

				  <a class="url" itemprop="url" href="'.$this->url.'"><span itemprop="name"> <b>'.$this->name.'</b> </span></a><br>

				  <meta class="start-date" itemprop="startDate" content="'.$this->startDate.'"><i> '.$this->startDate.'</i>

				  <div class="location" itemprop="location" itemscope itemtype="http://schema.org/Place">				

					<a class="location-url" itemprop="url" href="'.$this->location->url.'">'.$this->location->name.'</a>				

					<div class="addr" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">		

					  <span class="street-address" itemprop="streetAddress">'.$this->location->address->streetAddress.'</span>

					  <span class="address-locality" itemprop="addressLocality">'.$this->location->address->addressLocality.'</span>, <span class="address-region" itemprop="addressRegion">'.$this->location->address->addressRegion.'</span> <span class="postal-code" itemprop="postalCode">'.$this->location->address->postalCode.'</span>

					</div>

				  </div>

				 <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">

					<b>Price:</b> <span class="offer-price" itemprop="price">'.$this->offers->price.'</span><br>

					<b>Availability:</b> <span class="offer-availability" itemprop="availability">'.$this->offers->availability.'</span>

					  <div itemprop="aggregateOffer" itemscope itemtype="http://schema.org/AggregateOffer">

						 <meta itemprop="lowPrice" content = "'.$this->offers->aggregateOffer->lowPrice.'"/>

						 <meta itemprop="highPrice" content = "'.$this->offers->aggregateOffer->highPrice.'"/>

					</div>

				  </div>				

				</div>';

			 return $microdata;

		}

	}



	class Organization{

		public $name;

		public $url;

		public $telephone;

		public $faxNumber;

		public $email;

		public $address;

		public $id;		

		public function __construct(){

			$this->name='';

			$this->url = '';

			$this->telephone='';

			$this->faxNumber = '';			

			$this->email = '';

			$this->address=new PostalAddress();

			$this->id=rand();

		}

		public function microdata(){

			$microdata .= '<div class="optimum7-microdata organization" itemscope itemtype="http://schema.org/Organization">';

			if ($this->name)

			$microdata .= '<a class="url" itemprop="url" href="'.$this->url.'"><span class="name" itemprop="name">'.$this->name.'</span></a><br>';

			 if ($this->address->streetAddress || $this->address->addressLocality || $this->address->addressRegion){ 

				 $microdata .= '<div class="address" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">';

				 if ($this->address->streetAddress) $microdata .= '<span class="street-address" itemprop="streetAddress">'.$this->address->streetAddress.'</span><br>';

				 if ($this->address->addressLocality) $microdata .= ' <span class="address-locality" itemprop="addressLocality">'.$this->address->addressLocality.'</span>';

				 if ($this->address->addressRegion) $microdata .= ', <span class="address-region" itemprop="addressRegion">'.$this->address->addressRegion.'</span>';

				 if ($this->address->postalCode) $microdata .= ' <span class="address-region" itemprop="postalCode">'.$this->address->postalCode.'</span>';

				 $microdata .= '</div>';

			 }

			if ($this->telephone)

			$microdata .= '<b>Tel:</b> <span class="tel" itemprop="telephone">'.$this->telephone.'</span><br>';

			if ($this->faxNumber)

			$microdata .= '<b>Fax:</b> <span class="fax" itemprop="faxNumber">'.$this->faxNumber.'</span><br>';

			if ($this->email)

			$microdata .= '<b>E-mail:</b> <span class="email" itemprop="email">'.$this->email.'</span>';

			$microdata .= '</div>';

			return $microdata;

		}

	}

	

	class LocalBusiness{

		public $name;

		public $description;

		public $telephone;

		public $address;

		public $id;

		public function __construct(){

			$this->name = '';

			$this->description = '';

			$this->telephone = '';

			$this->address = new PostalAddress();

			$this->id= rand();

		}

		public function microdata(){

			$microdata = '<div class="optimum7-microdata place" itemscope itemtype="http://schema.org/LocalBusiness">';

			if ($this->name)

			$microdata .= '<span class="name" itemprop="name"><b>'.$this->name.'</b></span><br>';

			if ($this->description)

			$microdata .= '<span class="description" itemprop="description"><i>'.$this->description.'</i></span><br>';

		 	if ($this->address->streetAddress || $this->address->addressLocality || $this->address->addressRegion){ 

				 $microdata .= '<div class="address" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">';

				 if ($this->address->streetAddress) $microdata .= '<span class="street-address" itemprop="streetAddress">'.$this->address->streetAddress.'</span><br>';

				 if ($this->address->addressLocality) $microdata .= ' <span class="address-locality" itemprop="addressLocality">'.$this->address->addressLocality.'</span>';

				 if ($this->address->addressRegion) $microdata .= ', <span class="address-region" itemprop="addressRegion">'.$this->address->addressRegion.'</span>';

				 if ($this->address->postalCode) $microdata .= ' <span class="address-region" itemprop="postalCode">'.$this->address->postalCode.'</span>';

				 $microdata .= '</div>';

			}			

			if ($this->telephone)

			$microdata .='<span class="telephone" itemprop="telephone">'.$this->telephone.'</span>';

			$microdata .='</div>';

			return $microdata;

		}

	}

?>