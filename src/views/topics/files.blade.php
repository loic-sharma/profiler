<div class="anbu-tab-pane anbu-table anbu-{{ $name }}count">
				@if (count($items) >0)
				<table>
					<tr>
						<th>File</th>
						<th>Size</th>
					</tr>
					@foreach ($items as $file)
					<tr>
						<td class="anbu-table-first-wide">{{ $file['filePath'] }}</td>
						<td><pre>{{ $file['size'] }}</pre></td>
					</tr>
					@endforeach
				</table>
				@else
					<span class="anbu-empty">Pas d'infos Fichiers.</span>
				@endif
</div>
