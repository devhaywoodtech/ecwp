import { startOfDay, endOfDay, fromUnixTime, isSameDay, differenceInMinutes, differenceInHours, getHours, getMinutes } from 'date-fns'
import { __ } from '@wordpress/i18n';
import Title from '../details/Title';
import Image from '../details/Image';
import EventTime from '../details/EventTime';

function DayEvent(props) {
    const { filteredData, today, handleMouseOver, convertHexToRGBA, settings } = props;
    let dayStart = startOfDay(today);
    let dayEnd = endOfDay(today);
    return(
        <section className='ecwp_events'>
            {
                filteredData.length === 0 &&
                <p className='ecwp_no_day_event'>{ __('No events were found for the selected day.','ecwp') }</p>
            }
            {                                                                                               
                filteredData && filteredData.map((val, key) => {
                    let difference = 5;
                    let startOrEnd = '';
                    let eventWidth = 50;
                    let marginleft = 50;
                    let eventBorder = '';
                    let starthour = getHours(fromUnixTime(parseInt(val?.ecwp?.startdate)));
                    let startmins = ((getMinutes(fromUnixTime(parseInt(val?.ecwp?.startdate))) / 60)).toFixed(2);
                    starthour += parseFloat(startmins);

                    if(isSameDay(fromUnixTime(parseInt(val?.ecwp?.startdate)),fromUnixTime(parseInt(val?.ecwp?.enddate)))){
                        startOrEnd = 'sameday';
                        difference = differenceInHours(fromUnixTime(parseInt(val?.ecwp?.enddate)),fromUnixTime(parseInt(val?.ecwp?.startdate)));
                        eventWidth = 100/(24/difference).toFixed(2); 
                        marginleft = starthour * 100/24;    
                        eventBorder = { borderTopRightRadius : 5, borderBottomRightRadius : 5 }
                    }
                    else if (isSameDay(fromUnixTime(parseInt(val?.ecwp?.startdate)),today)){
                        startOrEnd = 'startday'; 
                        difference = (differenceInMinutes(dayEnd,fromUnixTime(parseInt(val?.ecwp?.startdate))) / 60 ).toFixed(2);
                        eventWidth = 100/(24/difference).toFixed(2);
                        marginleft = starthour * 100/24;    
                    }
                    else if (isSameDay(fromUnixTime(parseInt(val?.ecwp?.enddate)),today)){
                        startOrEnd = 'endday';
                        difference = differenceInHours(fromUnixTime(parseInt(val?.ecwp?.enddate)),dayStart);
                        eventWidth = 100/(24/difference).toFixed(2);
                        marginleft = 0;  
                        eventBorder = { borderTopRightRadius : 5, borderBottomRightRadius : 5 }
                    }
                    else{
                        //Events Pass through the Date.
                        marginleft = 0;
                        eventWidth = 100;
                    }                
                
                    let bordercolor = convertHexToRGBA(val?.ecwp?.color,0.3);
                    let gColor1 = convertHexToRGBA(val?.ecwp?.color,0.20);
                    let gColor2 = convertHexToRGBA(val?.ecwp?.color,0.01);
                    let gradient = 'linear-gradient(to right, '+gColor1+', '+gColor2+')';

                    let eventStyle = {background : gradient, border : '1px solid '+bordercolor, borderLeft : '5px solid '+val?.ecwp?.color, width : eventWidth + '%', marginLeft : marginleft + '%',...eventBorder}

                    if(eventWidth <= 10){
                        eventStyle = {...eventStyle, height : 200, textOrientation : 'mixed', writingMode : 'vertical-rl', padding : 5 }
                    }

                    return (                                                                      
                        <div key={key} className="border_highlight" style={eventStyle} onClick={(e) => handleMouseOver(e,val?.eventIndex)}>
                            {
                                val?.ecwp?.img &&  eventWidth > 25 && 
                                <div className="ecwp_day_img">
                                    <Image img={val?.ecwp?.img} />
                                </div>
                            }   
                            <div className="ecwp_day_img">                      
                                <Title title={val?.title?.rendered} settings={settings} /> 
                                {
                                    eventWidth > 24 && 
                                        <EventTime {...val?.ecwp} />
                                }     
                            </div>                                                    
                        </div> 
                    )

                })
            }
        </section>
    )
}

export default DayEvent;