<div class="anbu-tab-pane anbu-table anbu-{{ $name }}count">
				@if (count($items) >0)
				<table>
					<tr>
						<th>Facade</th>
						<th>Class</th>
					</tr>
				        <tr>
					<td colspan="2" class="anbu-center"><a href="http://laravel.com/api/" target="api">API Documentation</a></td>
					</tr>
					@foreach ($items as $value)
					<tr>
						<td class="anbu-table-first-middle">{{ $value }}.::</td>
						<td>{{ ucfirst(strtolower($value)) }}</td>
					</tr>
					@endforeach
				</table>
				@else
					<span class="anbu-empty">Pas d'infos Facades.</span>
				@endif
</div>
