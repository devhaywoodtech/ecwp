import { Icon, calendar } from '@wordpress/icons';
import { format } from 'date-fns'
import { __ } from '@wordpress/i18n';

function NoEvent( props ){ 
    const { current, search } = props;
    let stripText = __('There are no events available for','ecwp')+ " " + format(current,'LLLL, yyyy');
    if(search === true){
        stripText = __('There are no events available','ecwp');
    }    
    return (
        <div className="ecwp_no_events">
            <Icon icon={ calendar } />
            <p className="ecwp_event" dangerouslySetInnerHTML={{__html: stripText}} /> 
        </div>
    )
}
export default NoEvent;