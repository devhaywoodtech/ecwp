import { createRoot } from 'react-dom/client';
import { ThemeProvider } from '@mui/material/styles';
import { Provider } from 'react-redux';
import store from './store';
import Dashboard from './Dashboard.jsx';
import theme from './theme';
import { format, fromUnixTime, formatISO, isBefore } from 'date-fns';
import { convertPhpToJsFormat } from './utils/Helper';
import 'add-to-calendar-button';
import { __ } from '@wordpress/i18n';


const div = document.getElementById('ecwp-calendar');
if (div != null) {
    const root = createRoot(div);
    const admin = div.getAttribute('data-admin'); 
    const term = div.getAttribute('data-term'); 
    const tax = div.getAttribute('data-tax'); 
    const display = div.getAttribute('data-display'); 
    root.render(
        <Provider store={store}>
            <ThemeProvider theme={theme}>
                <Dashboard admin={admin} term={term} tax={tax} display={display} />
            </ThemeProvider>
        </Provider>
    );    
}

const ecwp_startDate = document.getElementById('ecwp_startDate');
if (ecwp_startDate != null) {
    const root = createRoot(ecwp_startDate); 
    const jsDateFormat = convertPhpToJsFormat(ECWP.WPdate);        
    const jsTimeFormat = convertPhpToJsFormat(ECWP.WPtime);    
    const startDate = ecwp_startDate.getAttribute('unix');
    root.render(format(fromUnixTime(startDate), jsDateFormat) + ' ' + format(fromUnixTime(startDate), jsTimeFormat));
    ecwp_startDate.setAttribute('datetime',formatISO(fromUnixTime(startDate), jsDateFormat) + ' ' + format(fromUnixTime(startDate), jsTimeFormat),{format : 'YYYY-MM-DDThh:mm:ss'});
}

const ecwp_endDate = document.getElementById('ecwp_endDate');
if (ecwp_endDate != null) {
    const root = createRoot(ecwp_endDate); 
    const jsDateFormat = convertPhpToJsFormat(ECWP.WPdate);        
    const jsTimeFormat = convertPhpToJsFormat(ECWP.WPtime);    
    const endDate = ecwp_endDate.getAttribute('unix');
    root.render(format(fromUnixTime(endDate), jsDateFormat) + ' ' + format(fromUnixTime(endDate), jsTimeFormat));    
    ecwp_endDate.setAttribute('datetime',formatISO(fromUnixTime(endDate), jsDateFormat) + ' ' + format(fromUnixTime(endDate), jsTimeFormat),{format : 'YYYY-MM-DDThh:mm:ss'});
}

const ecwp_add_calendar = document.getElementById('ecwp_add_calendar');
if (ecwp_add_calendar != null) {
    const root = createRoot(ecwp_add_calendar); 
    const title = ecwp_add_calendar.getAttribute('data-title');
    const address = ecwp_add_calendar.getAttribute('data-address');
    const ecwp_startDate = document.getElementById('ecwp_startDate');
    const ecwp_endDate = document.getElementById('ecwp_endDate');
    const startDate = ecwp_startDate.getAttribute('unix');
    const endDate = ecwp_endDate.getAttribute('unix');

    //Add Expired DIV.
    if(isBefore(new Date(format(fromUnixTime(endDate), 'yyyy-MM-dd kk:mm')), new Date())){
        root.render(
            <React.Fragment>
                <div className="ecwp_expired">{ __('Ended','ecwp') }</div>                            
                <add-to-calendar-button
                    name={title}
                    options="'Google','Apple','iCal','Outlook.com','Yahoo','Microsoft365','MicrosoftTeams'"
                    location={address}
                    startDate={format(fromUnixTime(startDate), 'yyyy-MM-dd')}
                    endDate={format(fromUnixTime(endDate), 'yyyy-MM-dd')}
                    startTime={format(fromUnixTime(startDate), 'kk:mm')}
                    endTime={format(fromUnixTime(endDate), 'kk:mm')}
                    timeZone="currentBrowser"
                    hideCheckmark
                    hideBranding={true}
                    hideRichData
                    inline
                    listStyle='overlay'
                /> 
            </React.Fragment>
        );
    } 
    else{
        root.render(
            <add-to-calendar-button
                name={title}
                options="'Google','Apple','iCal','Outlook.com','Yahoo','Microsoft365','MicrosoftTeams'"
                location={address}
                startDate={format(fromUnixTime(startDate), 'yyyy-MM-dd')}
                endDate={format(fromUnixTime(endDate), 'yyyy-MM-dd')}
                startTime={format(fromUnixTime(startDate), 'kk:mm')}
                endTime={format(fromUnixTime(endDate), 'kk:mm')}
                timeZone="currentBrowser"
                hideCheckmark
                hideBranding={true}
                //hideRichData
                //images={['http://192.168.1.7/ecwppublic/wp-content/uploads/2023/12/Benefits.png','http://192.168.1.7/ecwppublic/wp-content/uploads/2023/12/Benefits.png','http://192.168.1.7/ecwppublic/wp-content/uploads/2023/12/Benefits.png']}
                inline
                listStyle='overlay'
            />       
        );
    }
}