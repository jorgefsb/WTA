$(document).ready(function(){
    WTA.shoppingCart();
    WTA.addToCart();
    WTA.lightbox();    
});

$(window).load(function(){
    WTA.choose_category();
})

var WTA = (function(){
    var that = this;
    
    /*
     *  Iniciamos efectos y funcionalidades del home
     */
    this.Home = function(){

        $('#logo-small').hide();
        
        $('#lissections').find('>li li').hover(function(){            
            $(this).find('ul').animate({opacity:1}, 200);
            $(this).css('z-index', 41);
        }, function(){
            $(this).find('ul').stop(true, true).animate({opacity:0}, 100);
            $(this).css('z-index', 19);
        })
        
        
    };
    
    this.Exclusive = function(){
        
        var details = $('#contentBody').find('.info').find('.details');
        if(details.length){
            details.click(function(e){
                e.preventDefault();                
                $(this).parent().find('.details-content').slideDown();
            });
            details.parent().find('.details-content').hide();
            
        }
        
    };
    
    this.lightbox = function(){
        $('.liLight').unbind('click').click( function(event){

            event.preventDefault();
            $('.wLight').remove();
            $.ajax({
                url:this.href,
                success:function(html, textStatus, jqXHR){
                    $('body').prepend(html);
                    $('.wLight').fadeIn();
                    var top=(parseInt($(window).height())-parseInt($('.wLight > div').height()))/2;
                    if(top<40){
                        top = 40;
                    }
                    $('.wLight > div').css("top",top+'px');
                    $('.wLight .closex').click( function(){
                        $('.wLight').fadeOut();
                        $('.wLight').remove();
                    });
                }
            })
        })
    }
    
    
    /*
     * Slider de fondo
     */
    this.bgSlider = function(images, div){ // Creamos el slider del background
                	
        //  Initialize Backgound Stretcher	   
        if( !div ){
            div = 'BODY';
        }
        if(!images){
            return false;
        }
        //var images = ['images/1girl.jpg', 'images/3girls.jpg'];
        var $content = $(div);
        
        if( $content.length ){
            var $divSlides = $('<div>');
            
            $content.append($divSlides);
            var $divSlides_width = $divSlides.width();
                        
            for(var i in images){
                $divSlides.append( $('<img>', {src: images[i]} ));
            }
            
            var ajustar = function(){                
                var $this = $(this);
                var w = $this.width();
                var diff = w  - $divSlides_width;
                                
                if( diff > 0){
                    $this.css('margin-left', (diff/2)*-1 );
                }else{
                    $this.css('margin-left', diff/2);                    
                }
            }
            
            var $imgs = $divSlides.find('img')
            $imgs.each(function(i, item){
                var $this = $(this);                                
                                                
                if($this.prop('complete')==true){
                    $this.css('opacity', 0);
                    ajustar.call(this);
                }else{
                    $this.css('opacity', 0);
                    $this.bind("load", ajustar);
                }
                
            });
            
            var imgCurrent = $($imgs.get(0));
            var $next = null;
            var $prev = null;
            imgCurrent.animate({opacity: 1});
            
            $divSlides.prepend($('<span class="next">').click(function(){
                var $next = imgCurrent.next('img');
                if( !$next.length ){
                    $next = $($imgs.get(0));
                }
                if( $next.length ){
                    $next.animate({opacity: 1}, 600);
                    imgCurrent.animate({opacity: 0}, 300);
                    
                    imgCurrent = $next;                    
                }
            }));
            
            $divSlides.prepend($('<span class="prev">').click(function(){
                
                var $prev = imgCurrent.prev('img');
                if( !$prev.length ){
                    $prev = $($imgs.get(-1));
                }
                if( $prev.length ){
                    $prev.animate({opacity: 1}, 600);
                    imgCurrent.animate({opacity: 0}, 300);
                    
                    imgCurrent = $prev;
                }
            }));
            
        }
      
    };
    
    /********************************* CART *************************************/
    /*
     * Agregamos al carrito
     **/
    this.addToCart = function(){
        $('.addToCart').click(function(e){
            e.preventDefault();
            var $this = $(this);
            var code = $this.data('code');
            if( !(/^\s*$/).test( code ) ){
                $.ajax({
                    type: 'POST',                    
                    url: '/index/addtocart/format/json', 
                    data: {code:code}                    
                }).done(function(response){
                    if(response.ok){
                        $('#btnShoppingCart').trigger('click');
                    }else{
                        alert('No se pudo agregar el producto');
                    }
                });
            }
        })
    }
    
    this.updateCart = function(callback){
        $('#shoppingCart').find('.overview').load('/index/cart', function(response, status, xhr) {
            if (status == "error") {
                var msg = "Sorry but there was an error ";
                //$('#shoppingCart').html(msg + xhr.status + " " + xhr.statusText);
                $('#shoppingCart').html(msg);
            }
            $.bootstrap_selects();
            callback();
            $('#scrollbar1').tinyscrollbar();
        });

    }
    
    this.shoppingCart = function(){
        $('#btnShoppingCart').click(function(){
            var $this = $(this);
            if( $this.hasClass('activo') ){ // oculta
                $this.removeClass('activo');
                $('#shoppingCart').slideUp();
            }else{
                that.updateCart(function(){$('#shoppingCart').slideDown();});
                $this.addClass('activo');                
            }
        })
    };
    
    /********************************* / CART *************************************/
    
    /********************************* ZOOM *************************************/
    
    /*
     *Crea el efecto de zoom en las imagenes 
     */
    this.zoom = function(selector){
        
        var $objs = $(selector);
        
        if(!$objs.length){return;}
        if( $objs.get(0).tagName.toLowerCase() != 'img' ) {  
            $objs = $objs.find('> img');
        }
        
        if(!$objs.length){return;}
        
        $objs.addpowerzoom({
            defaultpower:1,
            magnifiersize: [270, 270] 
        });  
        
    }
    
    this.iniMenuSections = function(visible){
        $('#change_section').find('.submenu-btn').click(function(){
            var $this = $(this);
            $('.submenu-content').slideToggle();
        });
        if(!visible){
            $('.submenu-content').css('display', 'none');
        }
        
    }
    /********************************* /ZOOM *************************************/
    
    /********************************* SLIDER PRODUCTOS *************************************/
    /*
     * Genera el slider de los productos 
     */
    this.slider_products = function(){
        $('#slider').carouFredSel({
            auto: false,
            responsive: true,
            width: '100%',
            scroll: 1,            
            mousewheel: true,
            swipe: {
                    onMouse: true,
                    onTouch: true
            },
            items: {
                //width: 400,
                //	height: '30%',	//	optionally resize item-height
                visible: {
                    min: 1,
                    max: 3
                }
            }          
        });
        
        var bar = $('#slider_products').find('.bar')
        
        var $slider_products = $('#slider_products');
        $slider_products.data('h',$slider_products.height());
        bar.click(function(){
            var $this = $(this);                        
            if($this.hasClass('mostrar')){
                //$('#slider_products').find('.slider-content').slideToggle();
                $slider_products.animate({marginTop: '-175px', height: $slider_products.data('h')});
                $('#slider').trigger('updateSizes');
            }else{
                $slider_products.animate({marginTop: '-28px', height: 28});
            }
            $this.toggleClass('mostrar');
        });
        $('#prev2').click(function(e){
            e.preventDefault();
            $('#slider').trigger('prev', {items:1});
        });
        $('#next2').click(function(e){
            e.preventDefault();
            $('#slider').trigger('next', {items:1});
        });
        
        setTimeout(function(){
            bar.trigger('click');
        }, 1500)
        
    }
    /********************************* SLIDER PRODUCTOS *************************************/
    
    this.choose_category = function(){
        var $choosecat = $('#choosecat');
        if($choosecat .length){
            $choosecat .change(function(){
                var url = $choosecat.val();
                if(url){
                    window.location.href = url;
                }
            });
        }
    }
    
    
    
    return this;
    
})();


