import { createSlice, createAsyncThunk } from '@reduxjs/toolkit'
import { add, sub, startOfMonth, getMonth, getYear, getDate, fromUnixTime } from 'date-fns'

const API_URL = ECWP.rest_url;
const per_page = 100;
const axios = require("axios");


const userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;

/****
 * Fetch Settings from the WordPress
 */
export const fetchSettings = createAsyncThunk("events/fetchSettings", async (props) => {     
    let url = API_URL+'ecwp/v1/settings';
    let response = await axios.get(url);    
    //Check the shortcode has view attribute.
    if(props?.display !== "" && props?.display !== null && props?.display !== undefined){
        response.data.settings.default_view = props?.display;
    }
    return response.data;   
});

/****
 * Save Settings from the WordPress
 */
export const updateSettings = createAsyncThunk("events/savesettings", async (props) => { 
    let url = API_URL+'ecwp/v1/savesettings';
    const response = await axios.post(url, { ecwp_settings : props } );    
    return response.data;   
});

/****
 * Fetch Events from the WordPress default REST API
 */
export const fetchEvents = createAsyncThunk("events/fetchEvents", async (props) => { 
    const { year, mon, term, tax } = props;  
    let url = API_URL+'wp/v2/wood-event?year='+year+'&month='+mon+'&timezone='+userTimezone+'&per_page='+per_page;
    if(term !== null && tax !== null){
        url += '&'+tax+'='+term;
    }
    const response = await axios.get(url);    
    return response.data;   
});

/****
 * Search Events from the WordPress default REST API
 */
export const searchEvents = createAsyncThunk("events/searchEvents", async (props) => { 
    const { search } = props;  
    let url = API_URL+'wp/v2/wood-event?search='+search+'&per_page='+per_page;     
    const response = await axios.get(url);    
    return response.data;   
});

/****
 * Fetch Upcoming Events & Date for the Upcoming Event from the WordPress default REST API
 */
export const fetchLatestEvents = createAsyncThunk("events/fetchLatestEvents", async (props) => {  
    const { term, tax } = props; 
    const currentMonth = getMonth(new Date());
    const currentYear  = getYear(new Date());
    const currentDate  = getDate(new Date());

    let url = API_URL + 'wp/v2/wood-event?upcoming=true&currentMonth='+(currentMonth+1)+'&currentYear='+currentYear+'&currentDate='+currentDate+'&timezone='+userTimezone+'&per_page='+per_page;

    if(term !== null && tax !== null){
        url += '&'+tax+'='+term;
    }

    const upcoming = await axios.get(url);
    return upcoming.data;
})

export const datesSlice = createSlice({
    name: 'dates',
    initialState: {
        loading: false,
        today: new Date(),
        selDate: new Date(),        
        events: [],        
        search : false  ,
        latest: new Date(),
        saveText : false,
        settings : {
            date_format : 'F j, Y',
            time_format : 'g:i a',
            timezone : userTimezone,
            default_view :'',
        },
        pages : [],
    },
    reducers: {
        nextMonth: (state) => {
            state.today = add(startOfMonth(state.today), { years: 0, months: 1 })
        },
        prevMonth: (state) => {
            state.today = sub(startOfMonth(state.today), { years: 0, months: 1 })
        },
        setMonth: (state, action) => {
            state.today = action.payload;
        },
        setDay: (state, action) => {
            state.today = action.payload;
            state.settings.default_view = 'day';
        },
        setView: (state, action) => {
            state.settings.default_view = action.payload;
        },
        selectedDate: (state, action) => {
            state.selDate = action.payload;
        },
        changeSettings:(state, action) => {
            state.settings = action.payload;
            state.saveText = false;
        },
        changeSaveText:(state, action) => {
            state.saveText = action.payload;
        },
    },
    extraReducers: (builder) => {
        builder.addCase(fetchSettings.pending, (state) => {
            state.loading = true;
        }).addCase(fetchSettings.fulfilled, (state, action) => {
            state.settings = action.payload.settings; 
            state.pages = action.payload.pages; 
            state.loading = false;
        }).addCase(fetchSettings.rejected, (state) => {
            state.loading = true;
        }),
        builder.addCase(updateSettings.pending, (state) => {
            state.loading = true;
        }).addCase(updateSettings.fulfilled, (state, action) => {
            state.settings = action.payload; 
            state.loading = false;
            state.saveText = true;
        }).addCase(updateSettings.rejected, (state) => {
            state.loading = true;
        }),
        builder.addCase(fetchEvents.pending, (state) => {
            state.loading = true;
            state.search = false;
        }).addCase(fetchEvents.fulfilled, (state, action) => {
            state.events = action.payload; 
            state.loading = false;
        }).addCase(fetchEvents.rejected, (state) => {
            state.loading = true;
        }),
        builder.addCase(fetchLatestEvents.pending, (state) => {
            state.loading = true;
            state.search = false;
        }).addCase(fetchLatestEvents.fulfilled, (state, action) => {
            state.events = action.payload; 
            const upcoming = getUpcomingDate(action.payload);
            state.today = upcoming;
            state.latest = upcoming;
            state.loading = false;
        }).addCase(fetchLatestEvents.rejected, (state) => {
            state.loading = true;
        }),
        builder.addCase(searchEvents.pending, (state) => {
            state.events = []; 
            state.loading = true;
            state.search = true;
        }).addCase(searchEvents.fulfilled, (state, action) => {
            state.events = action.payload; 
            state.loading = false;
        }).addCase(searchEvents.rejected, (state) => {
            state.loading = true;
        })
    }
});

const getUpcomingDate = (events) => { 
    return (events && events.length > 0) ? fromUnixTime(events[0]?.ecwp?.startdate) : new Date()    
};


export const { nextMonth, prevMonth, setMonth, setView, setDay, selectedDate, changeSettings, changeSaveText } = datesSlice.actions;
export const val = (state) => state.dates;
export default datesSlice.reducer;