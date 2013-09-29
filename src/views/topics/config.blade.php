<div class="anbu-tab-pane anbu-table anbu-{{ $name }}count">
				@if (count($items) >0)
				<table>
					<tr>
						<th>Key</th>
						<th>Value</th>
					</tr>
					<tr>
						<td class="anbu-table-first-middle">base_path()</td>
						<td><?php echo base_path(); ?></td>
					</tr><tr>
						<td class="anbu-table-first-middle">public_path()</td>
						<td><?php echo public_path(); ?></td>
						
					</tr>
					@foreach ($items as $key=>$value)
					<tr>
						<td class="anbu-table-first-middle">{{ $key }}</td>
						<td><pre>{{ $value }}</pre></td>
					</tr>
					@endforeach
				</table>
				@else
					<span class="anbu-empty">Pas d'infos Config.</span>
				@endif
</div>