/*   
 * Plugin  de bootstrap para los selectores
 */
jQuery(function($){
    $.bootstrap_selects = function(){
            $('select').each(function(i, e){
                    if (!($(e).data('convert') == 'no')) {
                            $(e).hide().wrap('<div class="btn-group" id="select-group-' + i + '" />');
                            var select = $('#select-group-' + i);
                            var current = ($(e).val()) ? $(e).find('option:selected').text(): '&nbsp;';
                            select.html('<input type="hidden" value="' + $(e).val() + '" name="' + $(e).attr('name') + '" id="' + $(e).attr('id') + '" class="' + $(e).attr('class') + '" /><a class="btn" href="javascript:;">' + current + '</a><a class="btn dropdown-toggle" data-toggle="dropdown" href="javascript:;"><span class="caret"></span></a><ul class="dropdown-menu"></ul>');
                            $(e).find('option').each(function(o,q) {
                                    select.find('.dropdown-menu').append('<li><a href="javascript:;" data-value="' + $(q).attr('value') + '">' + $(q).text() + '</a></li>');
                                    if ($(q).attr('selected')) select.find('.dropdown-menu li:eq(' + o + ')').click();
                            });
                            select.find('.dropdown-menu a').click(function() {
                                    select.find('input[type=hidden]').val($(this).data('value')).trigger('change');
                                    select.find('.btn:eq(0)').text($(this).text());
                            });
                    }
            });
    };
    
    $.bootstrap_selects();
});