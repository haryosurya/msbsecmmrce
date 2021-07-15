<?php
/*************************************************
## Bacola Shop View Grid-List
*************************************************/ 
function bacola_shop_view(){
	$getview  = isset( $_GET['shop_view'] ) ? $_GET['shop_view'] : '';
	if($getview){
		return $getview;
	}
}

