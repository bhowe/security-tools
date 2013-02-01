<?php

/**
* Scan and fixes common eval exploit very common in wordpress, drupal, joomla


*/

ini_set('memory_limit', '3G');

set_time_limit(0);

$hack_str = 'eval(base64_decode(';

$the_dir = '../businessforms';

function get_infected_files( $dir ) {
	global $hack_str;
	$dir = rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
	$d = opendir($dir);
	$files = array();
	if ( $d ) {
		while ( $f = readdir($d) ) {
			$path = $dir . $f;
				
			if ( is_dir($path) ) {
				if ( $f != '.' && $f != '..' ) {
					$more_files = get_infected_files($path);
					if ( count($more_files) > 0 ) {
						$files[] = $more_files;
					}
				}
			}
			else {
				if ( strpos($f, '.php') !== false ) {
					$contents = explode($hack_str, file_get_contents($path));
					if (count($contents) > 1) {
						$files[] = $path;
					}
				}
			}
		}
	}
	return $files;
}

function print_files( $files ) {
	if ( count($files) > 0 ) {
		foreach ( $files as $file ) {
			if ( is_array($file) ) {
				print_files($file);
			}
			else {
				echo $file . '<br />';
			}
		}
	}
}

function fix_files( $files ) {
	global $hack_str;
	foreach ( $files as $file ) {
		if ( is_array($file) ) {
			fix_files($file);
		}
		else { 
			$getCOntents = explode('eval(base64_decode', file_get_contents($file));
			
			$realCOntents = "";
			
			foreach($getCOntents as $thC){
				$extLs = explode('="));', $thC);
				$realCOntents .= (isset($extLs[1])) ? $extLs[1] : $thC;
			}
			
			
			$contents = explode("\n", $realCOntents);
			//unset($contents[0]);
			$f = fopen($file, 'w');
			if ( $f ) {
			
				//$the_content = preg_replace("#eval(base64_decode(.*?));#is", '', $contents); // remove any leading whitespace.
				
				$the_content = implode($contents, "\n");
				
				fwrite($f, $the_content);
				fclose($f);
				echo "Removed first line containing <code>" .  htmlentities($hack_str) ."</code>from $file...<br />";
			}
		} 
	}
}

function get_count( $files ) {
	$count = count($files);
	foreach ( $files as $file ) {
		if ( is_array($file) ) {
			$count--; // remove this because it's a directory
			$count += get_count($file);
		}
		else {
			$count ++;
		}
	}
	return $count / 2;
}

?>

<?php
$files = get_infected_files($the_dir);
?>

<h2><?php echo get_count($files); ?> Infected Files in <?php echo $the_dir; ?></h2>

<?php 
if ( count($files) > 0 ) :

	if ( $_POST['do_fix'] ) :
		fix_files( $files );
		die();
	endif; 
	
	print_files($files);
?>
<form method="post" action="">
	<p>
		<label for="fix">
			<input type="hidden" name="do_fix" value="1" />
			Fix files: <input type="submit" value="Fix Files" onclick="
				var ret1 = confirm('Have you backed up your existing files?');
				return ret1;
				" />
	</label>
	</p>
</form>
<?php endif; ?>