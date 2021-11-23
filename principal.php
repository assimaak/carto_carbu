<!-- Koboyoda Arthur Assima - Fouad Medjahed -->
<?php  // script principal

  if (filter_input(INPUT_GET,'valid')===NULL) {
					require('views/index.php');
					exit();
	 }


	try {
		 require('lib/verifyParms.php');
		 require('views/index.php');

 }

 catch (ParmsException $e){
		 $errorMessage = $e->getMessage();
			require('views/index.php');
}

?>
