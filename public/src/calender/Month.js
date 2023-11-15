import React, { useEffect, useState, useRef, useCallback } from 'react';
import { getDay, getMonth, getYear, isToday, getDaysInMonth, getWeeksInMonth, fromUnixTime, startOfDay, endOfDay,  isWithinInterval, isEqual } from 'date-fns'
import { useDispatch } from 'react-redux';
import { setDay } from '../reducer/admin';
import EventHover from './EventHover';
import Title from '../calender/details/Title';
import Loader from './utils/Loader';

function Month(props) {

    let { today, loading, events, convertHexToRGBA, settings } = props;
    let mon = getMonth(today);
    let year = getYear(today);
    let day_arr = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
    let numberofdays = getDaysInMonth(today); 
    let startofmonth = getDay(new Date(year, mon, 1));
    let numberofweeks = getWeeksInMonth(today);
    let afterdays = (numberofweeks * 7) - (numberofdays + startofmonth);
    const [hover, setHover] = useState(false);
    const [currentEvent, setCurrentEvent] = useState([]);
    const [mouseE, setMouseE] = useState([]);
    const dispatch = useDispatch();    

    const [width, setWidth] = useState(0)
    const [height, setHeight] = useState(0)
    const monthRef = useRef(null);
    const handleResize = useCallback(() => {
        setWidth(monthRef.current.offsetWidth)
        setHeight(monthRef.current.offsetHeight)
    }, [monthRef])


    const handleMouseOver = (e,index) => {
        getEventByIndex(index)
        setMouseE(e)
        setHover(true);
    };
    
    const handleMouseLeave = (e) => {
        setHover(false);        
    };

    const getEventByIndex = (index) => {
        setCurrentEvent(events[index]);
    }

    const gotoDay = (day) => {
        setHover(false);
        dispatch(setDay(new Date(year, mon, day))); 
    }

    useEffect(() => {
        window.addEventListener('load', handleResize)
        window.addEventListener('resize', handleResize)
    
        return () => {
            window.removeEventListener('load', handleResize)
            window.removeEventListener('resize', handleResize)
        }       
    }, [monthRef.current, handleResize]);

    return(
        <React.Fragment> 
            <div className="ecwp_month_calender" ref={monthRef}>
                <div style={{ display:'flex', justifyContent :'space-between' }}>
                    {
                        day_arr.map((day, index) => (
                            <div className='ecwp_week' key={index}>
                                <div className='ecwp_title'>{day.toLocaleUpperCase()}</div>
                            </div>
                        ))
                    }
                </div>
                <div className='ecwp_month'>
                    {
                        [...Array(startofmonth)].map((_, i) => (
                            <div className='ecwp_grid' key={i}>
                                <div className='ecwp_paper'>
                                    <div className='ecwp_box'>
                                        &nbsp;
                                    </div>
                                </div>
                            </div>
                        ))
                    }
                    {
                        [...Array(numberofdays)].map((_, i) =>  {    
                            let currentDayEvents = 0; 
                            let todayClass =  isToday(new Date(year, mon, (i+1))) ? 'ecwp_paper today' : 'ecwp_paper' ;                       
                            return(
                                <div className='ecwp_grid' key={i}>
                                    <div className={todayClass}>
                                        <div className='ecwp_box'>                                       
                                            <p className='ecwp_date' onClick={() => gotoDay(i + 1) }>{i + 1}</p>
                                           
                                            <div className='ecwp_e_container'>                                            
                                                {                                                                                               
                                                    events && events.map((val, key) => {
                                                        let startDate = startOfDay(fromUnixTime(parseInt(val?.ecwp?.startdate)));
                                                        let currentDate = new Date(year, mon, i + 1);

                                                        let gColor1 = convertHexToRGBA(val?.ecwp?.color,0.15);
                                                        let gColor2 = convertHexToRGBA(val?.ecwp?.color,0.05);
                                                        let gradient = 'linear-gradient(to right, '+gColor1+', '+gColor2+')';
                                                         

                                                        //Add Highlighter with title & width percentage
                                                        if(isWithinInterval(currentDate, { start : startDate, end : startDate })){
                                                            let eventStartDay = isEqual(currentDate, startDate);                                                                                
                                                            currentDayEvents++;   
                                                                                                    
                                                            if(currentDayEvents > 3){                                     
                                                                return(
                                                                    <div key={key} className="ecwp_multiple" onClick={() => gotoDay(i + 1) }>{'1+'}</div>
                                                                );               
                                                            } 

                                                            //If the Event Starts display with border
                                                            if(eventStartDay){                                              
                                                                return (                                                                      
                                                                    <div key={key} onMouseOver={(e) => handleMouseOver(e,key)} className="border_highlight" style={{background : gradient, borderLeft : eventStartDay?  '5px solid '+val?.ecwp?.color : 'none'}}>
                                                                        <Title title={val?.title?.rendered} strip={15} settings={settings} />
                                                                    </div> 
                                                                )
                                                            }                                                            
                                                        }          
                                                                                               
                                                    })
                                                }
                                            </div>

                                            <div className='ecwp_c_container'>       
                                                {                                           
                                                    events && events.map((val, key) => {
                                                        let startDate = startOfDay(fromUnixTime(parseInt(val?.ecwp?.startdate)));
                                                        let endDate = endOfDay(fromUnixTime(parseInt(val?.ecwp?.enddate)));
                                                        let currentDate = new Date(year, mon, i + 1);
                                                        let eventStart = isEqual(currentDate, startDate); 

                                                        let gColor1 = convertHexToRGBA(val?.ecwp?.color,0.9);
                                                        let gColor2 = convertHexToRGBA(val?.ecwp?.color,0.2);
                                                        let gradient = 'linear-gradient(to right, '+gColor1+', '+gColor2+')';
                                                         
                                                        if(!eventStart && isWithinInterval(currentDate, { start : startDate, end : endDate })){
                                                           
                                                            return (
                                                                <div key={key} onMouseOver={(e) => handleMouseOver(e,key)} className="circle_highlight" style={{background : gradient }}></div>
                                                            )
                                                        }


                                                    })
                                                }
                                            </div>  

                                        </div>
                                    </div>
                                </div>
                            )
                        })
                    }
                    {
                        [...Array(afterdays)].map((_, i) => (
                            <div className='ecwp_grid' key={i}>
                                <div className='ecwp_paper'>
                                    <div className='ecwp_box'>
                                        &nbsp;
                                    </div>
                                </div>
                            </div>
                        ))
                    }

                {
                    loading &&  <Loader />
                }
                </div>

                {
                    hover && currentEvent && 
                        <EventHover 
                            position={mouseE} 
                            event={currentEvent} 
                            dimension={monthRef.current} 
                            mouseLeave={handleMouseLeave} 
                            settings={settings}
                        />
                }

            </div>
        </React.Fragment>
    )
}


export default Month;