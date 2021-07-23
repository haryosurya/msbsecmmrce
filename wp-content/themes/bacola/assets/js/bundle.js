(function ($) {
  "use strict";

  var doc = $( document );
  var win = $( window );

  var BACOLA_APP = {
    init: function() {
      this.dom();
      this.dropdownParent();
      this.allCategories();
      this.siteScroll();
      this.mainMenu();
      this.mobileMenu();
      this.mobileSearch();
      this.productSorting();
      this.productHover();
      this.productQty();
      this.addToCartQuantity();
      this.checkboxList();
      this.sidebarFilter();
      this.myAccountMenu();
      this.loginForm();
      $('[data-toggle="tooltip"]').tooltip();
    },
    dom: function() {
      var body = $( 'body' );
      var html = $( 'html' );
    },
    dropdownParent: function() {
      var content = $( '.dropdown-parent' );
      var categories = $( '.dropdown-categories' );

      var h = categories.outerHeight();
      if ( content.length ) {
        content.css( 'height', h );
      }
    },
    allCategories: function() {
      var content = $( '.header-nav .all-categories' );
      var button = content.find( '> a' );
      var subMenu = content.find( '.dropdown-categories' );

      button.on( 'click', function(e) {
        e.preventDefault();
        if ( $(this).parent().hasClass( 'click' ) ) {
          subMenu.toggleClass( 'active' );
        }
      });
    },
    siteScroll: function() {
      var siteScroll = $( '.site-scroll' );
      siteScroll.each( function() {
        const ps = new PerfectScrollbar( $( this )[0], {
          suppressScrollX: true,
        });
      });
    },
    mainMenu: function() {
      var subMenuItem = $( '.primary-menu .sub-menu .menu-item' ).find( '> a' );
      var textWrapper = $( '<span class="text"></span>' );
      subMenuItem.wrapInner( textWrapper );

      const spacing = () => {
        var containerWidth = $( '.header-wrapper > .container' ).width();
        var windowWidth = $(window).width();

        var spacing = windowWidth - containerWidth;
        var megaSubmenu = $( '.primary-menu .mega-menu > .sub-menu' );
        megaSubmenu.css( 'padding-left', spacing / 2 );
        megaSubmenu.css( 'padding-right', spacing / 2 );
      }
	  
	  spacing();
	  
      $( window ).on( 'load', () => {
        spacing();
      });

      $( window ).on( 'resize', () => {
        spacing();
      });

      var megaMenu = $( '.primary-menu .mega-menu' );
      var siteMask = $( '.site-overlay' );

      megaMenu.on( 'hover', () => {
        siteMask.toggleClass( 'active-for-mega' );
      });
    },
    mobileMenu: function() {
      var canvasMenu = $( '.site-canvas' );
      var siteOverlay = $( '.site-overlay' );
      var canvasButton = $( '.header-canvas > a' );
      var canvasClose = $( '.site-canvas .close-canvas' );

      var tl = gsap.timeline( { paused: true, reversed: true } );
      tl.set( canvasMenu, {
        autoAlpha: 1
      }).to( canvasMenu, .5, {
        x:0,
				ease: 'power4.inOut'
      }).to( siteOverlay, .5, {
        autoAlpha: 1,
        ease: 'power4.inOut'
      }, "-=.5");

      canvasButton.on( 'click', function(e) {
				e.preventDefault();
        siteOverlay.addClass( 'active' );
				tl.play();
			});

      canvasClose.on( 'click', function(e) {
				e.preventDefault();
				tl.reverse();
        setTimeout( function() { 
          siteOverlay.removeClass( 'active' );
        }, 1000);
			});

      var menuChildren = $( '.canvas-menu .menu-item-has-children' );
      menuChildren.append( '<span class="menu-dropdown"><i class="klbth-icon-down-open-big"></i></span>' );

      $( '.canvas-menu .menu-item-has-children .menu-dropdown' ).on( 'click', function(e) {
        e.preventDefault();

        var link = $(this);
        var closestUL = link.closest( 'ul' );
        var activeItem = closestUL.find( '.active' );
        var closestLI = link.closest( 'li' );
        var linkClass = closestLI.hasClass( 'active' );
        var count = 0;

        const resetAnimation = () => {
          activeItem.removeClass( 'active' );
        }

        gsap.to( closestUL.find( 'ul' ), .5, { height: 0, ease: 'power4.inOut', onComplete: resetAnimation() });

        if ( !linkClass ) {
          gsap.to( closestLI.children( 'ul' ), .5, { height: 'auto', ease: 'power4.inOut' } );
          closestLI.addClass( 'active' );
        }
      });

    },
    mobileSearch: function() {
      var searchButton = $( '.header-mobile-nav .menu-item .search' );
      var searchHolder = $( '.header-search' );

      if ( searchButton.length ) {
        searchButton.on( 'click', function(e) {
          e.preventDefault();
          $(this).toggleClass( 'active' );
          searchHolder.toggleClass( 'active' );
        });
      }
    },

    productSorting: function() {
      var container = $( '.filterSelect' );
      container.each(function() {
        var filterClass = $(this).data('class');
        var filterSelect = $(this).select2({
          minimumResultsForSearch: Infinity,
          dropdownAutoWidth: true,
          dropdownCssClass: filterClass,
        });
      });
    },
    productHover: function() {
      var product = $( '.products .product' );

      product.each( function(e) {
        var fadeBlock = $(this).find( '.product-fade-block' );
        var contentBlock = $(this).find( '.product-content-fade' );
        var outerHeight = 0;

        if ( fadeBlock.length ) {
          fadeBlock.each( function(e) {
            var self = $(this);
            outerHeight += self.outerHeight();
    
            contentBlock.css( 'marginBottom', -outerHeight );
          });
        }
      });
    },
    productQty: function() {
      function qty() {
        var container = $( '.quantity:not(.ajax-quantity)' );
        container.each( function() {
          var self = $( this );
          var buttons = $( this ).find( '.quantity-button' );
		  
		  $("form.cart.grouped_form .input-text.qty").attr("value", "0");

          buttons.each( function() {
            $(this).on( 'click', function(event) {
              var qty_input = self.find( '.input-text.qty' );
              if ( $(qty_input).prop('disabled') ) return;
              var qty_step = parseFloat($(qty_input).attr('step'));
              var qty_min = parseFloat($(qty_input).attr('min'));
              var qty_max = parseFloat($(qty_input).attr('max'));


              if ( $(this).hasClass('minus') ){
                var vl = parseFloat($(qty_input).val());
                vl = ( (vl - qty_step) < qty_min ) ? qty_min : (vl - qty_step);
                $(qty_input).val(vl);
              } else if ( $(this).hasClass('plus') ) {
                var vl = parseFloat($(qty_input).val());
                vl = ( (vl + qty_step) > qty_max ) ? qty_max : (vl + qty_step);
                $(qty_input).val(vl);
              }

              qty_input.trigger( 'change' );
            });
          });
        });
      }

      qty();
      $('body').on( 'updated_cart_totals', qty );
    },
    addToCartQuantity: function() {
      var container = $( '.cart-with-quantity' );
      container.each( function() {
        var self = $(this);
        var button = self.find( '.ajax_add_to_cart' );
        var quantity = self.find( '.ajax-quantity' );

        button.on( 'click', function(e) {
          e.preventDefault();
          $(this).hide();
          addQty();
        });

        function addQty() {
          quantity.css( 'display', 'flex' );

        }

        function showButton() {
          button.css( 'display', 'flex' );
          quantity.hide();
          quantity.find( '.input-text.qty' ).val(0);
		  
        }
		
          var sbuttons = quantity.find( '.quantity-button' );
          sbuttons.each( function() {
            $(this).on( 'click', function(event) {
              var qty_input = quantity.find( '.input-text.qty' );
              if ( $(qty_input).prop('disabled') ) return;
              var qty_step = 1;
              var qty_min = parseFloat($(qty_input).attr('min'));
              var qty_max = parseFloat($(qty_input).attr('max'));


              if ( $(this).hasClass('minus') ){
                var vl = parseFloat($(qty_input).val());
                vl = ( (vl - qty_step) < qty_min ) ? qty_min : (vl - qty_step);
                $(qty_input).val(vl);
				
				$(this).closest('.product-button-group').find('a.button').attr('data-quantity', vl);
				
              } else if ( $(this).hasClass('plus') ) {
                var vl = parseFloat($(qty_input).val());
                vl = ( (vl + qty_step) > qty_max ) ? qty_max : (vl + qty_step);
                $(qty_input).val(vl);
				$(this).closest('.product-button-group').find('a.button').attr('data-quantity', vl);
				
              }

              if ( qty_input.val() === '0' ) {
                showButton();
				$(this).closest('.product-button-group').find('a.button').attr('data-quantity', '1');
              }

              qty_input.trigger( 'change' );
            });
          });
		  
		  
      });
    },
    checkboxList: function() {
      var container = $( '.site-checkbox-lists.hidden-sub' );
      var menu = $( '.site-checkbox-lists > .site-scroll > ul' );
      var menuChildren = container.find( '.cat-parent' );
      menuChildren.append( '<span class="menu-dropdown"><i class="klbth-icon-plus"></i></span>' );

      var menuHeight = menu.height();

      if ( menuHeight > 300 ) {
        menu.addClass( 'scroll-active' )
      }

      container.each( function() {
        $(this).find( '.menu-dropdown' ).on( 'click', function(e) {
          e.preventDefault();
  
          var link = $(this);
          var closestUL = link.closest( 'ul' );
          var activeItem = closestUL.find( '.active' );
          var closestLI = link.closest( 'li' );
          var linkClass = closestLI.hasClass( 'active' );
          var count = 0;
  
          const resetAnimation = () => {
            activeItem.removeClass( 'active' );
          }
  
          gsap.to( closestUL.find( 'ul' ), .5, { height: 0, ease: 'power4.inOut', onComplete: resetAnimation() });
  
          if ( !linkClass ) {
            gsap.to( closestLI.children( 'ul' ), .5, { height: 'auto', ease: 'power4.inOut' } );
            closestLI.addClass( 'active' );
          }
        });
      });
    },
    sidebarFilter: function() {
      var sidebar = $( '#sidebar' );
      var button = $( '.filter-toggle' );
      var siteOverlay = $( '.site-overlay' );
      var close = $( '.close-sidebar' );

      var tl = gsap.timeline( { paused: true, reversed: true } );
      tl.set( sidebar, {
        autoAlpha: 1
      }).to( sidebar, .5, {
        x:0,
				ease: 'power4.inOut'
      }).to( siteOverlay, .5, {
        autoAlpha: 1,
        ease: 'power4.inOut'
      }, "-=.5");

      button.on( 'click', function(e) {
				e.preventDefault();
        siteOverlay.addClass( 'active' );
				tl.reversed() ? tl.play() : tl.reverse();
			});

      close.on( 'click', function(e) {
				e.preventDefault();
				tl.reverse();
        setTimeout( function() { 
          siteOverlay.removeClass( 'active' );
        }, 1000);
			});
    },

    myAccountMenu: function() {
      var container = $( '.my-account-navigation' );

      if ( container.length ) {
        var button = $( '.account-toggle-menu' );

        button.on( 'click', function() {
          container.toggleClass(  'dropdown');
        });
      }
    },
    loginForm: function() {
      var tab = $( '.login-page-tab li' );
      var forms = $( '.login-form-container' );

      var removeClass = () => {
        for ( var i = 0; i < tab.length; i++ ) {
          if ( tab[i].children[0].classList.contains( 'active' ) ) {
            tab[i].children[0].classList.remove('active');
          }
        }
      }

      for ( var i = 0; i < tab.length; i++ ) {
        const button = tab[i].children[0];
        button.addEventListener( 'click', (event) => {
          event.preventDefault();
          if ( !event.target.classList.contains( 'active' ) ) {
            removeClass();
            event.target.classList.add( 'active' );
            forms.toggleClass( 'show-register-form' );
          }
        });
      }
    },

  }

  doc.ready( () => {
    BACOLA_APP.init();
  });
  
	$(document).ready(function() {
		$(".flex-control-thumbs").addClass("product-thumbnails");
		
		if ($(".woocommerce-product-gallery").hasClass("vertical") && $(window).width() > 992) {
			var verti = true;
		} else {
			var verti = false;
		}

		$('.product-thumbnails').slick({
		  dots: false,
		  arrows: true,
		  prevArrow: '<div class="prev"><i class="far fa-angle-left"></i></div>',
		  nextArrow: '<div class="next"><i class="far fa-angle-right"></i></div>',
		  autoplay: false,
		  Speed: 5000,
		  slidesToShow: 6,
		  slidesToScroll: 1,
		  focusOnSelect: true,
		  vertical: verti,
		});

		$(".flex-viewport, .flex-control-nav" ).wrapAll( "<div class='slider-wrapper'></div>" );
		
		if ( $('.product-brand > *').length < 1 ) {
			$('.product-brand').remove();
		}
		
	});

	$(window).load(function(){
		$('.site-loading').fadeOut('slow',function(){$(this).remove();});
	});

    $(window).scroll(function() {
        $(this).scrollTop() > 135 ? $("header.site-header").addClass("sticky-header") : $("header.site-header").removeClass("sticky-header")
    });
}(jQuery));