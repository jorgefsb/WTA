$(document).ready(function(){
    WTA.shoppingCart();
    WTA.addToCart();
    WTA.lightbox();    
    WTA.updateCountCart();
});

$(window).load(function(){
    WTA.choose_category();
    WTA.initSelectRegions();
    WTA.ajustarImgProd();
    $(document).ajaxStop(function(){WTA.ajaxStatus(true)});
    $(window).resize(WTA.ajustarImgProd);
    
    
    if(window.location.hash) {                  // si viene un hash en la url y existe un link con link+hash como id lo ejecuta
        var hash = window.location.hash
        var link = $('#link-'+ hash.replace('#', '') );
        if(link.length){
            link.trigger('click');
        }
    }
    $(' .disabled').unbind('click').click(function(e){e.preventDefault(); return false;})
})

var WTA = (function(){
    var that = this;
    var jqxhr_active = true;
    var ajaxQueue = [];
        
    this.ajaxStatus = function(status){
        that.jqxhr_active = status;
        if(status==true){
            for(var i in that.ajaxQueue){                
                that.ajaxQueue[i]();
            }
            that.ajaxQueue = []
        }
    }
    
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
        
        var timeGift =null;
        $('.getafgt, #popupgift').hover(function(){
            $('#popupgift').stop(true, true).slideDown();
            clearTimeout(timeGift);
        },function(){
            timeGift = setTimeout(function(){
                $('#popupgift').stop(true, true).slideUp();
            },500);
        })
        
        $('#popupgift').slideUp();
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
    
    this.Designer = function(){
        
        var details = $('#contentBody').find('.info').find('.details');
        var detailsDesigner = $('#contentBody').find('.info').find('.detailsDesigner');
        if(details.length){
            details.click(function(e){
                e.preventDefault();                                
                detailsDesigner.parent().find('.detailsDesigner-content').slideUp();            
                $(this).parent().find('.details-content').slideDown();
            });
            details.parent().find('.details-content').hide();            
        }
        
        
        if(detailsDesigner.length){
            detailsDesigner.click(function(e){
                e.preventDefault();                                
                details.parent().find('.details-content').slideUp();            
                $(this).parent().find('.detailsDesigner-content').slideDown();
            });
            detailsDesigner.parent().find('.detailsDesigner-content').hide();            
        }
        
    };
    
    
    this.ForgotPassword = function(){
        $('#forgot').submit(function(e){
            e.preventDefault();
            
            var  $this = $(this);
            var $email = $this.find('input[name=mail]');
            var validForm = true;
            
            if( !that.isEmail( $email.val() ) ){
                validForm = false;
                that.setMsgError($email, 'Oops, there\'s something wrong with your email. Please try again.');
            }
            
            if(validForm){
                $.ajax($this.attr('action'), {type:'post', data:$this.serialize()}).done(function(response){
                    if(response.ok == 1){
                        that.setMsgValid($email, 'We\'ve sent an email to retrieve your account');
                        $this.find('input').fadeOut().delay(1000).remove();
                    }else{
                        that.setMsgValid($email, 'We\'ve sent an email to retrieve your account');
                        $this.find('input').fadeOut().delay(1000).remove();
                    }
                });
            }
            
        })
        
    }
    
    this.Affiliates = function(){
        $('#frmAffiliates').submit(function(e){
            
            e.preventDefault();
            
            var $this = $(this);
            var validForm = true;
            
            $this.find('span.error').remove();            
            
            $this.find('input[type=text]').each(function(){
                if(that.isEmpty(this.value)){
                    validForm = false;
                }
            });
            
            
            if( !that.isEmail($this.find('input[name=email]').val())){
                validForm = false;
                that.setMsgError($this.find('q'), 'Oops, there\'s something wrong with your email. Please try again.');
            }
            
            if(validForm){
                $.ajax($this.attr('action'), {type: 'post', data: $this.serialize()}).done(function(response){
                    if(response.send==1){
                        $this.parent().prepend('Thank you for your interest in our Affiliate Program. Your information has been sent sucessfully. A member of our team will contact you shortly.');
                        $this.fadeOut().delay(500).remove();
                    }else{
                        if(response.error){
                            that.setMsgError($this.find('q'), response.error);
                        }
                    }
                })
            }else{
                that.setMsgError($this.find('q'), 'Please complete all fields');
            }
            
            
        })
    }
    
    
    this.ajustarImgProd = function(){
        
        var $contentBody = $('#contentBody.ajustar');
        if($contentBody.length){
            var $imgProd = $contentBody.find('.imgProd');
            
            var wHeight = $(window).height();
            var fHeight = $('#footer').height();
            var hHeight = $('#header').height();
            var cHeight = $('#change_section').height();
            
            $imgProd.find('img').css('max-height', (wHeight-fHeight-hHeight-cHeight) );
            
            
        }
    }
        
    this.lightbox = function(){
        
        
        $('.liLight').unbind('click').click( function(event){
            $('#overlay').css('background-color', 'transparent');   
            var $link = $(this);
            event.preventDefault();
            
            if( !$link.hasClass('popup-modal') ){                
                $('#overlay').click(function(){
                    $('.wLight .closex').trigger('click');
                })
            }
            
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
                    if( $link.data('top')){
                        $wLight.css("top",$link.data('top'));
                    }else{
                        $wLight.css("top",'70px');
                    }
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
                	
                        
        $('BODY').bgStretcher({
            images: images, 
            imageWidth: 1034, 
            imageHeight: 671
            //callbackfunction: function (){                
            //}
        });
                        
                        
        //  Initialize Backgound Stretcher	   
       /* if( !div ){
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
            
            setTimeout(next, 8000);*/
            
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
            
        }*/
      
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
                    url: '/special/shopping/addtocart/format/json', 
                    data: {code:code}                    
                }).done(function(response){
                    
                    if(response.ok){
                        
                        //    $('#btnShoppingCart').trigger('click');
                        //  $('#popupShoppinCart').slideDown();
                        if( $this.data('redirect') ){
                            window.location.href = $this.data('redirect');
                        }else{
                            that.updateCart(function(){$('#popupShoppinCart').slideDown();});                        
                        }
                    }else{
                        alert('No se pudo agregar el producto');
                    }
                });
            }
        })
    }
    
    this.updateCart = function(callback){
        
        $('#popupShoppinCart').find('.overview').load('/special/shopping/cart', function(response, status, xhr) {
            if (status == "error") {
                var msg = "Sorry but there was an error ";
                //$('#popupShoppinCart').html(msg + xhr.status + " " + xhr.statusText);
                $('#popupShoppinCart').html(msg);
            }
            
            that.updateCountCart();
            
            var $checkoutcart = $('#checkout-cart');
            
            if($checkoutcart.length){
                $checkoutcart.load('/special/shopping/checkoutcart', function(response, status, xhr) {
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
                    url: '/special/shopping/changeitem/clave/'+$this.data('code')+'/quantity/'+value+'/format/json'
                }).done(function(response){
                    that.updateCart();
                });
            });
                                
            $popupShoppinCart.find('.products_size').change(function(){
                var code = $(this.parentNode.parentNode.parentNode).find('input.quantity').data('code')
                $.ajax({
                    url: '/special/shopping/changeitem/clave/'+code+'/size/'+this.value+'/format/json'
                }).done(function(response){
                    that.updateCart();
                });
            });
            
            $popupShoppinCart.find('.checkout').click(function(e){
                e.preventDefault();
                if(that.jqxhr_active==false){
                    var url = this.href;
                    that.ajaxQueue.push(function(){console.log('si entro');document.location.href = url;});
                }else{
                    document.location.href = this.href;
                }
            })
            
            
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
        /*$.ajax({
            url: '/shopping/countcart/format/json'
        }).done(function(response){
            if(response.count){
                $('#btnShoppingCart').html('S. Cart ('+parseInt(response.count,10)+')');
            }else{
                $('#btnShoppingCart').html('Your S. Cart is empty');
            }
        });*/
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
        var isboutique = $('#contentBody').hasClass('boutique');
        //if( !isboutique){
            $('BODY').append($slider_products);
//        }
        
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
        var timeMostrar = null;
        
        //if( !isboutique){ 
            $slider_products.mouseover(function(){                
                clearTimeout(timeOcultar);
                /*timeOcultar = setTimeout(function(){
                    $slider_products.animate({marginTop: '-28px', height: 28});
                    bar.addClass('mostrar');
                }, 2000);*/
            });
            
            $slider_products.mouseout(function(){
                timeOcultar = setTimeout(function(){
                    $slider_products.animate({marginTop: '-28px', height: 28});
                    bar.addClass('mostrar');
                }, 2000);
            })
            
            
            $('#prev2, #next2').mouseover(function(){ 
                clearTimeout(timeOcultar);                
            });
            
        //}
        
        $slider_products.data('h',$slider_products.height());
        
        var showProducts = function(e){
            var $this = $(this);                        
            if($this.hasClass('mostrar') || !e){
                //$('#slider_products').find('.slider-content').slideToggle();
                $slider_products.animate({marginTop: '-175px', height: $slider_products.data('h')});
                $('#slider').trigger('updateSizes');
                $this.toggleClass('mostrar');
            }else{
                if(e &&e.type=='click'){
                    $slider_products.animate({marginTop: '-28px', height: 28});
                    $this.toggleClass('mostrar');
                }
            }            
        }
        
        //if( !isboutique){ 
            bar.mouseenter(function(e){ 
                timeMostrar = setTimeout(function(e){showProducts(e)}, 200);
            });
            bar.click(showProducts);
            bar.mouseout(function(){clearTimeout(timeMostrar);})
        //}
        
        $('#prev2').click(function(e){
            e.preventDefault();
            $('#slider').trigger('prev', {items:1});
        });
        $('#next2').click(function(e){
            e.preventDefault();
            $('#slider').trigger('next', {items:1});
        });
        
        //if( !isboutique){
            setTimeout(function(){
                bar.trigger('click');
            }, 1500)
        //}
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
    
    
    this.setMsgValid = function($element, msgValid){
        if(msgValid){
            $element.parent().append('<span class="valid">'+msgValid+'</span>');
        }
        if($element.attr('type') && $element.attr('type')=='hidden'){
            $element.parent().addClass('valid');
        }else{
            $element.addClass('valid');
        }
    }
    
    /*  / VALIDACIONES Y MENSAJES DE ERROR     */ 
    
    this.initSelectRegions = function(){
        $('.regions').change(function(){
            var $this = $(this);
            $.ajax('regions/states/format/json/region/'+$this.val()).done(function(response){
                if(response.subregions){
                    var $destino = $this.parent().parent().find('.subregions');
                    var valdefault = $destino.parent().find('input[type=hidden]').data('default');
                    var select = '';
                    for(var i in response.subregions){
                        if(valdefault == response.subregions[i]['name']){
                            select += '<li><a href="javascript:;" class="selected" data-value="' + response.subregions[i]['id'] + '">' + response.subregions[i]['name'] + '</a></li>'
                        }else{
                            select += '<li><a href="javascript:;" data-value="' + response.subregions[i]['id'] + '">' + response.subregions[i]['name'] + '</a></li>'
                        }
                    }                    
                    $destino.parent().find('.dropdown-menu').html('').append(select);
                    $destino.parent().find('.dropdown-menu a').click(function() {
                            $destino.parent().find('input[type=hidden]').val($(this).data('value')).trigger('change');
                            $destino.parent().find('.btn:eq(0)').text($(this).text());
                    });
                    $destino.parent().find('.dropdown-menu a.selected').trigger('click');
                }
            })
        }).trigger('change');
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
            
            var not_empty = [//'inf_firstname', 'inf_lastname', 
                                            'shp_firstname', 'shp_lastname', 'shp_address', 'shp_city', 'shp_region', 
                                                'shp_cp', 'shp_country', 'shp_phonenumber',
                                            /*'card_name',*/ 'card_number', 'card_expirationmonth', 'card_expirationyear', 'car_seccode'];
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
                form_valid = false;
            }
            
            if(form_valid){
                $.ajax($this.attr('action')+'/format/json', {type: 'post', data: $this.serialize()}).done(function(response){
                    if(response.messages){
                        $('span.error').remove();                        
                        var obj = $('#checkoutsubmit');
                        for(var i in response.messages){
                            that.setMsgError(obj, response.messages[i]);
                        }
                    }else{
                       if(response.ok){
                           
                           $('#linkprocessed').trigger('click');
                           
                           _gaq.push([
                               '_addTrans',    
                               response.data.transactionID, // transaction ID - required    
                               'WeTheAdorned',  // affiliation or store name    
                               response.data.total,          // total - required    
                               '',           // tax    
                               '',              // shipping    
                               '',       // city    
                               '',     // state or province    
                               ''             // country  
                           ]);
                                                      
                           _gaq.push(['_addItem',    
                               response.data.transactionID,           // transaction ID - required    
                               response.data.products[0].code,           // SKU/code - required    
                               response.data.products[0].name,        // product name    
                               '',   // category or variation    
                               response.data.products[0].finalPrice,          // unit price - required    
                               response.data.products[0].quantity               // quantity - required  
                            ]);
                           
                           _gaq.push(['_trackTrans']); 
                           
                            
                        }else{
                            that.setMsgError(obj, 'We had a problem. Please review your information and try again');
                        }
                    }
                })
            }else{
                that.setMsgError($('#checkoutsubmit'), 'You have empty fields');
            }
        });
        
    }
    
    this.checkoutForm = function(){
        $('#checkoutsubmit').click(function(e){
            e.preventDefault();
            $('#frmcheckout').trigger('submit');
        })
    }
    
    this.beta = function(){
        $('#guestCode').submit(function(e){
            e.preventDefault();
            var $this = $(this);
            var $password = $this.find('#password');
            
            $this.find('span.error').remove();
            
            if( that.isEmpty($password.val()) ){
                that.setMsgError($password, 'Please, insert your guest code');
            }else{
                $.ajax($this.attr('action'), 
                    {
                        type: 'post',
                        data: $this.serialize()
                    }
                ).done(function(response){
                        if(response.ok){
                            //window.location.href='/';
                            $this.find('.liLight').trigger('click');
                        }else{
                            if(response.message){
                                that.setMsgError($password, response.message);
                            }
                        }
                })
            }
        });
    }
    
    this.abandon = function(){
        window.onbeforeunload = function(){
            $('#checkoutcancel').trigger('click');
            return "Would you like to receive offers from our products...";
        }
    }
    
    this.initFormSubscriptions = function(){
        $('#susbcriptions').submit(function(e){
            e.preventDefault();
            return false;
        })
        $('#saveSubscription').click(function(e){
            e.preventDefault();
            var $email = $('#input_suscription');
            if( !that.isEmail( $email.val() ) ){
                that.setMsgError($email, 'Oops, there\'s something wrong with your email.');
                return false;
            }
            $.ajax('/special/statics/subscription', 
                {
                    type: 'post',
                    data: {email:$email.val()}
                }
            ).done(function(response){
                    if(response.ok){
                        $('.closex').trigger('click');
                    }else{
                        alert('Error');
                    }
            })
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
                            $(e).hide().wrap('<div class="btn-group ' + $(e).data('class') + '" id="select-group-' + i + '" />');
                            var select = $('#select-group-' + i);
                            var current = ($(e).val()) ? $(e).find('option:selected').text(): '&nbsp;';
                            select.html('<input type="hidden" value="' + $(e).val() + '" name="' + $(e).attr('name') + '" id="' + $(e).attr('id') + '" class="' + $(e).attr('class') + '" data-default="'+$(e).data('default')+'" /><a class="btn dropdown-toggle" data-toggle="dropdown"  href="javascript:;">' + current + '</a><a class="btn dropdown-toggle" data-toggle="dropdown" href="javascript:;"><span class="caret"></span></a><ul class="dropdown-menu"></ul>');
                           
                            $(e).find('option').each(function(o,q) {
                                    select.find('.dropdown-menu').append('<li><a href="javascript:;" data-value="' + $(q).attr('value') + '">' + $(q).text() + '</a></li>');
                                    if ($(q).attr('selected')){select.find('.dropdown-menu li:eq(' + o + ')').click();};
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