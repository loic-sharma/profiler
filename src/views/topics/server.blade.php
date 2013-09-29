<div class="anbu-tab-pane anbu-table anbu-{{ $name }}count">
				<table>
					<tr>
						<th>Key</th>
						<th>Value</th>
					</tr>
					<tr>
						<td class="anbu-table-first-more">ROUTE  courante</td>
						<td><?php echo Route::currentRouteName(); ?></td>
					</tr><tr>
						<td class="anbu-table-first-more">ACTION  courante</td>
						<td><?php echo Route::currentRouteAction(); ?></td>
						
					</tr>
					<?php
						$routes=Route::getRoutes();
						foreach($routes as $name=>$route){
						    echo '<tr><td>route: </td><td>'.$name.'</td></tr>';
						}
					?>
					@foreach ($items as $key=>$value)
					<tr>
						<td class="anbu-table-first-more">{{ $key }}</td>
						<td><pre>{{ $value }}</pre></td>
					</tr>
					
					@endforeach
				</table>
</div>
