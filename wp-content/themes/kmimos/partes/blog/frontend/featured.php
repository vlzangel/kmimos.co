<div class="caregiver contain" data-section="<?php echo getTema(); ?>/partes/blog/process/featured.php">
    <div class="action">
        <i class="icon arrow fa fa-caret-left" data-direction="prev"></i>
        <i class="icon arrow fa fa-caret-right" data-direction="next"></i>
    </div>
    <div class="group">
        <?php
        //include_once(__DIR__.'/blog/frontend/featured.php');
        ?>
    </div>
</div>
<!-- <script type="text/javascript">
    jQuery(document).ready(function(){
        var featured = 1;
        jQuery(document).on('click','#featured .caregiver .action .icon', function(e){
            featured_page(this);
        });

        function featured_page(element){
            var direction = jQuery(element).data('direction');
            var caregiver = jQuery(element).closest('.caregiver');
            var path = caregiver.data('section');
            jQuery.post(path,{'page':featured, 'direction':direction},function(data){
                //console.log(data);
                data=JSON.parse(data);
                if(data['result']){
                    featured = data['page'];
                    caregiver.find('.group').fadeOut(function(e){
                        jQuery(this).html(data['html']).fadeIn();
                    });
                }
            });
        }
        featured_page( jQuery('#featured .caregiver .action .icon') );
    });
</script> -->