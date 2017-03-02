<?php 
	/**php utils**/

	//create a menu for folding the parent div.card element
	function cardMenu($name){
		echo "
		<div class=menu onclick=this.parentNode.classList.toggle('folded')>
			<button></button>
			$name
		</div>
		";
	}
?>
