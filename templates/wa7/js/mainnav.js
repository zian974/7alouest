/**
 * @package     7aw
 * @subpackage  Templates.7aw
 *
 * @copyright   (C) 2021 Open Source Matters, Inc. <https://www.7alouest.re/>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


( function() {
   

    class MainNav {

        /**
         * @property {Element} hostElement Element de base du menu.
         * Sélecteur: .mainnav .nav
         */
        navFake; 
        
        /**
         * @property {Element} liList1 <LI> de premier niveau du menu
         */
        liList1;
        
        /**
         * @property {Element} navFake <LI> virtuel
         */
        navFake

        /**
         * @property {Element} liList1Active <LI> de premier niveau sélectionné 
         */
        liList1Active;


        constructor( hostId ) {

            this.hostElement = document.querySelector( '.mainnav' );

            this.liList1 = this.hostElement.querySelectorAll( '.nav > .nav-link' );
            this.liList1Active = this.hostElement.querySelector( '.nav > .nav-link.active' );
            this.navFake = this.hostElement.querySelector( '.nav-fake' );

            this.initLi1();

        }

        showFakeLink() {
            document.querySelector( '.mainnav .nav-fake .nav-fake-inner' ).classList.add("show");
            this.styleFakeLink();
        }

        hideFakeLink() {
            document.querySelector( '.mainnav .nav-fake .nav-fake-inner' ).classList.remove("show");
        }

        styleFakeLink = () => {
            if (window.matchMedia("(min-width: 768px)").matches) {
                
                this.navFake.style.left = "calc(" + this.liList1Active.offsetLeft +"px - 1rem)";
                this.navFake.style.width = this.liList1Active.clientWidth +"px";

                this.liList1Active.querySelector( 'a' ).style.color = "white";
                if ( this.liList1Active.querySelector( '.nav-header' ) )
                    this.liList1Active.querySelector( '.nav-header' ).style.color = "white";
            }
        }

        initLi1() {

            


            for( let li1 of this.liList1 ) {
            
                if ( li1.classList.contains("deeper") ) {
        
                    li1.addEventListener('click',  (event) => {

                        for( let li of this.liList1 ) {

                            if( this.hostElement.querySelector('.offcanvas.show') ) {
                                li1.classList.toggle('show');
                            }
                            else {
                                if ( li == li1) {
                                    li.classList.contains('show')?li.classList.remove('show'):li.classList.add('show');
                                }
                                else  {
                                    li.classList.remove('show')
                                } 
                            }                        
                        }

        
                    });
        
                }


                li1.addEventListener('mouseenter', 
                    (event) => {

                        if( this.hostElement.querySelector('.offcanvas.show') ) {
                            this.liList1Active.querySelector( 'a' ).style.color = "white";
                            if ( this.liList1Active.querySelector( '.nav-header' ) )
                                this.liList1Active.querySelector( '.nav-header' ).style.color = "white";
                                return;
                        }

                        event.preventDefault();
                        this.navFake.classList.add("target-hover");

                        this.navFake.style.left = "calc(" + event.target.offsetLeft +"px - 1rem)";
                        this.navFake.style.width = event.target.offsetWidth+"px";
                        if( li1 != this.liList1Active  ) {
                            this.liList1Active.querySelector( 'a' ).style.color = "var(--main-color)";
                            if ( this.liList1Active.querySelector( '.nav-header' ) )
                            this.liList1Active.querySelector( '.nav-header' ).style.color = "var(--main-color)";
                        }
                        else {
                            this.liList1Active.querySelector( 'a' ).style.color = "white";
                            if ( this.liList1Active.querySelector( '.nav-header' ) )
                            this.liList1Active.querySelector( '.nav-header' ).style.color = "white";
                        }
                });
                    

                li1.addEventListener('mouseleave', 
                    (event) => {
                        event.preventDefault();
                        li1.classList.remove('show');
                        this.navFake.classList.remove("target-hover");
                        
                        this.navFake.style.left = "calc(" + this.liList1Active.offsetLeft +"px - 1rem)";
                        this.navFake.style.width = this.liList1Active.clientWidth+"px";

                        this.liList1Active.querySelector( 'a' ).style.color = "white";
                        if ( this.liList1Active.querySelector( '.nav-header' ) )
                            this.liList1Active.querySelector( '.nav-header' ).style.color =  "white";
                });
            }
        }

    }


    let mainnav = new MainNav;  
    
    
    document.addEventListener('readystatechange', () => {
        
        if (window.matchMedia("(min-width: 768px)").matches ) {       
            mainnav.showFakeLink();
        }

    })

    window.addEventListener('resize', () => {
        
        if (window.matchMedia("(min-width: 768px)").matches ) {       
            mainnav.showFakeLink();
        }
        else {      
            mainnav.hideFakeLink();
        }

    })
        
})();