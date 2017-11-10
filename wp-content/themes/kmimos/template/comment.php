<div class="comments">
	<h3 id="reply-title" class="comment-reply-title">
		Deja un comentario
	</h3>
	<form action="/" onsubmit="return false;" method="post" id="commentform" class="comment-form golden-forms">
		<p class="comment-notes">
			<span id="email-notes">Tu dirección de correo electrónico no será publicada.</span>
			Los campos necesarios están marcados <span class="required">*</span>
		</p>

		<textarea id="comment" name="comment" class="textarea" placeholder="Tu comentario" required></textarea>
		<input type="text" name="author" id="author" class="input" placeholder="Nombre*" aria-required="true" required>
		<input type="email" name="email" id="email" class="input" placeholder="Dirección de correo*" aria-required="true" required>
		<div class="g-recaptcha" data-sitekey="6LeQPysUAAAAAKKvSp_e-dXSj9cUK2izOe9vGnfC"></div>

		<p class="form-submit">
			<input name="submit" type="submit" id="submit" class="submit button km-btn-primary" value="Publicar comentario">
			<input type="hidden" name="comment_post_ID" value="<?php echo get_the_ID(); ?>" id="comment_post_ID">
			<input type="hidden" name="comment_parent" id="comment_parent" value="0">
		</p>
	</form>
</div>
    