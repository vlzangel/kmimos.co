<?php

    $formaction = 'pfbepetsitter_cancel_confirm';
    $noncefield = wp_create_nonce($formaction);
    $buttonid = 'pf-ajax-cancel-confirm-button';
    $buttontext = esc_html__('SOLICITAR CANCELACIÓN','pointfindert2d');
    $this->FieldOutput .= '
                                        <h1>Cancelación de Postulación para ser cuidador</h1><hr><br>
                                              <section>
                                                <label for="confirm_cancel" class="lbl-text">
                                                <input type="checkbox" name="confirm_cancel" id="confirm_cancel" value="1">
                                                <strong>'.esc_html__('Estoy realmente seguro de querer cancelar mi postulación','pointfindert2d').'</strong>.</label>
                                            </section>
                                        <br>';
    $this->ScriptOutput = '
                                        jQuery("#pf-ajax-cancel-confirm-button").prop("disabled",true);
                                        jQuery("#confirm_cancel").on("click",function(e){
                                            if(jQuery(this).is(":checked")) jQuery("#pf-ajax-cancel-confirm-button").prop("disabled",false);
                                        });
                                    ';


?>