$(document).ready(function(){
    WTA.shoppingCart();
    WTA.addToCart();
    WTA.lightbox();    
    WTA.updateCountCart();
});

$(window).load(function(){
    WTA.choose_category();
    WTA.initSelectRegions();
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
        
        $('#overlay').click(function(){
            $('.wLight .closex').trigger('click');
        })
        
        $('.liLight').unbind('click').click( function(event){
            
            event.preventDefault();
            $('.wLight').remove();
            $.ajax({
                url:this.href,
                success:function(html, textStatus, jqXHR){
                    $('body').prepend(html);
                    var $wLight = $('.wLight');
                    $wLight.hide().delay(500).fadeIn();
                    //var top=(parseInt($(window).height())-parseInt($('.wLight > div').height()))/2;
                    var left=$wLight.find('> div').width()/2;
                    
                    $wLight.css('margin-left', '-'+left+'px');
                    $('#overlay').fadeIn();
                    $wLight.css("top",'100px');
                    $wLight.find('.closex').click( function(){
                        $('.wLight').fadeOut().delay(500).remove();
                        $('#overlay').fadeOut();
                    });
                    $('html, body').animate({'scrollTop': 0}, 500)
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
            
            var next = function(){
                var $next = imgCurrent.next('img');
                if( !$next.length ){
                    $next = $($imgs.get(0));
                }
                if( $next.length ){
                    $next.animate({opacity: 1}, 600);
                    imgCurrent.animate({opacity: 0}, 300, function(){
                        setTimeout(next, 8000);
                    });
                    
                    imgCurrent = $next;                    
                }
            }
            
            setTimeout(next, 8000);
            
            /*
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
            */
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
                    //    $('#btnShoppingCart').trigger('click');
                      //  $('#popupShoppinCart').slideDown();
                        that.updateCart(function(){$('#popupShoppinCart').slideDown();});
                    }else{
                        alert('No se pudo agregar el producto');
                    }
                });
            }
        })
    }
    
    this.updateCart = function(callback){
        
        $('#popupShoppinCart').find('.overview').load('/index/cart', function(response, status, xhr) {
            if (status == "error") {
                var msg = "Sorry but there was an error ";
                //$('#popupShoppinCart').html(msg + xhr.status + " " + xhr.statusText);
                $('#popupShoppinCart').html(msg);
            }
            
            that.updateCountCart();
            
            var $checkoutcart = $('#checkout-cart');
            
            if($checkoutcart.length){
                $checkoutcart.load('/index/checkoutcart', function(response, status, xhr) {
                     if (status == "error") {
                        var msg = "Sorry but there was an error.";
                        //$('#popupShoppinCart').html(msg + xhr.status + " " + xhr.statusText);
                        $checkoutcart.html(msg);
                    }
                    
                    $checkoutcart.find('.remove').click(function(e){
                        e.preventDefault();
                        $.ajax({
                            url: $(this).attr('href')
                        }).done(function(response){
                            that.updateCart();
                        });
                    });
                });
            }
            
            $.bootstrap_selects();
            
            if(callback){
                callback();
            }
            
            var $popupShoppinCart = $('#popupShoppinCart');
            
            $popupShoppinCart.find('.remove').click(function(e){
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('href')
                }).done(function(response){
                    that.updateCart();
                });
            });
            
            $popupShoppinCart.find('.quantity').blur(function(){
                var $this = $(this);
                var value = parseInt($this.val(), 10);
                if( !value ){
                    value = 1;
                    $this.val(1);
                }
                $.ajax({
                    url: '/index/changeitem/clave/'+$this.data('code')+'/quantity/'+value+'/format/json'
                }).done(function(response){
                    that.updateCart();
                });
            });
                                
            $popupShoppinCart.find('.products_size').change(function(){
                var code = $(this.parentNode.parentNode.parentNode).find('input.quantity').data('code')
                $.ajax({
                    url: '/index/changeitem/clave/'+code+'/size/'+this.value+'/format/json'
                }).done(function(response){
                    that.updateCart();
                });
            });
            
            
            $('#scrollbar1').tinyscrollbar();
        });

    }
    
    this.shoppingCart = function(){
        $('#btnShoppingCart').click(function(){
            var $this = $(this);
            if( $this.hasClass('activo') ){ // oculta
                $this.removeClass('activo');
                $('#popupShoppinCart').slideUp();
            }else{
                that.updateCart(function(){$('#popupShoppinCart').slideDown();});
                $this.addClass('activo');                
            }
        });
        $('#popupShoppinCart').find('.closex').click(function(){
            $('#btnShoppingCart').trigger('click');
        })
    };
    
    this.updateCountCart = function(){        
        $.ajax({
            url: '/index/countcart/format/json'
        }).done(function(response){
            if(response.count){
                $('#btnShoppingCart').html('<strong>Shopping Cart ('+parseInt(response.count,10)+')</strong>');
            }else{
                $('#btnShoppingCart').html('<strong>Your Shopping Cart is empty</strong>');
            }
        });
    }
    
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
        
        
        $objs.each(function(i){
            var $this = $(this);
            if($this.prop('complete')==true){
                $this.css('opacity', 0);
                $this.animate({'opacity': 1});
                $this.addpowerzoom({
                    defaultpower:1,
                    magnifiersize: [270, 270] 
                });  
            }else{
                $this.css('opacity', 0);
                $this.bind("load", function(){
                    $this.animate({'opacity': 1});
                    $this.addpowerzoom({
                        defaultpower:1,
                        magnifiersize: [220, 220] 
                    });  
                });
            }
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
        
        var $slider_products = $('#slider_products');
        if( !$('#contentBody').hasClass('boutique')){
            $('BODY').append($slider_products);
        }
        
        var $slider = $('#slider');        
        
        $slider.carouFredSel({
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
        var timeOcultar = null;
        
        $slider_products.mouseover(function(){
            clearTimeout(timeOcultar);
            timeOcultar = setTimeout(function(){
                $slider_products.animate({marginTop: '-28px', height: 28});
                bar.addClass('mostrar');
            }, 5000);
        });
        
        $slider_products.data('h',$slider_products.height());
        
        var showProducts = function(e){

            var $this = $(this);                        
            if($this.hasClass('mostrar')){
                //$('#slider_products').find('.slider-content').slideToggle();
                $slider_products.animate({marginTop: '-175px', height: $slider_products.data('h')});
                $('#slider').trigger('updateSizes');
                $this.toggleClass('mostrar');
            }else{
                if(e.type=='click'){
                    $slider_products.animate({marginTop: '-28px', height: 28});
                    $this.toggleClass('mostrar');
                }
            }            
        }
        
        bar.mouseenter(showProducts);
        bar.click(showProducts);
        
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
    
    
    this.signin = function(){
        $('#formSignin').submit(function(e){            // Validaciones para el login
            e.preventDefault();
            var $this = $(this);            
            var $email = $this.find('#email');
            var $password = $this.find('#password');
            var validForm = true;
            $this.find('span.error').remove();
            
            if( !that.isEmail( $email.val() ) ){
                validForm = false;
                that.setMsgError($email, 'Oops, there\'s something wrong with your email. Please try again.');
            }
            
            if( that.isEmpty( $password.val()) ){
                validForm = false;
                that.setMsgError($password, 'Password is empty');
            }
            
            if( validForm ){
                $.ajax($this.attr('action'), 
                        {
                            type:'POST', 
                            data: $this.serialize()
                        }).done(function(response){
                            if(response.message){
                                if(response.message == 'Incorrect Authentication'){
                                        that.setMsgError($this.find('input[type=submit]'), 'Hmm, not it. We can’t remember ours either. Try again!   .');
                                }else{
                                    if(response.message == 'Successful Authentication'){
                                        that.setMsgError($this.find('input[type=submit]'), 'Corrrect');
                                        window.location.href = window.location.href;
                                    }
                                }
                                
                            }
                        })
            }
            return false;
        })
        
        $('#formRegister').submit(function(e){            // Validaciones para el login
            e.preventDefault();
            var $this = $(this);            
            var $firstname = $this.find('#firstname');
            var $lastname = $this.find('#lastname');
            var $email = $this.find('#email');
            var $password1 = $this.find('#password1');
            var $password2 = $this.find('#password2');
            var $cbterms = $this.find('#cbterms');
            
            var validForm = true;
            $this.find('span.error').remove();
            
            
            
            if( that.isEmpty( $lastname.val() ) ){
                validForm = false;
                that.setMsgError($lastname, 'Last name is empty');
            }
            
            if( that.isEmpty( $firstname.val() ) ){
                validForm = false;
                that.setMsgError($firstname, 'First name is empty');
            }
            
            if( !that.isEmail( $email.val() ) ){
                validForm = false;
                that.setMsgError($email, 'Oops, there\'s something wrong with your email. Please try again.');
            }
            
            if( !that.isValidLength( $password1.val(), 8 ) ){
                validForm = false;
                that.setMsgError($password1, 'Enter a value at least 8 characters long');
            }
            
            if( $password1.val() !=  $password2.val() ){
                validForm = false;
                that.setMsgError($password1, 'The passwords must match');
            }
            
            if( $cbterms.attr('checked') != 'checked' ){
                validForm = false;
                that.setMsgError($cbterms, 'You must to accept the Terms of Services');
            }
            
            if( validForm ){
                $.ajax($this.attr('action'), 
                        {
                            type:'POST', 
                            data: $this.serialize()
                        }).done(function(response){
                            if(response.formMessages){
                                for(var i in response.formMessages){
                                    var obj = $this.find('#'+i);
                                    for(var j in response.formMessages[i]){
                                        that.setMsgError(obj, response.formMessages[i][j]);
                                    }
                                }
                            }else{
                                if(response.message == 'Registration was successful' || response.message == 'Successful Authentication'){
                                    window.location.href = window.location.href;
                                }
                            }
                        })
            }
            return false;
        })
        
        
    }
    
    /*
     *  VALIDACIONES Y MENSAJES DE ERROR
     */ 
    
    // True si es un correo
    this.isEmail = function(str){
        var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
        if( !emailPattern.test(str)){
            return false;
        }
        return true
    }
    
    // True si esta vacio
    this.isEmpty = function(str){
        var pattern = /^\s*$/;
        if( !pattern.test(str)){
            return false;
        }
        return true
    }    
    
    // True si no esta vacio y tiene una longitud minima 
    this.isValidLength = function(str, minlength){

        if( that.isEmpty(str) || str.length < minlength){
            return false;
        }
        return true
    }
    
    this.setMsgError = function($element, msgError){
        if(msgError){
            $element.parent().append('<span class="error">'+msgError+'</span>');
        }
        if($element.attr('type') && $element.attr('type')=='hidden'){
            $element.parent().addClass('error');
        }else{
            $element.addClass('error');
        }
    }
    
    /*  / VALIDACIONES Y MENSAJES DE ERROR     */ 
    
    this.initSelectRegions = function(){
        $('.regions').change(function(){
            var $this = $(this);
            $.ajax('regions/states/format/json/region/'+$this.val()).done(function(response){
                if(response.subregions){
                    var $destino = $this.parent().parent().find('.subregions');
                    var select = '';
                    for(var i in response.subregions){
                        select += '<li><a href="javascript:;" data-value="' + response.subregions[i]['id'] + '">' + response.subregions[i]['name'] + '</a></li>'
                    }                    
                    $destino.parent().find('.dropdown-menu').html('').append(select);
                    $destino.parent().find('.dropdown-menu a').click(function() {
                            $destino.parent().find('input[type=hidden]').val($(this).data('value')).trigger('change');
                            $destino.parent().find('.btn:eq(0)').text($(this).text());
                    });
                }
            })
        });
    }
    
    this.checkout = function(){
        $('#bill_same').change(function(){
            if(this.checked){
                $('#bill_same_content').slideUp();
            }else{
                $('#bill_same_content').slideDown();
            }
        });
        
        $('#frmcheckout').submit(function(e){
            e.preventDefault();
            var $this = $(this);
            
            $('span.error').remove();
            $this.find('.error').removeClass('error');
            
            var not_empty = ['inf_firstname', 'inf_lastname', 
                                            'shp_firstname', 'shp_lastname', 'shp_address', 'shp_city', 'shp_region', 
                                                'shp_cp', 'shp_country', 'shp_phonenumber',
                                            'card_name', 'card_number', 'card_expirationmonth', 'card_expirationyear', 'car_seccode'];
            var form_valid = true;
            
            var $email = $this.find('#inf_emailaddress');
            
            if( !that.isEmail($email.val()) ){
                that.setMsgError($email);
                that.setMsgError($('#checkoutsubmit'), 'Oops, there\'s something wrong with your email. Please try again.');
            }
            
            for(var i in not_empty){
                var tmp = $this.find('#'+not_empty[i]);
                if(tmp.length && (that.isEmpty(tmp.val()) || tmp.val()=='0')){
                    form_valid = false;
                    that.setMsgError(tmp);
                }
            }
            
            var $bill_same = $this.find('#bill_same');
            if( $bill_same.attr('checked') != 'checked' ){
                var not_empty_shp = ['bill_firstname', 'bill_lastname', 'bill_address', 'bill_city', 'bill_region', 'bill_country', 'bill_cp', 'bill_phonenumber'];
                
                for(var j in not_empty_shp){
                    tmp = $this.find('#'+not_empty_shp[j]);
                    if(tmp.length && (that.isEmpty(tmp.val()) || tmp.val()=='0')){
                        form_valid = false;
                        that.setMsgError(tmp);
                    }
                }
            }
            
            var $cb_termns = $('#cb_termns');
            if( $cb_termns.attr('checked') != 'checked' ){
                that.setMsgError($('#checkoutsubmit'), 'You must to accept the Terms of Services');
            }
            
            if(form_valid){
                $.ajax($this.attr('action'), {data: $this.serialize()}).done(function(response){s
                    $('#linkprocessed').trigger('click');
                })
            }else{
                that.setMsgError($('#checkoutsubmit'), 'You empty fields');
            }
        });
        
    }
    
    this.checkoutForm = function(){
        $('#checkoutsubmit').click(function(e){
            e.preventDefault();
            $('#frmcheckout').trigger('submit');
        })
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