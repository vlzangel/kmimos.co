<?php
    $formaction = 'pfpet_delete_confirm';
    $noncefield = wp_create_nonce($formaction);
    $buttonid = 'pf-ajax-delete-confirm-button';
    $buttontext = esc_html__('CONFIRMAR ELIMINACI?N','pointfindert2d');
    $this->FieldOutput .= '
                                <h1>Eliminación de Mascota de lista del cuidador</h1><hr><br>
                                      <section>
                                        <label for="confirm_delete" class="lbl-text">
                                        <input type="hidden" name="pet_id" value="'.$_GET['id'].'">
                                        <input type="checkbox" name="confirm_delete" id="confirm_delete" value="1">
                                        <strong>'.esc_html__('Estoy realmente seguro de querer eliminar esta mascota','pointfindert2d').'</strong>.</label>
                                    </section>
                                <br>
                                <script>
                                jQuery("#pf-ajax-delete-confirm-button").prop("disabled",true);
                                jQuery("#pfuaprofileform").prop("action","?ua=delpet");
                                jQuery("#confirm_delete").on("click",function(e){
                                    if(jQuery(this).is(":checked")) jQuery("#pf-ajax-delete-confirm-button").prop("disabled",false);
                                });
                                </script>
                            ';
?>