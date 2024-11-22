<?php

class WC_Product_Listing_Booking extends WC_Product {

    public function get_type() {
        return 'listing_booking';
    }

    public function is_sold_individually() {
		return true;
	}

    public function is_purchasable() {
		return true;
	}

    public function is_virtual() {
		return true;
	}

    public function is_visible() {
		return false;
	}

}
