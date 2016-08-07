<?php 

function  handleParticleLoopArr($particle_settings, $particle_default,$prop, $min = null, $max = null,$step = null, $isColor = null,$hasPreFactor = null,$preFactor = null){
	$_min = "";$_max = "";$_step = "";$disableState = "";$exception="";$notice="";
	if(!is_null($min) ){$_min = sprintf('min="%d"',$min);}
	if(!is_null($max) ){$_max = sprintf('max="%d"',$max);}
	if(!is_null($step) ){$_step= sprintf('step="%s"',(string)$step);}
	if($preFactor){
		$preFactorToString = $preFactor;//*
	}
	if($hasPreFactor){
		$retrieve_palette_pre_factor = get_option($particle_settings[$preFactor]);
		if($retrieve_palette_pre_factor != 1){
			$disableState = "disabled";
			$notice = '<p>'.__(sprintf("You must enable %s above",$preFactorToString)).'</p>';
		}
	}
	$retrieve_palette_prop = get_option($particle_settings[$prop]);
	$value = !empty($retrieve_palette_prop) ? $retrieve_palette_prop : $particle_default[$particle_settings[$prop]]; 
	if($isColor){
		$exception = sprintf('class="custom-color-field" data-default-color="%s"',$particle_default[$particle_settings[$prop]]);//*
	}
	
	$type = sprintf('type="%s"',($isColor ? "text" : "number" ));
	$result = '<input '.$type.' value="'.$value.'" '.$_min.' '.$_max.' '.$_step.' name="'.$particle_settings[$prop].'" '.$disableState.' '.$exception.' />';
	
	return $result.$notice; 
}


function handleParticleLoopArr2($particle_settings,$prop){
	$retrieve_palette_prop = get_option($particle_settings[$prop]);
	$checkedOrNot = "";
	if(empty($retrieve_palette_prop)){
		$checkedOrNot = "checked";
	}elseif(!empty($retrieve_palette_prop) && $retrieve_palette_prop == 1){
		$checkedOrNot = "checked";
	}
	return '<input type="checkbox" name="'.$particle_settings[$prop].'" value="'.$particle_settings[$prop].'" '.$checkedOrNot.' />';
}


function handleParticleLoopArr3($particle_settings,$prop){
	$retrieve_palette_prop = get_option($particle_settings[$prop]);
	$checkedOrNot = "";
	if($retrieve_palette_prop){
		$checkedOrNot = "checked";
	}
	return '<input type="checkbox" name="'.$particle_settings[$prop].'" value="'.$particle_settings[$prop].'" '.$checkedOrNot.' />';
}

 ?>