import { useMemo } from 'react';
import {  format, fromUnixTime } from 'date-fns'
import { Icon, calendar, styles } from '@wordpress/icons';
import { convertPhpToJsFormat } from '../../utils/Helper';

function EventTime(props){
    const jsDateFormat = useMemo(() => convertPhpToJsFormat(ECWP.WPdate), []);        
    const jsTimeFormat = useMemo(() => convertPhpToJsFormat(ECWP.WPtime), []);    

    const { startdate, enddate  } = props;  
    return(
        <div className="ecwp_day_details">
            {
                startdate && 
                <div className="ecwp_event_details">
                    <span><Icon icon={ calendar } /> <p>{format(fromUnixTime(startdate), jsDateFormat)}</p></span>
                    <span><Icon icon={ styles } /> <p>{format(fromUnixTime(startdate), jsTimeFormat)}</p></span>                  
                </div>
            }            
            {
                enddate && 
                <div className="ecwp_event_details">
                    <span><Icon icon={ calendar } /> <p>{format(fromUnixTime(enddate), jsDateFormat)}</p></span>
                    <span><Icon icon={ styles } /> <p>{format(fromUnixTime(enddate), jsTimeFormat)}</p>       </span>                
                </div>
            } 
        </div>
    )
}
export default EventTime;