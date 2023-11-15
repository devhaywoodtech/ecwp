import { Icon, mapMarker } from '@wordpress/icons';

function Location(props){
    const { venue  } = props; 
    return(
        <div className="ecwp_day_details ecwp_event_location">
            {
                venue && 
                <div className="ecwp_event_details">
                    <span><Icon icon={ mapMarker } /> <p>{venue}</p></span>                  
                </div>
            } 
        </div>
    )
}

export default Location;