jQuery(document).ready(function(e){
    
        var news_show=-1;
        var news_count=2;
        var news_navigate=1;
        var news_action = false;
        var news_post=jQuery('#last .section.news .post');

        jQuery('#last .section.news .action .icon.arrow a').click(function(event){
            if(jQuery(this).closest('.icon.arrow').length){
                news_nav(jQuery(this).closest('.icon.arrow'));
            }

            if(news_action == false){
                event.preventDefault();
                event.stopPropagation();
            }
        });


        function news_nav(element){
            if(news_action == false){
                news_post.removeClass('show');
                var direction = jQuery(element).data('direction');
                var show = news_show;
                var action = false;

                // console.log(direction);

                if(direction=='prev'){
                    show=show-(news_navigate*news_count);
                }

                for(var news=1; news<=news_count; news++){
                    var post=show+news+(news_navigate-news_count);//

                    if(post>0 && news_post.eq(post).hasClass('loadfirst')){
                        action=false;
                        post=-1;

                    }else if(post<0 && news_post.closest('.section.news').find('loadfirst').length>0){
                        action=false;
                        post=0;

                    }else if(post<0 && direction=='prev'){
                        action=true;

                    }else if(post>0 && news_post.eq(post).hasClass('redirect')){
                        action=true;

                    }

                    if(post<0){
                        post=0;

                    }else if(post==(news-news_count) && news<=news_count){
                        post++;
                    }

                    //console.log(post);
                    if(news_show_display(post) && !action){
                        news_show=post;

                    }else{
                        if(news_post.closest('.news').find('.post.show').length<=news_count || action){
                            news_action = true;
                            jQuery(element).find('a').trigger('click');
                            break;
                        }
                    }
                }
            }
        }

        function news_show_display(post){
            var news=news_post.eq(post);
            if(news.length>0 && post>=0){
                news.addClass('show');
                return true;
            }
            return false;
        }

        news_nav('');


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
