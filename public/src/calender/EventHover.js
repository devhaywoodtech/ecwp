import EventTime from './details/EventTime';
import Location from './details/Location';
import Image from './details/Image';
import Title from './details/Title';

function EventHover({ position, event, dimension, mouseLeave, settings }) {        
    const { clientX, clientY } = position; 
    const { ecwp } = event;

    const componentWidth = 500; // Adjust as needed
    const componentHeight = 300; // Adjust as needed

    const screenWidth = window.innerWidth;
    const screenHeight = window.innerHeight;

    // Calculate the maximum x and y positions to keep the component within the screen
    const maxX = screenWidth - componentWidth; // Adjust as needed
    const maxY = screenHeight - componentHeight; // Adjust as needed

    // Calculate the adjusted position based on the screen dimensions
    let adjustedX = clientX > maxX ? maxX : clientX;
    let adjustedY = clientY > maxY ? maxY : clientY;

    
    const hoverStyle = {
        top: adjustedY,
        left: adjustedX,
    };
  
    return (
        <div className="ecwp_hover" style={hoverStyle} onMouseLeave={mouseLeave}>
            <Title title={event?.title?.rendered} link={event?.link} color={ecwp?.color} settings={settings} /> 
            {
                ( ecwp?.img || ecwp?.excerpt )  && 
                <div className="e_desc">                    
                    <Image img={ecwp?.img} />        
                    <p dangerouslySetInnerHTML={{__html: ecwp?.excerpt}} />
                </div>  
            }                    

            <Location {...ecwp} />
            <EventTime {...ecwp} />         
            
        </div>
    );
}

export default EventHover;