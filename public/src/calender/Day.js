import React, { useEffect, useState, useRef, useCallback, useMemo } from 'react';
import { getDay, getMonth, getYear,  getDaysInMonth,  eachHourOfInterval, startOfDay, endOfDay,  format, isSameDay,  isSunday, getUnixTime, add, sub, fromUnixTime, startOfMonth, endOfMonth, eachDayOfInterval, isSameMonth } from 'date-fns'
import { useDispatch } from 'react-redux';
import { setMonth } from '../reducer/admin';
import EventHover from './EventHover';
import DayEvent from './event/DayEvent';
import Loader from './utils/Loader';

function Day(props) {    
    let { today, loading, events, settings } = props;  
    const step = 2;
    let mon = getMonth(today);
    let year = getYear(today);
    let day_arr = ["S", "M", "T", "W", "T", "F", "S"];
    let dayStart = startOfDay(today);
    let dayEnd = endOfDay(today);
    let numberofdays = getDaysInMonth(today); 
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
     
    //Filter the Events for the Current Date
    const filteredData = events
        .map((item, index) => ({ ...item, eventIndex: index })) 
        .filter((item) => { 
        const startDate = new Date(item.ecwp.startdate * 1000);
        const endDate = new Date(item.ecwp.enddate * 1000);
        const dayStartDate = new Date(getUnixTime(dayStart) * 1000);
        const dayEndDate = new Date(getUnixTime(dayEnd) * 1000);

        return (
            (startDate >= dayStartDate && startDate <= dayEndDate) || // Start date falls within the range
            (endDate >= dayStartDate && endDate <= dayEndDate) || // End date falls within the range
            (startDate <= dayStartDate && endDate >= dayEndDate) // Event spans across the range
        );
    });

    let monthStart = startOfMonth(today);
    let monthEnd = endOfMonth(today);
    const activeDates = useMemo(() => getActiveDates(monthStart, monthEnd, events), [events]);        
    const uniqueDates = [...new Set(activeDates.flat())];
    
    const changeDate = (newValue) => {  
        setHover(false);
        let newVal = new Date(year, mon , newValue);  
        dispatch(setMonth(newVal))
    };

    const nextPrevChange = (newValue) => {
        setHover(false);
        dispatch(setMonth(newValue))
    }

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
  
    useEffect(() => {
        window.addEventListener('load', handleResize)
        window.addEventListener('resize', handleResize)
    
        return () => {
            window.removeEventListener('load', handleResize)
            window.removeEventListener('resize', handleResize)
        }       
    }, [monthRef.current, handleResize]);

    return(        
        <div className="ecwp_day_calender" ref={monthRef}>
            <div className="ecwp_day_toolbar">
                {                        
                    [...Array(numberofdays)].map((_, i) =>  {                          
                        let endofweek = isSunday(new Date(year, mon, (i+1))) ? 'endofweek' : '';
                        let todayClass = isSameDay(new Date(year, mon, (i+1)), today)  ? ' ecwp_isToday' : '';
                        let exists = uniqueDates.some(date => isSameDay(date, new Date(year, mon, (i+1)))) ? ' ecwp_exists' : '';
                        return (
                            <div key={i} className={endofweek + todayClass + exists} onClick={() => changeDate(i+1)}> 
                                <span className='ecwp_day_week'> { day_arr[getDay(new Date(year, mon, (i+1)))] } </span> 
                                <span> {i + 1} </span>
                            </div>
                        )
                    })
                }
            </div>

            {
                loading &&  <Loader />
            }   

            <div className="ecwp_day_events">
                <section className='ecwp_times'>
                {
                    eachHourOfInterval({ start : dayStart, end : dayEnd }, {step : step}).map((val, i) =>  {                              
                        let timeWidth = (100 / (24/step)).toFixed(2); 
                        return(<time style={{ width : timeWidth+'%'}} key={i}> {format(val,"h':00' a")} </time>) 
                    })
                }
                </section>
                <section className='ecwp_day_navigate'>
                    {
                        !isSameDay(today, monthStart) &&
                        <div className="ecwp_previous" onClick={() => nextPrevChange(sub(today, {  days : 1 })) }><p>Previous day</p></div>
                    } 
                    {
                        <div className="ecwp_today"><p>{format(today , 'do MMM')}</p></div>
                    }                   
                    {
                        !isSameDay(today, monthEnd) &&
                        <div className="ecwp_next" onClick={() =>  nextPrevChange(add(today, {  days : 1 })) }><p>Next day</p></div>
                    }                    
                </section>
                <DayEvent 
                    {...props} 
                    filteredData={filteredData} 
                    handleMouseOver={handleMouseOver}
                    settings={settings}
                />
            </div>

            {
                hover && currentEvent && 
                    <EventHover 
                        position={mouseE} event={currentEvent} 
                        dimension={monthRef.current} 
                        mouseLeave={handleMouseLeave} 
                        settings={settings}
                    />
            }

        </div>
    )
}

const getActiveDates = (monthStart, monthEnd, events) => { 
    const activeDates = events.map((item) => ([(isSameMonth(new Date(fromUnixTime(item?.ecwp?.startdate)), monthStart) || isSameMonth(new Date(fromUnixTime(item?.ecwp?.enddate)), monthEnd) ) ? eachDayOfInterval({
        start : isSameMonth(new Date(fromUnixTime(item?.ecwp?.startdate)), monthStart) ?  new Date(fromUnixTime(item?.ecwp?.startdate)) : monthStart,
        end : isSameMonth(new Date(fromUnixTime(item?.ecwp?.enddate)), monthEnd) ?  new Date(fromUnixTime(item?.ecwp?.enddate)) : monthEnd,
    }) : 0]));
    return activeDates.flat();
};

export default Day;