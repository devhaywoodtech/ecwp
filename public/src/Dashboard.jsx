import React, { useEffect, useState } from 'react';
import Box from '@mui/material/Box';
import { useSelector, useDispatch } from 'react-redux';
import { getYear, getMonth } from 'date-fns'
import CalendarToolbar from './Toolbar.jsx';
import NoEvent from './calender/NoEvent';
import Month from './calender/Month';
import Day from './calender/Day';
import ListEvent from './calender/List';
import { val } from './reducer/admin';
import { fetchEvents, fetchSettings } from './reducer/admin';

const convertHexToRGBA = (hexCode, opacity = 1) => {  
    let hex = hexCode.replace('#', '');
    
    if (hex.length === 3) {
        hex = `${hex[0]}${hex[0]}${hex[1]}${hex[1]}${hex[2]}${hex[2]}`;
    }    
    
    const r = parseInt(hex.substring(0, 2), 16);
    const g = parseInt(hex.substring(2, 4), 16);
    const b = parseInt(hex.substring(4, 6), 16);
    
    // Backward compatibility for whole number based opacity values. 
    if (opacity > 1 && opacity <= 100) {
        opacity = opacity / 100;   
    }

    return `rgba(${r},${g},${b},${opacity})`;
};

export default function Dashboard(props) {   
    const value = useSelector(val);     
    const dispatch = useDispatch();
    const year = getYear(new Date()); 
    const mon = getMonth(new Date())+1; 
    const { admin, term, tax } = props;  
    const { events, today, latest, loading, search, settings } = value;
    const [ show, setShow ]  = useState(false);
    
    /***
     * Fetch Events from the API
     */
    useEffect(() => {
        dispatch(fetchSettings(props)).then((result) => {
            dispatch(fetchEvents({year, mon, tax, term})).then((res) => {
                setShow(true);                   
            });
        });       
    }, []);      

    return (
        <Box sx={{ display: { xs: 'block', sm: 'flex' } }}>
            <Box component="main" sx={{ flexGrow: 1, mt: admin == 1 ? 3 : 0 }}>     
                {
                    settings && 
                    <React.Fragment>
                        <CalendarToolbar 
                            current={today} 
                            view={settings?.default_view} 
                            searchEnable={settings?.search}
                            latestEvent={latest} {...props} 
                        />
                        {
                            !loading && show && events.length === 0 &&
                            <NoEvent current={today} search={search} />
                        }
                        {
                            settings?.default_view && settings?.default_view === 'month' && 
                            <Month {...value} convertHexToRGBA={convertHexToRGBA} />
                        }
                        {
                            settings?.default_view && settings?.default_view === 'day' && 
                            <Day {...value} convertHexToRGBA={convertHexToRGBA} />
                        } 
                        {
                            settings?.default_view && settings?.default_view === 'list' && 
                            <ListEvent {...value} convertHexToRGBA={convertHexToRGBA} />
                        } 
                    </React.Fragment>
                }                                     
            </Box>
        </Box>
    );
}