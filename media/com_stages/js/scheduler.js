/**
 * @package     7aw
 * @subpackage  Templates.7aw
 *
 * @copyright   (C) 2021 Open Source Matters, Inc. <https://www.7alouest.re/>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

class ZnAgenda {

    baseContainerId = null;

    baseContainer = null;

    weeksSelect = [];

    weeks = [];

    states = {
        containerWidth: 0,
        cardWidth: 300,
        activeDay: 0,
        inscription: false
    }

    constructor( id, options = { inscription: false } ) {
        
        this.baseContainerId = id;
        
        this.baseContainer = document.getElementById(this.baseContainerId);
        
        this.addNavigationEvents();

        this.url = window.location.origin;
        if ( window.location.host === "localhost" ) {
            this.url += "/7aw-dev";
        }
        
        this.states.inscription = options.inscription;
    }


    setData = ( data ) => {

        this.weeks = data.data;
        console.log(data)
        if ( data.meta && data.meta.weeksSelect) {
            this.weeksSelect = data.meta.weeksSelect;
            if ( this.weeksSelect.length > 1 ) {

                let select = document.createElement('select');
                select.classList.add('form-select');
                select.classList.add('week-select');

                for( let i=0;i<this.weeksSelect.length;i++) {
                    let option = document.createElement('option');
                    option.value = i;
                    let optionTitle = document.createTextNode(this.weeksSelect[i]);
                    option.appendChild(optionTitle);

                    if ( i === 0 ) option.selected ="selected";
                    select.appendChild(option);
                }

                let container = this.baseContainer.querySelector('.week-select-container');
                container.appendChild(select);
                this.addSelectWeekEvent();
            }
        }
        
        if ( data.meta && data.meta.countMax ) {
            this.countMax = data.meta.countMax;
        }
    }


    render = ( idx ) => {
        this.html( this.weeks[idx] );
        this.updateStates();
        this.setContainerPosition( this.states.activeDay );
    }

    addSelectWeekEvent = () => { 
        this.baseContainer.querySelector('.week-select')
            .addEventListener('change', this.weekSelectHasChange);
    }
    
    addNavigationEvents = () => {

        this.baseContainer.querySelector('.navigation-left')
            .addEventListener('click', this.clickMoveSelectDay);
        this.baseContainer.querySelector('.navigation-right')
            .addEventListener('click', this.clickMoveSelectDay);

        window.addEventListener('resize', () => {         
            this.updateStates();
            this.setContainerPosition(this.states.activeDay);
        });

        this.baseContainer.querySelector('.znWeek ').addEventListener('touchstart', this.handleTouchStart, false);        
        this.baseContainer.querySelector('.znWeek ').addEventListener('touchmove', this.handleTouchMove, false);

    }


    updateStates = () => {
        this.states.activeDay = this.getSelectedDay();
        this.states.containerWidth = this.baseContainer.querySelector('.week-container').offsetWidth;
        this.states.cardWidth = this.baseContainer.querySelector('li').offsetWidth;
    }
    
    getSelectedDay = () => {
        let day = false;
        this.baseContainer.querySelectorAll('.znDay').forEach( (el, key) => {
            if( el.classList.contains("active") ) {
                day = key;
            }
        });
        return day;
    };
    

    setContainerPosition = ( index ) => {

        let container = this.baseContainer.querySelector('.znWeek ');
        let value = 
            (this.states.containerWidth / 2) 
            - (index * this.states.cardWidth) 
            - this.states.cardWidth/2 + 'px';

            // let translation = translatable.style.transform.match(/\d+/g)?translatable.style.transform.match(/-{0,1}\d+/g):[0];
            container.style.transform = "translateX("+ (parseInt(value)) +"px)";
    };


    clickDay = ( event ) => {
        let target = event.target.closest(".znDay");
        let dayNumber = parseInt(target.dataset.day);
        this.setContainerPosition( dayNumber );


        let days = this.baseContainer.querySelectorAll('.znDay');
        for( let day of days ) {
            if ( dayNumber === parseInt(day.dataset.day) ) {
                day.classList.add('active');
            }
            else {
                day.classList.remove('active');
            }

        }
    }

    clickMoveSelectDay = ( event ) => {
        let target = event.target.closest(".navigation-left, .navigation-right");
        let offset = parseInt(target.dataset.offset);

        this.moveSelectDay(offset);
    }
    
    moveSelectDay = ( offset ) => {

        let days = this.baseContainer.querySelectorAll('.znDay');
        let next = this.getSelectedDay() + offset;

        if ( offset > 0 && next > days.length - 1 ) {
            return;
        }
        if ( offset < 0 && next < 0 ) {
            return;
        }
        this.setContainerPosition( next );

        for( let day of days ) {
            if ( next === parseInt(day.dataset.day) ) {
                day.classList.add('active');
            }
            else {
                day.classList.remove('active');
            }

        }
    }

    xDown = null;                                                        
    yDown = null;

    getTouches = (evt) => {
        return evt.touches ||             // browser API
                evt.originalEvent.touches; // jQuery
    }                                                     

    handleTouchStart = (evt) =>  {
        const firstTouch = this.getTouches(evt)[0];                                      
        this.xDown = firstTouch.clientX;                                      
        this.yDown = firstTouch.clientY;                                      
    };                                                

    handleTouchMove = (evt) => {
        if ( ! this.xDown || ! this.yDown ) {
            return;
        }

        var xUp = evt.touches[0].clientX;                                    
        var yUp = evt.touches[0].clientY;

        var xDiff = this.xDown - xUp;
        var yDiff = this.yDown - yUp;

        if ( Math.abs( xDiff ) > Math.abs( yDiff ) ) {/*most significant*/
            if ( xDiff > 0 ) {
                /* left swipe */ 
                this.moveSelectDay(1)
            } else {
                /* right swipe */
                this.moveSelectDay(-1)
            }                       
        } else {
            if ( yDiff > 0 ) {
                /* up swipe */ 
            } else { 
                /* down swipe */
            }                                                                 
        }
        /* reset values */
        this.xDown = null;
        this.yDown = null;                                             
    };


    weekSelectHasChange = (e) => {
        this.render(this.baseContainer.querySelector('.week-select').value)
    }


    html = ( data ) => {

        if ( data.length === 0 ) return;
        
        let container = this.baseContainer.querySelector('ul.znWeek ');
        
        this.removeDays( container );

        for ( let day of data.attributes.data ) {
            
            container.appendChild( this.createLI( day ) );
            
            this.baseContainer.querySelectorAll('.znDay').forEach((element)=>{
                element.addEventListener('click', this.clickDay);
            });
            
        }
    }


    removeDays = ( container ) => {
        while (container.firstChild) {    
            let lastChild = container.lastChild;

            lastChild.removeEventListener('click', this.clickDay);
            
            // let button = container.querySelector('.btn-open-day');
            // if ( button ) container.querySelector('.btn-open-day').removeEventListener('click', this.clickDay);

            container.removeChild(lastChild);
        }
    }


    createLI = ( day ) => {

        let li = document.createElement('li');
        li.classList.add('znDay');
        li.classList.add('rounded');
        li.dataset.day = day.dayIndex;
        if ( day.dayActive ) {
            li.classList.add('active');
        }

        let dayTitle = document.createElement('h3');
        let dayTitleLbl = document.createTextNode(day.dayTitle);   
        dayTitle.appendChild(dayTitleLbl);        
        if ( day.daySubTitle ) {
            let html = document.createElement('small');
            let text = document.createTextNode(' ' + day.daySubTitle);
            html.appendChild(text);
            dayTitle.appendChild(html);
        }
        li.appendChild(dayTitle);

        for ( let time of day.timeSlots ) {
            li.appendChild( this.createTimeSlots(time) );
        }
        
        li.addEventListener('click', this.clickDay);

        return li;
    }


    createTimeSlots = ( time ) => {
        let timeSlot = document.createElement('div');
        timeSlot.classList.add('time-slot');
        timeSlot.classList.add('rounded');

        if ( time.schedule ) {
            timeSlot.appendChild( this.createSchedule( time ) );
        }
        if ( time.place ) {
            timeSlot.appendChild( this.createPlace( time ) );
        }
        if ( time.encadrants ) {
            timeSlot.appendChild(this.createMonitor( time ));
        }
        if ( time.type ) {
            timeSlot.appendChild(this.createType( time ));
        }
        if ( time.groupe ) {
            timeSlot.appendChild(this.createGroup( time ));
        }
        if ( time.warning ) {
            timeSlot.appendChild(this.createWarning( time ));
        }
        
        if ( this.states.inscription ) {
            timeSlot.appendChild(this.createCount( time ));
            
            if ( time.ct_restant > 0 )
                timeSlot.appendChild(this.createButton(time) );
        }

        return timeSlot;
    }


    createButton = ( time ) => {
        
        let btnInscription = document.createElement('div');
        btnInscription.classList.add('btn-inscription');

        let btn = document.createElement('a');

        // btn.classList.add('button');
        // btn.setAttribute("type", "button");
        // btn.setAttribute('data-bs-toggle', "modal"); 
        // btn.setAttribute('data-bs-target', '#modal-box');
        // btn.setAttribute('onclick', "return false;");

        btn.classList.add('btn-open-day');
        btn.classList.add('mat-raised-button');
        btn.classList.add('inverse');
        btn.dataset.slot = time.id;

        btn.href = this.url + 
            '/index.php?option=com_stages&view=stagiaireform&layout=edit&' +
            'slot_id='+ time.id+'&Itemid=';
        btn.appendChild(document.createTextNode('S\'inscrire'));

        return btn;
    }

    openModal = () => {
        alert('e')
    }

    createSchedule = (time) => {
        let info = document.createElement('div');
        info.classList.add('znInfo');
        
        let i = document.createElement('i');
        i.classList.add('material-icons');
        i.appendChild(document.createTextNode('schedule'));
        
        let strong = document.createElement('strong');
        strong.appendChild( document.createTextNode(time.schedule));
        
        info.appendChild(i);
        info.appendChild(strong);

        return info;
    }


    createPlace = (time) => {
        let info = document.createElement('div');
        info.classList.add('znInfo');
        info.classList.add('place');

        let i = document.createElement('i');
        i.classList.add('material-icons');
        i.appendChild(document.createTextNode('place'));

        info.appendChild(i);
        info.appendChild(document.createTextNode(time.place));

        return info;
    }


    createMonitor = (time) => {
        let info = document.createElement('div');
        info.classList.add('znInfo');

        let i = document.createElement('i');
        i.classList.add('material-icons');
        i.appendChild(document.createTextNode('person'));

        info.appendChild(i);
        info.appendChild(document.createTextNode(time.encadrants));

        return info;
    }


    createType = (time) => {
        let info = document.createElement('div');
        info.classList.add('znInfo');
        info.classList.add('climb-type');
        
        let strong = document.createElement('strong');
        strong.appendChild( document.createTextNode(time.type));

        info.appendChild(strong);

        return info;
    }


    createGroup = (time) => {
        let info = document.createElement('div');
        info.classList.add('znInfo');
        info.classList.add('groupe');
        info.classList.add(time.groupe.color);
        info.classList.add('mb-2');

        let spanLbl = document.createElement('span');
        spanLbl.appendChild(document.createTextNode(time.groupe.label));

        info.appendChild(spanLbl);

        if ( time.groupe.sublabel ) {
            let spanSubLbl = document.createElement('span');
            spanSubLbl.appendChild(document.createTextNode(time.groupe.sublabel));
            info.appendChild(spanSubLbl);
        }

        return info;
    }


    createWarning = (time) => {
        let info = document.createElement('div');
        info.classList.add('text-muted');
        info.classList.add('text-center');
        info.classList.add('m-1');

        let strong = document.createElement('strong');

        let small = document.createElement('small');
        small.classList.add('text-warning');
        small.appendChild( document.createTextNode(time.warning));

        strong.appendChild(small);

        info.appendChild(strong);

        return info;
    }


    createCount = (time) => {
        let info = document.createElement('div');
        info.classList.add('text-danger');
        info.classList.add('text-center');
        info.classList.add('m-1');

        let strong = document.createElement('strong');
        strong.appendChild( document.createTextNode( time.ct_restant ));

        info.appendChild( document.createTextNode("Il reste ") );
        info.appendChild( strong );
        info.appendChild( document.createTextNode(" place(s)") );

        return info;
    }

}
