/**
 * Magento
 *
 */

$j(document).ready(function (){

    // ==============================================
    // Header Switchers (language/currency)
    // ==============================================


    $j('.header-button, .switch-show').not('.top-login').on("click", function(e){
            var ul=$j(this).find('ul')
            if(ul.is(':hidden'))
             ul.slideDown()
             ,$j(this).addClass('active')
            else
             ul.slideUp()
             ,$j(this).removeClass('active')
             $j('.header-button, .switch-show').not(this).removeClass('active'),
             $j('.header-button, .switch-show').not(this).find('ul').slideUp()
             $j('.header-button ul li, .switch-show ul li').click(function(e){
                 e.stopPropagation(); 
                });
                return false
        });
        $j(document).on('click',function(){ 
            $j('.header-button, .switch-show').removeClass('active').find('ul').slideUp()
        });
        $j('.block-cart-header, .top-search').on('click',function(){ 
            $j('.header-button').removeClass('active').find('ul').slideUp()
        });

    // ==============================================
    // Back To Top
    // ==============================================
        $j(function () {
         $j(window).scroll(function () {
          if ($j(this).scrollTop() > 100) {
           $j('#back-top').fadeIn();
          } else {
           $j('#back-top').fadeOut();
          }
         });

         // scroll body to 0px on click
         $j('#back-top a').click(function () {
          $j('body,html').stop(false, false).animate({
           scrollTop: 0
          }, 800);
          return false;
         });
        });


    // ==============================================
    // Stick menu init
    // ==============================================

    if(!isMobile) {
        if($j('.main-menu').length){
            $j('.main-menu').tmStickUp() ;
        }
    };



    // ==============================================
    // Superfish Menu
    // ==============================================
    
    jQuery(".skip-nav").on("click", function(){
        jQuery(".sf-menu-phone").slideToggle();
        jQuery(this).toggleClass("active");
    });

    jQuery('li.parent', '.sf-menu-phone').append('<strong></strong>');
    jQuery('.sf-menu-phone li.parent strong').each(function(){
        jQuery(this).on("click", function(){
            if (jQuery(this).attr('class') == 'opened') { 
                jQuery(this).removeClass().parent('li.parent').find('> ul').slideToggle(); 
            } 
                else {
                    jQuery(this).addClass('opened').parent('li.parent').find('> ul').slideToggle();
                }
        });
    })

    jQuery('.swipe-control, .block-cart-header, .top-search').on("click", function(){
        jQuery('.sf-menu-phone').slideUp()
        jQuery('.skip-nav').removeClass('active')
    });


    // ==============================================
    // Carousels init
    // ==============================================

    var screenSize = '';

    var upsellSlidesCount = '';
    var relatedSlidesCount = '';

    var moreSlidesCount = '';
    var moreSliderDirection = '';
    var moreSpaceBetween = '';

    var gallerytopSlidesCount = '';

    /*upsell*/
    $j(".up-sell-carousel").owlCarousel({
            items: 4,
            itemsDesktop : [bp.xlarge,4],
            itemsDesktopSmall : [bp.large,3],
            itemsTablet: [bp.medium,2],
            itemsTabletSmall: false,
            itemsMobile : [bp.xsmall,1],
            pagination: false,
            navigation: true,
            navigationText: ['<div class="button-prev"></div>','<div class="button-next"></div>']

    });

    /*related*/
    $j(".related-carousel").owlCarousel({
            items: 4,
            itemsDesktop : [bp.xlarge,4],
            itemsDesktopSmall : [bp.large,3],
            itemsTablet: [bp.medium,2],
            itemsTabletSmall: false,
            itemsMobile : [bp.xsmall,1],
            pagination: false,
            navigation: true,
            navigationText: ['<div class="button-prev"></div>','<div class="button-next"></div>']

    });

   /*sale*/
    $j(".sale-carousel").owlCarousel({
            items: 4,
            itemsDesktop : [bp.xlarge,4],
            itemsDesktopSmall : [bp.large,3],
            itemsTablet: [bp.medium,2],
            itemsTabletSmall: [bp.medium,2],
            itemsMobile : [bp.xsmall,1],
            pagination: false,
            navigation: true,
            navigationText: ['<div class="sale-prev"></div>','<div class="sale-next"></div>']

    });
            
    /*more-views*/
    if ($j(".more-views-carousel").length) {

        var moreSliderOptions = {
            screenSize: '',
            mode: 'vertical',
            pager: false,
            controls: true,
            slideMargin: 15, 
            minSlides: 5,
            moveSlides: 1,
            maxSlides: 5,
            infiniteLoop: false,
            nextText: '',
            prevText: '',
            slideWidth: 82
            
        }

        var moreViewsSlider = $j('.more-views-carousel').bxSlider(moreSliderOptions);
        
    }

    /*more-views of "center-image" mode*/
    if($j(".gallery-thumbs").length){ 
       
        var galleryThumbsOptions = {
            screenSize: '',
            pager: false,
            controls: true,
            slideMargin: 15, 
            minSlides: 4,
            moveSlides: 1,
            maxSlides: 4,
            infiniteLoop: false,
            nextText: '',
            prevText: '',
            slideWidth: 82
            }
        var galleryThumbsSlider = $j('.gallery-thumbs').bxSlider(galleryThumbsOptions);
        

    }


    $j(window).on('resize.resize_slider', function(){

        var windowWidth = $j(window).width();

        // More-view carousel

        if($j(".more-views-carousel").length){ 

            if (windowWidth > bp.xlarge){
                screenSize = 'xlarge';
                moreSlidesCount = 5;
                moreSpaceBetween = 16;
            }
            if (windowWidth > bp.large && windowWidth <= bp.xlarge){
                screenSize = 'large';
                moreSlidesCount = 4;
                moreSpaceBetween = 10;
            }
            if (windowWidth > bp.medium && windowWidth <= bp.large){
                screenSize = 'medium';
                moreSlidesCount = 3;
                moreSpaceBetween = 21;
            }
            if (windowWidth > bp.small && windowWidth <= bp.medium){
                screenSize = 'small';
                moreSlidesCount = 3;
                moreSpaceBetween = 20;
            }
            if (windowWidth > bp.xsmall && windowWidth <= bp.small){
                screenSize = 'xsmall';
                moreSlidesCount = 3;
                moreSpaceBetween = 20;
            }
            if (windowWidth <= bp.xsmall){
                screenSize = 'xxsmall';
                moreSlidesCount = 3;
                moreSpaceBetween = 12;
            }

             if(moreSliderOptions['screenSize'] != screenSize){

                   moreSliderOptions['screenSize']  = screenSize;
                   moreSliderOptions['slideMargin'] = moreSpaceBetween;
                   moreSliderOptions['minSlides']   = moreSlidesCount;
                   moreSliderOptions['maxSlides']   = moreSlidesCount;

                   moreViewsSlider.reloadSlider(moreSliderOptions);

            }
 
        }


          /*more-views of "center-image" mode*/

        if($j(".gallery-thumbs").length){ 

            if (windowWidth > bp.xlarge){
                screenSize = 'xlarge';
                moreSlidesCount = 4;
                moreSpaceBetween = 16;
            }
            if (windowWidth > bp.large && windowWidth <= bp.xlarge){
                screenSize = 'large';
                moreSlidesCount = 4;
                moreSpaceBetween = 10;
            }
            if (windowWidth > bp.medium && windowWidth <= bp.large){
                screenSize = 'medium';
                moreSlidesCount = 4;
                moreSpaceBetween = 21;
            }
            if (windowWidth > bp.small && windowWidth <= bp.medium){
                screenSize = 'small';
                moreSlidesCount = 4;
                moreSpaceBetween = 20;
            }
            if (windowWidth > bp.xsmall && windowWidth <= bp.small){
                screenSize = 'xsmall';
                moreSlidesCount = 3;
                moreSpaceBetween = 20;
            }
            if (windowWidth <= bp.xsmall){
                screenSize = 'xxsmall';
                moreSlidesCount = 3;
                moreSpaceBetween = 12;
            }
      

             if(galleryThumbsOptions['screenSize'] != screenSize){

                   galleryThumbsOptions['screenSize']  = screenSize;
                   galleryThumbsOptions['slideMargin'] = moreSpaceBetween;
                   galleryThumbsOptions['minSlides']   = moreSlidesCount;
                   galleryThumbsOptions['maxSlides']   = moreSlidesCount;

                   galleryThumbsSlider.reloadSlider(galleryThumbsOptions);

            }
 
        }


    }).trigger('resize.resize_slider');

    // ==============================================
    // Active item of more-views-carousel 
    // ==============================================
    
    if($j('.product-image-thumbs').length){
       
            var slider = $j('.product-image-thumbs');
            $j('li:first-child a', slider).addClass('active');
            $j('li', slider).click(function(){
                $j('li a', slider).removeClass('active');    
                $j('a', this).addClass('active');
            })
        
    }

});




/************************** functions & plugins ************************************/

var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|Windows Phone/i.test(navigator.userAgent), $flag;

