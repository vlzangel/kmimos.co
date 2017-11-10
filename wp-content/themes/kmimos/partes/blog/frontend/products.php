<div class="kmibox contain" data-section="<?php echo getTema(); ?>/partes/blog/process/products.php">
    <div class="action">
        <i class="icon arrow fa fa-caret-left" data-direction="prev"></i>
        <i class="icon arrow fa fa-caret-right" data-direction="next"></i>
    </div>
    <div class="group">
        <?php
        //include_once(__DIR__.'/blog/process/products.php');
        ?>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function(){
        var products = 1;
        jQuery(document).on('click','#products .kmibox .action .icon', function(e){
            products_page(this);
        });

        function products_page(element){
            var direction = jQuery(element).data('direction');
            var kmibox = jQuery(element).closest('.kmibox');
            var path = kmibox.data('section');
            jQuery.post(path,{'page':products, 'direction':direction},function(data){
                //console.log(data);
                data=JSON.parse(data);
                if(data['result']){
                    products = data['page'];
                    kmibox.find('.group').fadeOut(function(e){
                        jQuery(this).html(data['html']).fadeIn();
                    });
                }
            });
        }
        products_page('#products .kmibox .action .icon');
    });
</script>