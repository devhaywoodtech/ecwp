import React from 'react';
import { format, fromUnixTime, isBefore } from 'date-fns'
import Title from '../calender/details/Title';
import EventTime from './details/EventTime';
import Location from './details/Location';
import Image from './details/Image';
import Loader from './utils/Loader';
import { __ } from '@wordpress/i18n';

function List(props) {
    let { loading, events, settings } = props;   
    return(        
        <div className="ecwp_list_calender">
        {
            loading &&  <Loader />
        }
        {                                                                                               
            events && events.map((val, key) => {                    
                return (
                    <div className='ecwp_list' key={key}>
                        <div className='ecwp_list_date'>
                            <p>{format(fromUnixTime(val?.ecwp?.startdate), 'eee')}</p>
                            <p style={{ color : val?.ecwp?.color  }}>{format(fromUnixTime(val?.ecwp?.startdate), 'dd')}</p>
                            <p>{format(fromUnixTime(val?.ecwp?.startdate), 'LLL')}</p>
                        </div>    
                        <div className='ecwp_list_details'>   
                            <Title title={val?.title?.rendered} link={val?.link} settings={settings} />          
                            <div className='ecwp_list_location'>                                
                                <EventTime {...val?.ecwp} />
                                <Location {...val?.ecwp} />
                            </div>
                            <p className='ecwp_desc' dangerouslySetInnerHTML={{__html: val?.ecwp?.excerpt}} />                                
                        </div>
                        <Image img={val?.ecwp?.img} />
                        { 
                            isBefore(new Date(format(fromUnixTime(val?.ecwp?.enddate), 'yyyy-MM-dd kk:mm')), new Date()) &&
                            <div className="ecwp_expired">{ __('Ended','ecwp') }</div>                            
                        }
                    </div>
                )
            })
        }      
        </div>
    )
}

export default List;