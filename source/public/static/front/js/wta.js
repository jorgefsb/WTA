$(document).ready(function(){
    WTA.shoppingCart();
    
		
});

var WTA = (function(){
    var that = this;
    
    /*
     *  Iniciamos efectos y funcionalidades del home
     */
    this.Home = function(){

        $('#lissections').find('>li li').hover(function(){            
            $(this).find('ul').animate({opacity:1}, 200);
            $(this).css('z-index', 41);
        }, function(){
            $(this).find('ul').stop(true, true).animate({opacity:0}, 100);
            $(this).css('z-index', 19);
        })
        
    };
    
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
        
        /*
        $(div).bgStretcher({
            images: ['images/3girls.jpg', 'images/3girls-red.jpg', 'images/3girls-blue.jpg'],
            imageWidth: 1400, 
            imageHeight: 671, 
            slideDirection: 'N',
            slideShowSpeed: 1000,
            transitionEffect: 'fade',
            sequenceMode: 'normal',
            resizeProportionally: false,
            buttonPrev: '#prev',
            buttonNext: '#next',
            pagination: false,
            anchoring: 'left center',
            anchoringImg: 'left center',
            preloadImg: true
        });
        */
    };
    
    this.shoppingCart = function(){
        $('#btnShoppingCart').click(function(){
            $('#shoppingCart').slideToggle();
        })
    }
    
    return this;
    
})();


/*   
 * Plugin  de bootstrap para los selectores
 */
jQuery(function($){
        $('select').each(function(i, e){
                if (!($(e).data('convert') == 'no')) {
                        $(e).hide().wrap('<div class="btn-group" id="select-group-' + i + '" />');
                        var select = $('#select-group-' + i);
                        var current = ($(e).val()) ? $(e).val(): '&nbsp;';
                        select.html('<input type="hidden" value="' + $(e).val() + '" name="' + $(e).attr('name') + '" id="' + $(e).attr('id') + '" class="' + $(e).attr('class') + '" /><a class="btn" href="javascript:;">' + current + '</a><a class="btn dropdown-toggle" data-toggle="dropdown" href="javascript:;"><span class="caret"></span></a><ul class="dropdown-menu"></ul>');
                        $(e).find('option').each(function(o,q) {
                                select.find('.dropdown-menu').append('<li><a href="javascript:;" data-value="' + $(q).attr('value') + '">' + $(q).text() + '</a></li>');
                                if ($(q).attr('selected')) select.find('.dropdown-menu li:eq(' + o + ')').click();
                        });
                        select.find('.dropdown-menu a').click(function() {
                                select.find('input[type=hidden]').val($(this).data('value')).change();
                                select.find('.btn:eq(0)').text($(this).text());
                        });
                }
        });
});