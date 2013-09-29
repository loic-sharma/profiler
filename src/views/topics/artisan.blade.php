			<div class="anbu-tab-pane anbu-table anbu-{{ $name }}count">
				<?php
				
				$path=App::offsetGet('path.base').'/';//dirname($_SERVER['SCRIPT_FILENAME']).'/../'; //$app['path.base']
				ob_start();
				passthru('php '.$path.'artisan list', $result);
				$content_cli=ob_get_contents();
				ob_end_clean();
				echo '<pre>'.trim($content_cli).'</pre>';
				
				?>
			</div>
