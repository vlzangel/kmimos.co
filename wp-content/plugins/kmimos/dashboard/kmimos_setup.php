<div class="wrap">
	<?php screen_icon();?><h2>Sistema de Administración de Kmimos - Configuración</h2>
	<form method="post" action="options.php">
		<?php settings_fields('kmimos_group'); ?>
		<?php @do_settings_fields('kmimos_group'); ?>
		<table class="form-table">
			<tr class="valign: top">
				<th scope="row">
					<label for="kmimos_title_plugin">Título</label>
				</th>
				<td>
					<input  type="text" name="kmimos_title_plugin" value="<?php echo get_option("kmimos_title_plugin"); ?>" /><br />
					<small>texto de ayuda</small>
				</td>
			</tr>
			<tr class="valign: top">
				<th scope="row">
					<label for="kmimos_description_plugin">Desctipción</label>
				</th>
				<td>
					<textarea name="kmimos_description_plugin"><?php echo get_option("kmimos_description_plugin"); ?></textarea><br />
					<small>texto de ayuda</small>
				</td>
			</tr>
			<tr class="valign: top">
				<td colspan="2">
					<input type="checkbox" id="kmimos_redirect_by_ip" name="kmimos_redirect_by_ip" value="1"<?php if(get_option("kmimos_redirect_by_ip")=='1') echo ' checked="checked"'; ?> />
					<label for="kmimos_redirect_by_ip">Redireccionar según IP en países donde esté presente KMIMOS</label>
				</td>
			</tr>
			<tr class="valign: top">
				<td colspan="2">
					<input type="checkbox" id="kmimos_notificar_por_email" name="kmimos_notificar_por_email" value="1"<?php if(get_option("kmimos_notificar_por_email")=='1') echo ' checked="checked"'; ?> />
					<label for="kmimos_notificar_por_email">Enviar notificación por email al ejecutarse el CRON</label>
				</td>
			</tr>
		</table>
		<?php @submit_button(); ?>
	</form>
</div>
