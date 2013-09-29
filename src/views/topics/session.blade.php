<div class="anbu-tab-pane anbu-table anbu-{{ $name }}count">
				@if (count($items) >0)
				<table>
					<tr>
						<th>Key</th>
						<th>Value</th>
					</tr>
					@foreach ($items as $key=>$value)
					<tr>
						<td class="anbu-table-first">{{ $key }}</td>
						<td><pre>{{ print_r($value) }}</pre></td>
					</tr>
					@endforeach
				</table>
				@else
					<span class="anbu-empty">Pas d'infos Sessions.</span>
				@endif
</div>
